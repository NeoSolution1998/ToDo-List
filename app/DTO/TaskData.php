<?php

namespace App\DTO;

class TaskData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $status = 'pending'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'pending'
        );
    }
}
