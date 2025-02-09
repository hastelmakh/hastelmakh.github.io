<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Manifest;

class Manifest
{
    private array $map;

    public function __construct(string $path)
    {
        $this->loadManifest($path);
    }

    public function get(string $asset): ManifestEntry
    {
        return $this->map[$asset] ?? throw new \RuntimeException(sprintf('Asset "%s" not found in manifest.', $asset));
    }

    private function loadManifest(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('Manifest file "%s" not exist. Did you run "yarn vite build"?', $path));
        }

        $contents = file_get_contents($path);
        if (false === $contents) {
            throw new \RuntimeException(sprintf('Failed to read manifest file "%s".', $path));
        }

        $manifest = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        foreach ($manifest as $src => $data) {
            $this->map[$src] = new ManifestEntry(
                $data['src'],
                $data['file'],
                $data['isEntry'] ?? false,
                $data['css'] ?? [],
                $data['assets'] ?? [],
            );
        }
    }
}
