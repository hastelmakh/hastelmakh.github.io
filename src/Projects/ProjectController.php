<?php

declare(strict_types=1);

namespace App\Projects;

use Stasis\Controller\ControllerInterface;
use Stasis\Router\Router;
use Twig\Environment;

class ProjectController implements ControllerInterface
{
    public const string PARAM_PROJECT = 'project';

    public function __construct(
        private readonly Environment $twig,
        private readonly ProjectRepository $projectRepository,
    ) {}

    public function render(Router $router, array $parameters): string
    {
        /** @var Project $project */
        $project = $parameters[self::PARAM_PROJECT];

        $previous = $this->projectRepository->previous($project);
        $next = $this->projectRepository->next($project);

        $template = sprintf('@Projects~%s/details.html.twig', $project->slug);
        return $this->twig->render($template, [
            'project' => $project,
            'previous' => $previous,
            'next' => $next,
        ]);
    }
}
