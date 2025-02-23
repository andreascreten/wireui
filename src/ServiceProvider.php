<?php

namespace WireUi;

use Illuminate\Foundation\{AliasLoader, Application};
use Illuminate\Support;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use WireUi\Facades\WireUi;
use WireUi\Providers\{BladeDirectives, CustomMacros};
use WireUi\Support\ComponentResolver;
use WireUi\View\Compilers\WireUiTagCompiler;

/**
 * @property Application $app
 */
class ServiceProvider extends Support\ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->registerWireUI();

        $this->setupHeroiconsComponent();
    }

    public function boot(): void
    {
        CustomMacros::register();

        BladeDirectives::register();

        $this->registerTagCompiler();

        $this->registerBladeComponents();
    }

    private function registerTagCompiler(): void
    {
        Blade::precompiler(static function (string $string): string {
            return app(WireUiTagCompiler::class)->compile($string);
        });
    }

    private function registerConfig(): void
    {
        $this->loadRoutesFrom($this->srcDir('routes.php'));

        $this->loadTranslationsFrom($this->srcDir('lang'), 'wireui');

        $this->mergeConfigFrom($this->srcDir('config.php'), 'wireui');

        $this->loadViewsFrom($this->srcDir('resources/views'), 'wireui');

        $this->publishes(
            [$this->srcDir('lang') => $this->app->langPath('vendor/wireui')],
            'wireui.lang',
        );

        $this->publishes(
            [$this->srcDir('config.php') => $this->app->configPath('wireui.php')],
            'wireui.config',
        );
    }

    private function registerWireUI(): void
    {
        $this->app->singleton('WireUi', WireUi::class);

        $loader = AliasLoader::getInstance();

        $loader->alias('WireUi', WireUi::class);
    }

    private function setupHeroiconsComponent(): void
    {
        config()->set('wireui.heroicons.alias', 'heroicons');
    }

    private function srcDir(string $path): string
    {
        return __DIR__ . "/{$path}";
    }

    private function registerBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            $resolver = new ComponentResolver();

            foreach (config('wireui.components') as $component) {
                $blade->component($component['class'], $resolver->addPrefix($component['alias']));
            }
        });
    }
}
