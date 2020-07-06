<?php

namespace LiZhineng\IdentityVerification;

trait HandleIdentityVerification
{
    public function identityVerifications()
    {
        return $this->morphMany(IdentityVerification::class, 'auth');
    }

    public function identity(?String $scene = null)
    {
        $identity = $this->identityVerifications()
            ->scene($scene = $scene ?: $this->defaultScene())
            ->latest()
            ->first();

        if (! $identity) {
            return $this->newIdentity($scene);
        }

        return $identity;
    }

    public function newIdentity(?String $scene = null)
    {
        $scene = $scene ?: $this->defaultScene();

        return tap(new IdentityVerification, function ($verification) use ($scene) {
            $verification->auth()->associate($this);
            $verification->scene = $scene;
        });
    }

    public function getIdentityAttribute()
    {
        return $this->identity($this->defaultScene());
    }

    public function scopeScene($query, $scene)
    {
        return $query->where('scene', $scene);
    }

    protected function defaultScene()
    {
        return config('identity-verification.scene');
    }
}
