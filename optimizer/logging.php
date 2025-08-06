<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

$logger = new Logger("log");
$stream_handler = new StreamHandler("log.txt");
$logger->pushHandler($stream_handler);

$output = "%level_name% | %datetime% > %message%\n";
$dateFormat = "Y-n-j, g:i a";

$formatter = new LineFormatter(
    $output,
    $dateFormat,
);

$stream_handler->setFormatter($formatter);

?>