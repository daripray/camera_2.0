<?php
namespace App\Helpers;

class Logger {
    public static function write($message) {
        $logPath = storage_path('app/log.txt');
        file_put_contents($logPath, now() . " - " . $message . PHP_EOL, FILE_APPEND);
    }
}
