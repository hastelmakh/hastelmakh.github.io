<?php

declare(strict_types=1);

namespace App\Projects;

readonly class Project
{
    public string $routeName;

    /**
     * @param int $priority Higher number means displayed first
     */
    public function __construct(
        public string $slug,
        public int $priority,
        public string $name,
        public string $description,
    ) {
        $this->routeName = sprintf('project_%s', $this->slug);
    }
}
