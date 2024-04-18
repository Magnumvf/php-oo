<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\Aluno;

interface AlunoRepositoryInterface
{
    public function save(Aluno $aluno): void;

    public function find(int $id): ?Aluno;

    public function findAll(): array;

    public function remove(Aluno $aluno): void;
}