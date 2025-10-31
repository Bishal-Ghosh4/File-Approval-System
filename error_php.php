<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }
        .error-container { 
            max-width: 800px; 
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-heading { 
            color: #dc3545;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .error-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-heading">An Error Occurred</h1>
        <div class="error-details">
            <p><strong>Message:</strong> <?= htmlspecialchars($message ?? 'No error message provided', ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>File:</strong> <?= htmlspecialchars($filepath ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Line:</strong> <?= $line ?? 'Unknown' ?></p>
            <?php if (isset($severity)): ?>
                <p><strong>Severity:</strong> <?= $severity ?></p>
            <?php endif; ?>
        </div>
        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && !empty(debug_backtrace())): ?>
        <div class="error-stack">
            <h3>Stack Trace:</h3>
            <pre><?php 
                foreach (debug_backtrace() as $i => $trace) {
                    echo "#{$i} ";
                    if (isset($trace['file'])) {
                        echo htmlspecialchars(basename($trace['file']), ENT_QUOTES, 'UTF-8');
                        if (isset($trace['line'])) {
                            echo "({$trace['line']}): ";
                        }
                    }
                    if (isset($trace['class'])) {
                        echo htmlspecialchars($trace['class'] . $trace['type'], ENT_QUOTES, 'UTF-8');
                    }
                    if (isset($trace['function'])) {
                        echo htmlspecialchars($trace['function'], ENT_QUOTES, 'UTF-8') . "()";
                    }
                    echo "\n";
                }
            ?></pre>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>