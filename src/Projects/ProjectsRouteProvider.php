<?php

declare(strict_types=1);

namespace App\Projects;

use Stasis\Router\Route\Route;
use Stasis\Router\Route\RouteProviderInterface;

class ProjectsRouteProvider implements RouteProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
    ) {}

    public function routes(): iterable
    {
        $projects = $this->projectRepository->all();

        foreach ($projects as $project) {
            $path = sprintf('/%s', $project->slug);

            yield new Route(
                $path,
                ProjectController::class,
                $project->routeName,
                [ProjectController::PARAM_PROJECT => $project],
            );
        }
    }
}
