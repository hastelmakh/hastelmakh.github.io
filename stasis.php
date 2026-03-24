<?php

declare(strict_types=1);

use App\Core\ModuleProvider\ModuleProvider;
use App\Core\ReferenceParser;
use App\Core\TwigFactory;
use App\Credits\CreditsController;
use App\Home\HomeController;
use App\Projects\ProjectsRouteProvider;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Stasis\Config\ConfigInterface;
use Stasis\Extension\Twig\StasisTwigExtension;
use Stasis\Extension\Vite\StasisViteExtension;
use Stasis\Generator\Distribution\DistributionInterface;
use Stasis\Generator\Distribution\FilesystemDistribution;
use Stasis\Router\Route\Group;
use Stasis\Router\Route\Route;
use Twig\Environment;

return new class implements ConfigInterface
{
    private readonly ModuleProvider $moduleProvider;
    private readonly Environment $twig;
    private readonly ReferenceParser $referenceParser;

    public function __construct() {
        $this->moduleProvider = new ModuleProvider();
        $this->twig = new TwigFactory($this->moduleProvider)->create();
        $this->referenceParser = new ReferenceParser($this->moduleProvider);
    }

    public function routes(): iterable
    {
        return [
            new Route('/', HomeController::class, 'home'),
            new Route('/credits', CreditsController::class, 'credits'),
            new Group('/projects', ProjectsRouteProvider::class)
        ];
    }

    public function container(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAttributes(false);
        $builder->addDefinitions([
            Environment::class => $this->twig,
        ]);
        return $builder->build();
    }

    public function distribution(): DistributionInterface
    {
        return new FilesystemDistribution(__DIR__ . '/dist');
    }

    public function extensions(): iterable
    {
        return [
            new StasisTwigExtension($this->twig),
            StasisViteExtension::createWithReferenceParser(
                assetsSourcePath: __DIR__ . '/dist_assets/assets',
                manifestPath: __DIR__ . '/dist_assets/manifest.json',
                referenceParser: $this->referenceParser,
                assetsRoutePath: '/assets',
            )->withTwig($this->twig),
        ];
    }
};
