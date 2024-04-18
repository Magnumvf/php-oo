<?php

declare(strict_types=1);

echo "<h1>Bem vindo</h1>";
echo "<br>";
echo "<hr>";

$log = file_get_contents('envio.log');

$logLines = explode(PHP_EOL, $log);

foreach ($logLines as $line) {
    echo $line . '<br>';
}