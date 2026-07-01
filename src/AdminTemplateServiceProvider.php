<?php

namespace Denoyey\AdminTemplate;

use Denoyey\AdminTemplate\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class AdminTemplateServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
