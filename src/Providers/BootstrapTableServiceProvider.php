<?php

namespace Itszun\LaravelBootstrapTable\Providers;

use Error;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BootstrapTableServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        throw new Error("QwQ");
        Blade::directive('bootstrapTableScripts', function () {
            return "<?php echo now()->format('m/d/Y H:i'); ?>";
        });
        Blade::directive('bootstrapTableStyles', function () {
            return "<?php echo now()->format('m/d/Y H:i'); ?>";
        });
    }
}