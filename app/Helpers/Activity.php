<?php

use App\Models\Activity;

if (!function_exists('log_activity')) {

    function log_activity($type, $title, $description = null, $meta = [], $userId = null)
    {
        Activity::create([
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'meta' => $meta,
        ]);
    }

}