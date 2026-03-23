<?php

declare(strict_types=1);

use App\Core\ModuleProvider\ModuleProvider;
use App\Credits\CreditsController;
use App\Projects\ProjectsRouteProvider;
use App\Core\AssetMapper\AssetMapperFactory;
use App\Core\AssetMapper\AssetMapperInterface;
use App\Core\TwigFactory;
use App\Home\HomeController;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Stasis\Config\ConfigInterface;
use Stasis\Extension\Twig\StasisTwigExtension;
use Stasis\Generator\Distribution\DistributionInterface;
use Stasis\Generator\Distribution\FilesystemDistribution;
use Stasis\Router\Route\Asset;
use Stasis\Router\Route\Group;
use Stasis\Router\Route\Route;
use Twig\Environment;

return new class implements ConfigInterface
{
    private readonly Environment $twig;

    public function __construct() {
        $this->twig = new TwigFactory(new ModuleProvider())->create();
    }

    public function routes(): iterable
    {
        return [
            new Asset('/assets', __DIR__ . '/dist_assets/assets'),
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
            AssetMapperInterface::class => fn (AssetMapperFactory $factory) => $factory->create(__DIR__ . '/dist_assets/manifest.json'),
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
        ];
    }
};
