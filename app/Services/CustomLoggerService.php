<?php

namespace App\Services;

use App\Models\CustomLog;

class CustomLoggerService
{
    public function write($module, $severity, $message, $description = null, $metaData = [])
    {
        try {
            CustomLog::create([
                'user_id' => auth()->user()->id ? auth()->user()->id : null,
                'module' => $module,
                'severity' => $severity,
                'file' => NULL,
                'line' => NULL,
                'url' => url()->current(),
                'method' => \Request::method(),
                'message' => $message,
                'ip_address' => \Request::ip(),
                'description' => $description,
                'metadata' => json_encode($metaData),
                'is_read' => 0,
            ]);
        } catch (\Throwable $th) {
            CustomLog::create([
                'user_id' => auth()->user()->id ? auth()->user()->id : null,
                'module' => $module,
                'severity' => $severity,
                'file' => NULL,
                'line' => NULL,
                'url' => url()->current(),
                'method' => \Request::method(),
                'message' => NULL,
                'ip_address' => \Request::ip(),
                'description' => $message,
                'metadata' => json_encode($metaData),
                'is_read' => 0,
            ]);
        }
    }
}
