<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Support\Facades\Storage;
use LiZhineng\IdentityVerification\Artifact;
use LiZhineng\IdentityVerification\Exceptions\UnreachableUrl;

class ArtifactTest extends FeatureTest
{
    public function testItWorks()
    {
        Storage::fake();

        $path = Artifact::make(__DIR__.'/Fixtures/artifact.jpg')->localize('public')->path();

        Storage::disk('public')->assertExists($path);
    }

    public function testLocalizeUnreachableUrl()
    {
        $this->expectException(UnreachableUrl::class);

        Artifact::make('https://unreachable.dev')->localize('public');
    }
}
