<!DOCTYPE html>
<html>
<head>
    <title>Shopy - Debug Log Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .log-entry {
            padding: 10px;
            margin: 5px 0;
            border-left: 4px solid #007cba;
            background-color: #f8f9fa;
            font-family: monospace;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .info {
            border-left-color: #17a2b8;
        }
        .warning {
            border-left-color: #ffc107;
            background-color: #fff3cd;
        }
        .error {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .refresh-btn {
            background-color: #007cba;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shopy - Debug Log Viewer</h1>
        <button class="refresh-btn" onclick="location.reload()">Refresh Logs</button>
        
        <?php
        $logPath = __DIR__ . '/storage/logs/laravel.log';

        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            
            // Get the last 50 lines
            $lines = explode("\n", $logs);
            $lastLines = array_slice($lines, -50);
            
            echo "<h2>Recent Log Entries</h2>\n";
            
            foreach ($lastLines as $line) {
                if (!empty(trim($line))) {
                    $logClass = 'log-entry';
                    if (strpos($line, 'ERROR') !== false || strpos($line, 'ERROR') !== false) {
                        $logClass .= ' error';
                    } elseif (strpos($line, 'WARNING') !== false || strpos($line, 'WARN') !== false) {
                        $logClass .= ' warning';
                    } elseif (strpos($line, 'INFO') !== false) {
                        $logClass .= ' info';
                    }
                    
                    echo '<div class="' . $logClass . '">' . htmlspecialchars($line) . "</div>\n";
                }
            }
        } else {
            echo "<p style='color: red;'>Log file not found at: " . htmlspecialchars($logPath) . "</p>";
            echo "<p>Make sure you have run your application and generated some logs first.</p>";
        }
        ?>
    </div>
</body>
</html>