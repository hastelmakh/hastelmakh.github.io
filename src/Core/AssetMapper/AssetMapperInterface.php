<?php

declare(strict_types=1);

namespace App\Core\AssetMapper;

interface AssetMapperInterface
{
    /**
     * Returns tags to be inserted in the HTML head. Includes imports and preload tags.
     * Only entrypoints may be imported.
     */
    public function import(string $reference): string;

    /**
     * Returns the public path to the asset. Quotes in the path are urlencoded.
     */
    public function path(string $reference): string;
}
