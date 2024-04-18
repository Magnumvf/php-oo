<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Curso;
use App\Observer\Publisher\NewCoursePublisher;
use App\Observer\Listeners\CursoEmailObserver;
use App\Observer\Listeners\CursoWhatsappObserver;

class SendStudentNotificationAboutNewCourse
{
    private NewCoursePublisher $cursoPublisher;
    private CursoEmailObserver $cursoEmailObserver;
    private CursoWhatsappObserver $cursoWhatsappObserver;

    public function __construct(
        private Curso $curso
    ) {
        $this->cursoPublisher = new NewCoursePublisher($curso);
        $this->cursoEmailObserver = new CursoEmailObserver();
        $this->cursoWhatsappObserver = new CursoWhatsappObserver();
    }

    public function notify(): void
    {
        $this->cursoPublisher->attach($this->cursoEmailObserver);
        $this->cursoPublisher->attach($this->cursoWhatsappObserver);
        $this->cursoPublisher->notify();
    }
}