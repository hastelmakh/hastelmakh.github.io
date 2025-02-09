<?php

declare(strict_types=1);

namespace App\Core\Router;

use Stasis\Router\Router;

class RouterContainer
{
    public ?Router $router = null;

    public function getPath(string $name): string
    {
        if (null === $this->router) {
            throw new \LogicException(sprintf('Router not initialized. Consider setting %s::router before using.', self::class));
        }

        return $this->router->get($name)->path;
    }
}
