<?php

namespace Denoyey\AdminTemplate\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    protected $signature = 'denoyey:install';
    protected $description = 'Install the Laravel Admin Template and Auth Starter Kit';

    public function handle()
    {
        $this->info('🚀 Installing Laravel Admin Template by denoyey...');

        $this->publishFiles();
        $this->updateRoutes();
        $this->updateCss();
        $this->updatePackageJson();

        $this->requireComposerPackages();
        $this->buildNodePackages();

        $this->info('✅ Laravel Admin Template installed successfully!');
        $this->info('🎉 Anda siap menggunakannya! Silakan jalankan:');
        $this->warn('php artisan migrate');
        $this->warn('php artisan db:seed --class=UserSeeder');
    }

    protected function publishFiles()
    {
        $this->info('Copying template files...');
        
        $directories = [
            'app/Http/Controllers/Admin' => app_path('Http/Controllers/Admin'),
            'app/Http/Controllers/Auth' => app_path('Http/Controllers/Auth'),
            'app/Http/Middleware' => app_path('Http/Middleware'),
            'app/Http/Requests' => app_path('Http/Requests'),
            'app/Models' => app_path('Models'),
            'resources/views/layouts' => resource_path('views/layouts'),
            'resources/views/components/admin' => resource_path('views/components/admin'),
            'resources/views/pages/admin' => resource_path('views/pages/admin'),
            'resources/views/errors' => resource_path('views/errors'),
            'resources/js/admin' => resource_path('js/admin'),
            'resources/css/admin' => resource_path('css/admin'),
            'database/migrations' => database_path('migrations'),
            'database/seeders' => database_path('seeders'),
            'public/assets' => public_path('assets'),
        ];

        foreach ($directories as $from => $to) {
            $source = __DIR__.'/../../stubs/'.$from;
            if (File::exists($source)) {
                if (File::isDirectory($source)) {
                    File::copyDirectory($source, $to);
                } else {
                    File::ensureDirectoryExists(dirname($to));
                    File::copy($source, $to);
                }
            }
        }
        
        if (File::exists(__DIR__.'/../../stubs/resources/js/admin.js')) {
            File::copy(__DIR__.'/../../stubs/resources/js/admin.js', resource_path('js/admin.js'));
        }
    }

    protected function updateRoutes()
    {
        $this->info('Updating routes/web.php...');
        $webRoutesPath = base_path('routes/web.php');
        $currentRoutes = File::get($webRoutesPath);

        $useStatements = "";
        
        if (!str_contains($currentRoutes, 'use App\Http\Controllers\Admin\DashboardController;')) {
            $useStatements .= "use App\Http\Controllers\Admin\DashboardController;\n";
        }
        if (!str_contains($currentRoutes, 'use App\Http\Controllers\Auth\LoginController;')) {
            $useStatements .= "use App\Http\Controllers\Auth\LoginController;\n";
        }
        
        if (!empty($useStatements)) {
            $currentRoutes = preg_replace('/(<\?php\s*)/', "$1\n" . $useStatements, $currentRoutes, 1);
        }

        if (!str_contains($currentRoutes, "Route::prefix('/portal-admin')")) {
            $routesStub = File::get(__DIR__.'/../../stubs/routes/web-admin-stub.php');
            
            $routeContent = strstr($routesStub, '// Admin Portal Routes');
            
            if ($routeContent) {
                $currentRoutes .= "\n\n" . $routeContent;
            }
        }
        
        File::put($webRoutesPath, $currentRoutes);
    }

    protected function updateCss()
    {
        $this->info('Configuring Tailwind CSS v4 in app.css...');
        $appCssPath = resource_path('css/app.css');
        if (File::exists($appCssPath)) {
            $cssContent = File::get($appCssPath);
            if (!str_contains($cssContent, '@import "tailwindcss";')) {
                File::prepend($appCssPath, "@import \"tailwindcss\";\n");
            }
        } else {
            File::put($appCssPath, "@import \"tailwindcss\";\n");
        }
    }

    protected function updatePackageJson()
    {
        $this->info('Updating package.json dependencies...');
        $packageJsonPath = base_path('package.json');
        
        if (!File::exists($packageJsonPath)) return;

        $packages = json_decode(File::get($packageJsonPath), true);
        $newDependencies = [
            "@tailwindcss/vite" => "^4.0.0",
            "tailwindcss" => "^4.0.0",
            "axios" => "^1.7.4",
            "gsap" => "^3.15.0",
            "swiper" => "^12.1.4",
            "cropperjs" => "^1.6.2"
        ];

        $packages['dependencies'] = array_merge($packages['dependencies'] ?? [], $newDependencies);
        ksort($packages['dependencies']);
        File::put($packageJsonPath, json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL);
    }

    protected function requireComposerPackages()
    {
        $this->info('Installing Security, Logs & Image Packages...');
        
        Process::forever()->run('composer require spatie/laravel-permission intervention/image spatie/laravel-activitylog', function (string $type, string $output) {
            $this->output->write($output);
        });

        Process::run('php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        Process::run('php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"');
    }

    protected function buildNodePackages()
    {
        $this->info('Installing NPM Dependencies & Building Assets (Please wait)...');
        
        Process::forever()->run('npm install', function (string $type, string $output) {
            $this->output->write($output);
        });

        Process::forever()->run('npm run build', function (string $type, string $output) {
            $this->output->write($output);
        });
    }
}
