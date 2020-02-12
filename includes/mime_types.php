<?php

namespace arlo;

final class mime_types 
{
    public function __construct()
    {

    }

    public function listen()
    {
        add_filter('upload_mimes', function ($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        });
    }
}