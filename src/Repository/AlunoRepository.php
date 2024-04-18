<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Aluno;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AlunoRepository implements AlunoRepositoryInterface
{
    public EntityManager $entityManager;
    public EntityRepository $repository;

    public function __construct()
    {
        $this->entityManager = require dirname(__DIR__, 2).'/bootstrap.php';
        $this->repository = $this->entityManager->getRepository(Aluno::class);
    }

    public function save(Aluno $aluno): void
    {
        $this->entityManager->persist($aluno);
        $this->entityManager->flush();
    }

    public function find(int $id): ?Aluno
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function remove(Aluno $aluno): void
    {
        $this->entityManager->remove($aluno);
        $this->entityManager->flush();
    }
}