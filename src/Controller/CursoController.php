<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Curso;
use App\Repository\CursoRepository;
use App\UseCase\SendStudentNotificationAboutNewCourse;
use App\Validator\CursoValidator;

final class CursoController extends AbstractController
{
    public CursoValidator $validator;

    public CursoRepository $cursoRepository;

    public function __construct()
    {
        $this->validator = new CursoValidator();
        $this->cursoRepository = new CursoRepository();
    }

    public function listar(): void
    {
        parent::render('curso/listar', [
            'cursos' => $this->cursoRepository->findAll(),
        ]);
    }

    public function add(): void
    {
        if (true === empty($_POST)) {
            parent::render('curso/add');
            return;
        }

        $errors = $this->validator->validateRequest();

        if (false === empty($errors)) {
            $_SESSION['errors'] = $errors;

            parent::render('curso/add');
            return;
        }

        $curso = new Curso();
        $curso->name = $_POST['name'];
        $curso->description = $_POST['description'];
        $curso->status = (bool) $_POST['status'];
        $curso->types = $_POST['types'];

        // Foi um tentativa em vao
        $curso->setDetails();

        $this->cursoRepository->save($curso);

        /**
         * Dispara notificação para os alunos
         */
        (new SendStudentNotificationAboutNewCourse($curso))->notify();

        parent::redirect("/cursos/listar");
    }

    public function editar(): void
    {
        $id = (int)$_GET['id'];
        $curso = $this->cursoRepository->find($id);

        if (true === empty($_POST)) {
            parent::render('curso/editar', [
                'curso' => $curso,
            ]);
            return;
        }

        $curso->name = $_POST['name'];
        $curso->description = $_POST['description'];
        $curso->status = boolval($_POST['status']);
        $curso->types = $_POST['types'];

        // Foi um tentativa em vao
        $curso->setDetails();

        $this->cursoRepository->save($curso);

        parent::redirect('/cursos/listar');
    }

    public function excluir(): void
    {
        $id = (int)$_GET['id'];
        $curso = $this->cursoRepository->find($id);

        if ($curso !== null) {
            $this->cursoRepository->remove($curso);
        }

        parent::redirect("/cursos/listar");
    }
}
