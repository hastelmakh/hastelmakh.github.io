<?php

declare(strict_types=1);

namespace App\Core\AssetMapper;

use App\Core\AssetMapper\Asset\AssetProvider;
use App\Core\AssetMapper\Manifest\Manifest;
use App\Core\AssetMapper\Mapper\DevAssetMapper;
use App\Core\AssetMapper\Mapper\ManifestAssetMapper;
use App\Core\AssetMapper\Mapper\ReferenceParser;
use App\Core\AssetMapper\Mapper\TagProvider;

class AssetMapperFactory
{
    public function __construct(
        private ReferenceParser $referenceParser,
        private AssetProvider $assetFactory,
        private TagProvider $tagProvider,
    ) {}

    public function create(string $manifestPath): AssetMapperInterface
    {
        if ($this->isDev()) {
            return new DevAssetMapper($this->referenceParser, $this->assetFactory, $this->tagProvider);
        }

        $manifest = new Manifest($manifestPath);
        return new ManifestAssetMapper($manifest, $this->referenceParser, $this->assetFactory, $this->tagProvider);
    }

    private function isDev(): bool
    {
        $fp = @fsockopen('localhost', 5173);
        $isDev = $fp !== false;

        if (is_resource($fp)) {
            fclose($fp);
        }

        return $isDev;
    }
}
