<?php

return [

    /**
     * The access key id which retrieves from Aliyun.
     */
    'key' => env('IDENTITY_VERIFICATION_ACCESS_KEY_ID'),

    /**
     * The secret key corresponds to the access key above.
     */
    'secret' => env('IDENTITY_VERIFICATION_ACCESS_KEY_SECRET'),

    /**
     * The region which determines the location of API servers to process the requests.
     */
    'region' => env('IDENTITY_VERIFICATION_REGION'),

    /**
     * The disk which stores identity verification photos.
     */
    'disk' => 'public',

    /**
     * The path which stores identity verification photos.
     */
    'path' => '/',

    /**
     * The queue which processing artifacts localization.
     */
    'queue' => 'default',

    /**
     * The default scene for user to verify their identity.
     */
    'scene' => 'registration',

];
