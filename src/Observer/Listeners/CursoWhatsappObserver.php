<?php
declare(strict_types=1);

namespace App\Observer\Listeners;

use App\Repository\AlunoRepository;
use SplObserver;
use SplSubject;

class CursoWhatsappObserver implements SplObserver
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

        foreach ($students as $student) {
            echo "Enviando notificação no Whatsapp do aluno: {$student->name}, sobre o novo curso de : {$course->name} <br>";
        }
    }
}