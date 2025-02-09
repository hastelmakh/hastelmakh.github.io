<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Mapper;

use App\Core\AssetMapper\Asset\AssetProvider;
use App\Core\AssetMapper\AssetMapperInterface;
use App\Core\AssetMapper\Manifest\Manifest;

class ManifestAssetMapper implements AssetMapperInterface
{
    private const string BASE = '/';

    public function __construct(
        private Manifest $manifest,
        private ReferenceParser $referenceParser,
        private AssetProvider $assetFactory,
        private TagProvider $tagProvider,
    ) {}

    public function import(string $reference): string
    {
        $tags = [];

        $referencePath = $this->referenceParser->getPath($reference);
        $entry = $this->manifest->get($referencePath);
        $asset = $this->assetFactory->fromSourcePath($entry->path, self::BASE);

        if (!$entry->isModule) {
            throw new \LogicException(sprintf(
                'Unable to import asset "%s". Asset reference is not an entry point.',
                $reference,
            ));
        }

        foreach ($entry->assets as $path) {
            $moduleAsset = $this->assetFactory->fromSourcePath($path, self::BASE);
            if ($moduleAsset->isFont()) {
                $tags[] = $this->tagProvider->preloadFont($moduleAsset->publicPath, $moduleAsset->mime);
            }
        }

        foreach ($entry->css as $path) {
            $cssAsset = $this->assetFactory->fromSourcePath($path, self::BASE);
            $tags[] = $this->tagProvider->style($cssAsset->publicPath);
        }

        $tags[] = $this->tagProvider->module($asset->publicPath);
        $tags[] = $this->tagProvider->preloadModule($asset->publicPath);

        return implode("\n", $tags);
    }

    public function path(string $reference): string
    {
        $referencePath = $this->referenceParser->getPath($reference);
        $entry = $this->manifest->get($referencePath);
        $asset = $this->assetFactory->fromSourcePath($entry->path, self::BASE);
        return $asset->publicPath;
    }
}
