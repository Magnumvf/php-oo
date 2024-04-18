<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Strategy\FileLog;
use App\Strategy\DataBaseLog;
use App\Strategy\LogStrategyInterface;

class LogWriter
{
    protected LogStrategyInterface $strategy;

    public function __construct(
        protected string $message
    ) {
        $this->strategy = new FileLog();
//        $this->strategy = new DataBaseLog();
    }

    public function execute(): void
    {
        $this->strategy->write($this->message);
    }
}