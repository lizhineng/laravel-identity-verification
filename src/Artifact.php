<?php

namespace LiZhineng\IdentityVerification;

use Illuminate\Support\Facades\Storage;
use LiZhineng\IdentityVerification\Exceptions\UnreachableUrl;

class Artifact
{
    protected string $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public static function make(string $path)
    {
        return new static($path);
    }

    public function shouldLocalize()
    {
        return $this->isFromRemote();
    }

    public function isFromRemote()
    {
        return strpos($this->path, 'http://') === 0 ||
            strpos($this->path, 'https://') === 0;
    }

    public function localize($disk, $path = '/')
    {
        if (! $stream = @fopen($this->path, 'r')) {
            throw new UnreachableUrl(sprintf("The given url [%s] cannot be reached.", $this->path));
        }

        $temporaryFile = tempnam(sys_get_temp_dir(), 'identity-verification');
        file_put_contents($temporaryFile, $stream);

        $this->path = Storage::disk($disk)->putFile($path, $temporaryFile);

        return $this;
    }

    public function path()
    {
        return $this->path;
    }
}