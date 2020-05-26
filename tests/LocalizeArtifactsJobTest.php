<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Support\Facades\Storage;

class LocalizeArtifactsJobTest extends FeatureTest
{
    use VerifyIdentityManually;

    public function testClearUpArtifactsWhenErrorOccurred()
    {
        $disk = config('identity-verification.disk');

        Storage::fake($disk);

        $verification = $this->mockResponse($withImages = 1)->verification()->verify();

        Storage::disk($disk)->assertMissing($verification->portrait_path);
        Storage::disk($disk)->assertMissing($verification->id_card_portrait_path);
        Storage::disk($disk)->assertMissing($verification->id_card_emblem_path);
    }
}