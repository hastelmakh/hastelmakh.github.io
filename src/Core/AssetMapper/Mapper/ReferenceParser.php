<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Mapper;

use App\Core\ModuleProvider\ModuleProvider;

class ReferenceParser
{
    public function __construct(
        private ModuleProvider $moduleProvider,
    ) {}

    public function getPath(string $reference): string
    {
        preg_match('/^@([^\/~]+)(?:~([^\/]+))?\/(.+)/u', $reference, $matches);
        $moduleName = $matches[1] ?: null;
        $nestedDir = $matches[2] ?: null;
        $assetPath = $matches[3] ?: null;

        if ($moduleName === null || $assetPath === null) {
            throw new \LogicException(sprintf('Unable to parse asset "%s". Expected format @Module/path/to/asset.png.', $reference));
        }

        $module = $this->moduleProvider->fromName($moduleName);
        $nestedPath = $nestedDir !== null ? sprintf('/%s', $nestedDir) : '';
        return sprintf('%s/Resources%s/assets/%s', $module->relativePath, $nestedPath, $assetPath);
    }
}
