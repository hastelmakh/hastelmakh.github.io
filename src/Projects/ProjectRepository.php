<?php

declare(strict_types=1);

namespace App\Projects;

class ProjectRepository
{
    /** @var ?list<Project> */
    private ?array $projects = null;

    /** @var array<int, int> */
    private array $indexMap = [];

    /**
     * @return list<Project>
     */
    public function all(): array
    {
        return $this->getProjects();
    }

    public function next(Project $project): ?Project
    {
        $id = $this->getProjectId($project);
        $index = $this->indexMap[$id] ?? null;

        if ($index === null) {
            return null;
        }

        return $this->projects[$index + 1] ?? $this->projects[0];
    }

    public function previous(Project $project): ?Project
    {
        $id = $this->getProjectId($project);
        $index = $this->indexMap[$id] ?? null;

        if ($index === null) {
            return null;
        }

        return $this->projects[$index - 1] ?? $this->projects[count($this->projects) - 1];
    }

    private function getProjects(): array
    {
        if ($this->projects === null) {
            $projects = $this->collectProjectsFromResources();
            $this->projects = $this->sortProjectsByPriority($projects);
            $this->indexMap = $this->buildIndexMap($this->projects);
        }

        return $this->projects;
    }

    /**
     * @return list<Project>
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
     * @param list<Project> $projects
     * @return list<Project>
     */
    private function sortProjectsByPriority(array $projects): array
    {
        usort($projects, static function (Project $a, Project $b): int {
            return $b->priority <=> $a->priority;
        });

        return $projects;
    }

    /**
     * @param list<Project> $projects
     * @return array<int, int>
     */
    private function buildIndexMap(array $projects): array
    {
        $result = [];

        foreach ($projects as $index => $project) {
            $id = $this->getProjectId($project);
            $result[$id] = $index;
        }

        return $result;
    }

    private function getProjectId(Project $project): int
    {
        return spl_object_id($project);
    }
}
