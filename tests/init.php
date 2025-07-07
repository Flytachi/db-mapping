<?php

require 'vendor/autoload.php';

final class DebugDump
{
    public static function dump(mixed ...$values): never
    {
        self::dumpCli(...$values);
    }

    public static function dumpCli(mixed ...$values): never
    {
        $info = self::info();
        echo "\033[31m" . "[====================== DUMP and DIE ======================]\033[0m\n";
        echo "\033[31m" . ">---------------------------------------------------------- \033[0m\n";
        foreach ($values as $key => $value) {
            echo match (gettype($value)) {
                'NULL'               => "\033[37mnull",
                'boolean'            => "\033[32m" . var_export($value, true),
                'integer', 'double'  => "\033[36m" . var_export($value, true),
                'object', 'array'    => "\033[35m" . print_r($value, true),
                'string'             => "\033[33m" . var_export($value, true),
                default              => "\033[31m" . var_export($value, true)
            };
            echo "\033[0m\n";
            if (isset($values[$key + 1])) {
                echo "\033[31m" . ">- \033[0m\n";
            }
        }
        echo "\033[31m" . ">---------------------------------------------------------- \033[0m\n";
        echo "\033[31m" . "[====================== DUMP and DIE ======================]\033[0m\n";
        die();
    }

    private static function info(): array
    {
        $backtrace = debug_backtrace();
        $line = $backtrace[3]['line'];
        $file = $backtrace[3]['file'];
        $delta = null;

        return [
            'file' => $file,
            'line' => $line,
            'delta' => $delta,
            'time' => date(DATE_ATOM),
        ];
    }
}

function dd(...$values): never
{
    DebugDump::dump(...$values);
}
