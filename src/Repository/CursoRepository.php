<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Curso;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CursoRepository implements CursoRepositoryInterface
{
    public EntityManager $entityManager;
    public EntityRepository $repository;

    public function __construct()
    {
        $this->entityManager = require dirname(__DIR__, 2).'/bootstrap.php';
        $this->repository = $this->entityManager->getRepository(Curso::class);
    }

    public function save(Curso $curso): void
    {
        $this->entityManager->persist($curso);
        $this->entityManager->flush();
    }

    public function find(int $id): ?Curso
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function remove(Curso $curso): void
    {
        $this->entityManager->remove($curso);
        $this->entityManager->flush();
    }
}