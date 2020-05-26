<?php

namespace LiZhineng\IdentityVerification\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use LiZhineng\IdentityVerification\Artifact;
use LiZhineng\IdentityVerification\Exceptions\UnreachableUrl;
use LiZhineng\IdentityVerification\IdentityVerification;

class LocalizeArtifacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model of identity verification.
     *
     * @var IdentityVerification $verification
     */
    public IdentityVerification $verification;

    public function __construct(IdentityVerification $verification)
    {
        $this->verification = $verification;
        $this->queue = config('identity-verification.queue');
    }

    public function handle()
    {
        try {
            $this->localize();
        } catch (UnreachableUrl $e) {
            $this->clearUp();
        }
    }

    /**
     * The artifact paths which needs to be localized.
     *
     * @return string[]
     */
    protected function fields()
    {
        return [
            'portrait_path',
            'id_card_portrait_path',
            'id_card_emblem_path',
        ];
    }

    protected function localize(): void
    {
        $disk = config('identity-verification.disk');
        $path = config('identity-verification.path');

        foreach ($this->fields() as $field) {
            if (! $this->verification->$field) {
                continue;
            }

            $artifact = Artifact::make($this->verification->$field);

            if ($artifact->shouldLocalize()) {
                $this->verification->$field = $artifact->localize($disk, $path)->path();
            }
        }

        if ($this->verification->isDirty($this->fields())) {
            $this->verification->save();
        }
    }

    public function clearUp()
    {
        $disk = config('identity-verification.disk');

        foreach ($this->fields() as $field) {
            if ($this->verification->$field) {
                Storage::disk($disk)->delete($this->verification->$field);
            }
        }
    }
}