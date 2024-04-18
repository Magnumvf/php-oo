<?php
declare(strict_types=1);

namespace App\Observer\Listeners;

use App\Repository\AlunoRepository;
use App\UseCase\LogWriter;
use SplObserver;
use SplSubject;

class CursoEmailObserver implements SplObserver
{
    private AlunoRepository $alunoRepository;

    public function __construct()
    {
        $this->alunoRepository = new AlunoRepository();
    }

    public function update(SplSubject $subject): void
    {
        $course = $subject->getCourseToNotify();
        $students = $this->alunoRepository->findAll();

        $msg = '';
        foreach ($students as $student) {
            $msg .= "Enviando email de notificação para o aluno: {$student->name}, sobre o novo curso: {$course->name}" . PHP_EOL;
        }

        $logWriter = new LogWriter($msg);
        $logWriter->execute();

    }
}