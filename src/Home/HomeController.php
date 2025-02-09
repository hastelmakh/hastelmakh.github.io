<?php

declare(strict_types=1);

namespace App\Home;

use App\Projects\ProjectRepository;
use Stasis\Controller\ControllerInterface;
use Stasis\Router\Router;
use Twig\Environment;

class HomeController implements ControllerInterface
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ProjectRepository $projectRepository,
    ) {}

    public function render(Router $router, array $parameters): string
    {
        $projects = $this->projectRepository->all();

        return $this->twig->render('@Home/home.html.twig', [
            'projects' => $projects,
        ]);
    }
}
