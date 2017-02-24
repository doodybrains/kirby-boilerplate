<?php

if (! file_exists(__DIR__ . DS . '.env')) {
    throw new Exception('Copy the ".env.example" file and name it ".env".');
}

require __DIR__ . DS . '.env';

$kirby = kirby();

kirby()->roots()->index = __DIR__;
kirby()->urls()->index = url::base();

kirby()->roots()->assets = kirby()->roots()->index() . DS . 'public';
kirby()->urls()->assets = kirby()->urls()->index();

kirby()->roots()->avatars = kirby()->roots()->assets() . DS . 'avatars';
kirby()->urls()->avatars = kirby()->urls()->assets() . '/avatars';

kirby()->urls()->content = kirby()->urls()->index();

kirby()->roots()->thumbs = kirby()->roots()->assets() . DS . 'thumbs';
kirby()->urls()->thumbs = kirby()->urls()->assets() . '/thumbs';
