<?php

declare(strict_types=1);

namespace App\Core\Router;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterTwigExtension extends AbstractExtension
{
    public function __construct(
        private RouterContainer $router,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', $this->router->getPath(...)),
        ];
    }
}
