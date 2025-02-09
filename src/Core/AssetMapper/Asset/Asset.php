<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Asset;

final readonly class Asset
{
    public function __construct(
        public string $sourcePath,
        public string $publicPath,
        public string $extension,
        public ?string $mime = null,
    ) {}

    public function isScript(): bool
    {
        return $this->extension === 'js';
    }

    public function isFont(): bool
    {
        return in_array($this->extension, ['ttf', 'otf', 'woff', 'woff2'], true);
    }
}
