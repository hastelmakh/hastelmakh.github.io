<?php

declare(strict_types=1);

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
    #[\Override]
    public function routes(): iterable
    {
        return [
            new Route('/', HomeController::class, 'home'),
            new Route('/credits', CreditsController::class, 'credits'),
            new Group('/projects', ProjectsRouteProvider::class)
        ];
    }

    #[\Override]
    public function container(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAttributes(false);
        $builder->addDefinitions([
            Environment::class => fn (TwigFactory $factory) => $factory->create(),
            StasisTwigExtension::class => fn (Environment $twig) => new StasisTwigExtension($twig),
            StasisViteExtension::class => function (Environment $twig, ReferenceParser $referenceParser) {
                return StasisViteExtension::createWithReferenceParser(
                    assetsSourcePath: __DIR__ . '/dist_assets/assets',
                    manifestPath: __DIR__ . '/dist_assets/manifest.json',
                    referenceParser: $referenceParser,
                    assetsRoutePath: '/assets',
                )->withTwig($twig);
            }
        ]);
        return $builder->build();
    }

    #[\Override]
    public function distribution(): DistributionInterface
    {
        return new FilesystemDistribution(__DIR__ . '/dist');
    }

    #[\Override]
    public function extensions(): iterable
    {
        return [
            StasisTwigExtension::class,
            StasisViteExtension::class,
        ];
    }
};
