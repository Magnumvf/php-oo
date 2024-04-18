<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\Curso;

interface CursoRepositoryInterface
{
    public function save(Curso $curso): void;

    public function find(int $id): ?Curso;

    public function findAll(): array;

    public function remove(Curso $curso): void;
}