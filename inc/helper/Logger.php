<?php
namespace Tarikul\TJMK\Inc\Helper;

class Logger
{
    public static function log($message)
    {
        if (WP_DEBUG === true) {
            $log = '[' . date('Y-m-d H:i:s') . '] ' . print_r($message, true) . PHP_EOL;
            file_put_contents(TJMK_PLUGIN_DIR . 'logs.log', $log, FILE_APPEND);
        }
    }
}
