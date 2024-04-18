<?php

declare(strict_types=1);

namespace App\Strategy;

class FileLog implements LogStrategyInterface
{
    public function write(string $message): void
    {
        /**
         * Aqui tem a logica para gravar em arquivo
         */

        $log = '';
        $logLines = explode(PHP_EOL, $message);
        foreach ($logLines as $line) {
            if (empty($line)) {
                continue;
            }

            $log .= "GRAVA LOG NO ARQUIVO {$line}" . PHP_EOL;
        }

        file_put_contents('envio.log', $log, FILE_APPEND | LOCK_EX);
    }
}
