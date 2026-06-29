<?php

if (!function_exists('__trans')) {
    function __trans($id, $en) {
        return \Illuminate\Support\Facades\App::getLocale() === 'en' ? $en : $id;
    }
}
