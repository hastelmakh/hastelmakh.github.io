<?php

declare(strict_types=1);

namespace App\Core\ModuleProvider;

use Symfony\Component\Filesystem\Path;

class ModuleProvider
{
    private ?string $rootDir = null;

    /**
     * @return Module[]
     */
    public function all(): array
    {
        $result = [];

        $root = $this->getRootDir();
        $paths = glob($root . '/src/*', GLOB_ONLYDIR);
        foreach ($paths as $path) {
            $result[] = $this->fromPath($path);
        }

        return $result;
    }

    /**
     * @param string $path Absolute or relative (from project root) path to any directory within a module.
     */
    public function fromPath(string $path): Module
    {
        $root = $this->getRootDir();
        $path = Path::makeAbsolute($path, $root);

        if (!Path::isBasePath($root, $path)) {
            throw new \LogicException(sprintf('Path "%s" is not within the project root directory "%s".', $path, $root));
        }

        $prefix = preg_quote($root, '/');
        preg_match('/^' . $prefix . '\/src\/[^\/]+/u', $path, $matches);
        $absolutePath = $matches[0] ?? null;

        if (!is_dir($absolutePath)) {
            throw new \LogicException(sprintf('Module "%s" does not exist.', $absolutePath));
        }

        preg_match('/src\/([^\/]+)$/u', $absolutePath, $matches);
        $name = $matches[1] ?? null;

        if ($name === null) {
            throw new \LogicException(sprintf('Unable to get module name from path "%s".', $absolutePath));
        }

        $relativePath = Path::makeRelative($absolutePath, $root);

        return new Module($name, $absolutePath, $relativePath);
    }

    public function fromName(string $name): Module
    {
        $path = sprintf('src/%s', $name);
        return $this->fromPath($path);
    }

    private function getRootDir(): string
    {
        if ($this->rootDir === null) {
            $this->rootDir = Path::canonicalize(__DIR__ . '/../../..');
            $composer = $this->rootDir . '/composer.json';
            if (!is_file($composer)) {
                throw new \LogicException('Unable to locate project root directory. Did you move the module provider class to a different directory?');
            }
        }

        return $this->rootDir;
    }
}
