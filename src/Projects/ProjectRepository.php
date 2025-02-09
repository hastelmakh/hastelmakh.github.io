<?php

declare(strict_types=1);

namespace App\Projects;

class ProjectRepository
{
    /** @var ?array<Project> */
    private ?array $projects = null;

    public function all(): array
    {
        return $this->getProjects();
    }

    private function getProjects(): array
    {
        if ($this->projects === null) {
            $projects = $this->collectProjectsFromResources();
            $this->projects = $this->sortProjectsByPriority($projects);
        }

        return $this->projects;
    }

    /**
     * @return array<Project>
     */
    private function collectProjectsFromResources(): array
    {
        $result = [];

        $paths = glob(__DIR__ . '/Resources/*/project.php');
        foreach ($paths as $path) {
            $project = require $path;

            if (!$project instanceof Project) {
                throw new \LogicException(sprintf(
                    'Project meta file "%s" does not return an instance of %s.',
                    $path,
                    Project::class,
                ));
            }

            $result[] = $project;
        }

        return $result;
    }

    /**
     * @param array<Project> $projects
     * @return array<Project>
     */
    private function sortProjectsByPriority(array $projects): array
    {
        usort($projects, static function (Project $a, Project $b): int {
            return $b->priority <=> $a->priority;
        });

        return $projects;
    }
}
