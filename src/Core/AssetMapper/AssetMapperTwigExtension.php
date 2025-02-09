<?php

declare(strict_types=1);

namespace App\Core\AssetMapper;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetMapperTwigExtension extends AbstractExtension
{
    public function __construct(
        private AssetMapperInterface $assetMapper,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_import', $this->assetMapper->import(...), ['is_safe' => ['html']]),
            new TwigFunction('asset_path', $this->assetMapper->path(...), ['is_safe' => ['html']]),
        ];
    }
}
