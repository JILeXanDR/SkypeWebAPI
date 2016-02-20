<?php

namespace WebSkype;

class Logger
{
    static public $logs = [];

    static public function append($log)
    {
        static::$logs[] = $log;
    }

    public static function write()
    {
        if (count(static::$logs)) {
            dump(static::$logs);
        }
    }
}