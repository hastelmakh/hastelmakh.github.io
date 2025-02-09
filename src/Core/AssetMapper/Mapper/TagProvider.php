<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Mapper;

class TagProvider
{
    public function module(string $path): string
    {
        return sprintf('<script type="module" src="%s"></script>', $path);
    }

    public function script(string $path): string
    {
        return sprintf('<script src="%s"></script>', $path);
    }

    public function style(string $path): string
    {
        return sprintf('<link rel="stylesheet" type="text/css" href="%s">', $path);
    }

    public function preloadModule(string $path): string
    {
        return sprintf('<link rel="modulepreload" href="%s">', $path);
    }

    public function preloadStyle(string $path): string
    {
        return sprintf('<link rel="preload" as="style" type="text/css" href="%s">', $path);
    }

    public function preloadFont(string $path, ?string $type = null): string
    {
        $type = $type !== null ? sprintf(' type="%s"', $type) : '';
        return sprintf('<link rel="preload" as="font"%s href="%s" crossorigin>', $type, $path);
    }

    public function preloadScript(string $path): string
    {
        return sprintf('<link rel="preload" as="script" href="%s">', $path);
    }

    public function preloadImage(string $path, ?string $type = null): string
    {
        $type = $type !== null ? sprintf(' type="%s"', $type) : '';
        return sprintf('<link rel="preload" as="image"%s href="%s">', $type, $path);
    }
}
