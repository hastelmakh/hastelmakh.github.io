<?php

declare(strict_types=1);

namespace App\Core\AssetMapper\Asset;

class AssetProvider
{
    public function fromSourcePath(string $sourcePath, string $base = '/'): Asset
    {
        $publicPath = $this->getPublicPath($base, $sourcePath);
        $extension = $this->getExtension($sourcePath);
        $mime = $this->getMime($extension);

        return new Asset($sourcePath, $publicPath, $extension, $mime);
    }

    private function getPublicPath(string $base, string $path): string
    {
        $fullPath = sprintf('%s/%s', rtrim($base, '/'), ltrim($path, '/'));
        return str_replace(array('"', '\''), ['%22', '%27'], $fullPath);
    }

    private function getExtension(string $path): string
    {
        preg_match('/\.(\w+)$/u', $path, $matches);
        return $matches[1] ?? '';
    }

    private function getMime(string $extension): ?string
    {
        return match ($extension) {
            'js' => 'text/javascript',
            'css' => 'text/css',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/vnd.microsoft.ico',
            default => null,
        };
    }
}
