<?php

namespace App\Services;

use Swis\Filament\Backgrounds\Contracts\ProvidesImages;
use Swis\Filament\Backgrounds\Image;

class SingleImageProvider implements ProvidesImages
{
    public function getImage(): Image
    {
        return new Image(
            'login-bg',
            url('/img/login_bg.jpeg'),
        );
    }
}
