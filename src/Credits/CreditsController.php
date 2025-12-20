<?php

declare(strict_types=1);

namespace App\Credits;

use Stasis\Controller\ControllerInterface;
use Stasis\Router\Router;
use Twig\Environment;

class CreditsController implements ControllerInterface
{
    public function __construct(
        private readonly Environment $twig,
    ) {}

    public function render(Router $router, array $parameters): string
    {
        return $this->twig->render('@Credits/credits.html.twig');
    }
}
