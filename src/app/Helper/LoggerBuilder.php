<?php

namespace Rezabdullah\Helper;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerBuilder
{
    public static function run(): Logger
    {
        require_once __DIR__ . '/../../config/storage.php';
        
        $storageConfig = getStorageConfig();
        
        $dateFormat = "Y-m-j h:i:s P";
        $output = "[%datetime%] %channel%.%level_name%: %message%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        
        $stream = new StreamHandler($storageConfig['logPath']);
        $stream->setFormatter($formatter);

        $channel = isset($_SERVER['X_USER_REQUEST_ID']) ? $_SERVER['X_USER_REQUEST_ID'] : '';
        
        $logger = new Logger($channel);
        $logger->pushHandler($stream);

        return $logger;
    }
}