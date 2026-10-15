<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('Quill:about', function () {
    $this->comment('Quill Motors landing page starter is ready.');
})->purpose('Show information about the Quill Motors starter');
