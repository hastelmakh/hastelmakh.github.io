<?php

declare(strict_types=1);

namespace App\Core\Router;

use Stasis\EventDispatcher\Event\SiteGenerate\SiteGenerateData;
use Stasis\EventDispatcher\Listener\SiteGenerateInterface;
use Stasis\Extension\ExtensionInterface;

class RouterStasisExtension implements ExtensionInterface, SiteGenerateInterface
{
    public function __construct(
        private readonly RouterContainer $router,
    ) {}

    public function listeners(): iterable
    {
        return [$this];
    }

    public function onSiteGenerate(SiteGenerateData $data): void
    {
        $this->router->router = $data->router;
    }
}
