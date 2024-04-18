<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Table;

#[Entity()] #[Table(name: 'course')]
class Curso
{
    #[Id] #[GeneratedValue] #[Column(type: 'integer')]
    public int $id;
    
    #[Column(length: 30, nullable: false)]
    public string $name;

    #[Column]
    public string $description;

    #[Column(type: 'boolean')]
    public bool $status;

    #[Column]
    public string $types;

    #[Column(type: 'integer')]
    public int $workload;

    public function setDetails()
    {
        if ($this->types === 'formacao') {
            $this->workload = 192;
        } else if ($this->types === 'curta_duracao') {
            $this->workload = 16;
        } else if ($this->types === 'media_duracao') {
            $this->workload = 44;
        } else if ($this->types === 'avancada') {
            $this->workload = 96;
        } else if ($this->types === 'intensivo') {
            $this->workload = 72;
        }
    }

    public function getTypes(): array
    {
        return [
            'formacao' => 'Formação',
            'curta_duracao' => 'Curta Duração',
            'media_duracao' => 'Média Duração',
            'avancada' => 'Avançada',
            'intensivo' => 'Intensivo',
        ];
    }

    // public function __construct(string $name) {
    //     $this->name = $name;
    // }
}
