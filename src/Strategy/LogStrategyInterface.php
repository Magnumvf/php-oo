<?php

declare(strict_types=1);

namespace App\Strategy;

interface LogStrategyInterface
{
    public function write(string $message): void;
}