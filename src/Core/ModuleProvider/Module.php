<?php

declare(strict_types=1);

namespace App\Core\ModuleProvider;

readonly class Module
{
    public function __construct(
        public string $name,
        public string $absolutePath,
        public string $relativePath,
    ) {}
}
