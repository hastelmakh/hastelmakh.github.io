<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Mapper;

use App\Core\AssetMapper\Asset\Asset;
use App\Core\AssetMapper\Asset\AssetProvider;
use App\Core\AssetMapper\AssetMapperInterface;

class DevAssetMapper implements AssetMapperInterface
{
    public function __construct(
        private ReferenceParser $referenceParser,
        private AssetProvider $assetFactory,
        private TagProvider $tagProvider,
    ) {}

    public function import(string $reference): string
    {
        $tags = [];

        $asset = $this->getAsset($reference);

        if (!$asset->isScript()) {
            throw new \LogicException(sprintf(
                'Unable to import asset "%s". Only JavaScript modules can be imported.',
                $reference,
            ));
        }

        $tags[] = $this->tagProvider->module($asset->publicPath);
        $tags[] = $this->tagProvider->preloadModule($asset->publicPath);

        return implode("\n", $tags);
    }

    public function path(string $reference): string
    {
        return $this->getAsset($reference)->publicPath;
    }

    private function getAsset(string $reference): Asset
    {
        $referencePath = $this->referenceParser->getPath($reference);
        return $this->assetFactory->fromSourcePath($referencePath, '//localhost:5173');
    }
}
