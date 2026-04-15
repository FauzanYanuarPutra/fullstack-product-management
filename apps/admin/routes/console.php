<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:test', function () {
    $this->comment('Product Manager admin panel is configured.');
});

