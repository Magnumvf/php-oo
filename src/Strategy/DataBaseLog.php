<?php

declare(strict_types=1);

namespace App\Strategy;

class DataBaseLog implements LogStrategyInterface
{

    public function write(string $message): void
    {
        /**
         * Aqui tem a logica para gravar no Bando de Dados
         */

        $log = '';
        $logLines = explode(PHP_EOL, $message);
        foreach ($logLines as $line) {
            if (empty($line)) {
                continue;
            }

            $log .= "GRAVA LOG NO BANCO {$line}" . PHP_EOL;
        }

        file_put_contents('envio.log', $log, FILE_APPEND | LOCK_EX);
    }
}
