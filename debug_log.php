<?php
// Debug file to help with error tracking

// This is a simple script to help you view the Laravel logs
// To use this, you can run it with PHP after encountering issues

$logPath = __DIR__ . '/storage/logs/laravel.log';

if (file_exists($logPath)) {
    $logs = file_get_contents($logPath);
    
    // Get the last 50 lines
    $lines = explode("\n", $logs);
    $lastLines = array_slice($lines, -50);
    
    echo "=== LAST 50 LINES OF LOGS ===\n\n";
    foreach ($lastLines as $line) {
        echo $line . "\n";
    }
} else {
    echo "Log file not found at: " . $logPath . "\n";
    echo "Make sure you have run your application and generated some logs first.\n";
}