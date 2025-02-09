<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\AssetMapper\AssetMapperTwigExtension;
use App\Core\ModuleProvider\Module;
use App\Core\ModuleProvider\ModuleProvider;
use App\Core\Router\RouterTwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigFactory
{
    public function __construct(
        private ModuleProvider $moduleProvider,
        private AssetMapperTwigExtension $assetMapperExtension,
        private RouterTwigExtension $routerExtension,
    ) {}

    public function create(): Environment
    {
        $loader = $this->createLoader();

        $twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'strict_variables' => true,
            'autoescape' => 'html',
        ]);

        $twig->addExtension($this->assetMapperExtension);
        $twig->addExtension($this->routerExtension);

        return $twig;
    }

    private function createLoader(): FilesystemLoader
    {
        $modules = $this->moduleProvider->all();
        if (empty($modules)) {
            throw new \LogicException('No modules found.');
        }

        $loader = new FilesystemLoader();
        foreach ($modules as $module) {
            $templatePath = $module->absolutePath . '/Resources/templates';
            $this->addPathIfExist($loader, $templatePath, $module->name);

            $this->processNestedTemplates($loader, $module);
        }

        return $loader;
    }

    private function processNestedTemplates(FilesystemLoader $loader, Module $module): void
    {
        $paths = glob($module->absolutePath . '/Resources/*/templates', GLOB_ONLYDIR);
        foreach ($paths as $path) {
            $dir = dirname($path, 1);
            $slug = basename($dir);
            $namespace = sprintf('%s~%s', $module->name, $slug);
            $this->addPathIfExist($loader, $path, $namespace);
        }
    }

    private function addPathIfExist(FilesystemLoader $loader, string $path, string $namespace): void
    {
        if (is_dir($path)) {
            $loader->addPath($path, $namespace);
        }
    }
}
