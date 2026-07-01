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
        $this->updateBootstrapApp();
        $this->updateCss();
        $this->updatePackageJson();
        $this->updateSessionConfig();
        $this->updateEnvironmentFile();
        $this->requireComposerPackages();
        $this->updatePermissionConfig();
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
            'app/Rules' => app_path('Rules'),
            'app/Services' => app_path('Services'),
            'app/Livewire' => app_path('Livewire'),
            'resources/views/layouts' => resource_path('views/layouts'),
            'resources/views/components/admin' => resource_path('views/components/admin'),
            'resources/views/components/public' => resource_path('views/components/public'),
            'resources/views/pages/admin' => resource_path('views/pages/admin'),
            'resources/views/errors' => resource_path('views/errors'),
            'resources/views/livewire' => resource_path('views/livewire'),
            'resources/views/vendor' => resource_path('views/vendor'),
            'resources/js/admin' => resource_path('js/admin'),
            'resources/css/admin' => resource_path('css/admin'),
            'resources/css/components' => resource_path('css/components'),
            'database/migrations' => database_path('migrations'),
            'database/seeders' => database_path('seeders'),
            'public/assets' => public_path('assets'),
            'public/src' => public_path('src'),
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

        $useStatements = '';

        $controllers = [
            'App\Http\Controllers\Admin\DashboardController',
            'App\Http\Controllers\Admin\GlobalSearchController',
            'App\Http\Controllers\Admin\ProfileController',
            'App\Http\Controllers\Admin\AccessManagement\RoleController',
            'App\Http\Controllers\Admin\AccessManagement\UserController',
            'App\Http\Controllers\Admin\AccessManagement\ActivityLogController',
            'App\Http\Controllers\Auth\LoginController',
        ];

        foreach ($controllers as $controller) {
            if (! str_contains($currentRoutes, "use {$controller};")) {
                $useStatements .= "use {$controller};\n";
            }
        }

        if (! empty($useStatements)) {
            $currentRoutes = preg_replace('/(<\?php\s*)/', "$1\n".$useStatements, $currentRoutes, 1);
        }

        if (! str_contains($currentRoutes, "Route::prefix('/portal-admin')")) {
            $routesStub = File::get(__DIR__.'/../../stubs/routes/web-admin-stub.php');

            $routeContent = strstr($routesStub, '// Admin Portal Routes');

            if ($routeContent) {
                $currentRoutes .= "\n\n".$routeContent;
            }
        }

        File::put($webRoutesPath, $currentRoutes);
    }

    protected function updateBootstrapApp()
    {
        $this->info('Registering middleware aliases in bootstrap/app.php...');
        $appPath = base_path('bootstrap/app.php');
        if (File::exists($appPath)) {
            $content = File::get($appPath);
            if (! str_contains($content, 'SecurityHeadersMiddleware')) {
                $aliasConfig = <<<PHP
        \$middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        \$middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\IdleTimeoutMiddleware::class,
        ]);
        \$middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'prevent-spam' => \App\Http\Middleware\PreventSpamSubmit::class,
        ]);
        \$middleware->redirectTo(
            guests: '/portal-admin/login',
            users: '/portal-admin/dashboard'
        );
PHP;

                $exceptionsConfig = <<<PHP
\$exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException \$e, \Illuminate\Http\Request \$request) {
            if (\$request->is('portal-admin') || \$request->is('portal-admin/*')) {
                \$required = \$e->getRequiredPermissions();
                if (is_array(\$required) && in_array('access_admin_panel', \$required)) {
                    \Illuminate\Support\Facades\Auth::logout();
                    \$request->session()->invalidate();
                    \$request->session()->regenerateToken();

                    return redirect()->route('admin.login')->withErrors([
                        'email' => 'Akun Anda tidak memiliki akses ke portal admin.',
                    ]);
                }

                return response()->view('errors.403-admin', ['exception' => \$e], 403);
            }
        });
PHP;

                $content = preg_replace('/(withMiddleware\(function\s*\(Middleware\s*\$middleware\)(?:\s*:\s*void)?\s*\{)/', "$1\n".$aliasConfig, $content);
                $content = preg_replace('/(withExceptions\(function\s*\(Exceptions\s*\$exceptions\)(?:\s*:\s*void)?\s*\{)/', "$1\n".$exceptionsConfig, $content);

                File::put($appPath, $content);
            }
        }
    }

    protected function updateCss()
    {
        $this->info('Configuring Tailwind CSS v4 in app.css...');
        $appCssPath = resource_path('css/app.css');

        $themeConfig = <<<'CSS'
@import 'tailwindcss';

@import '@fontsource/inter/400.css';
@import '@fontsource/inter/500.css';
@import '@fontsource/inter/600.css';
@import '@fontsource/inter/700.css';

@theme {
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif,
        'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';

    --color-hijau: #56ab4f;
    --color-hijau-dark: #659e4b;
    --color-hijau-light: #7dc776;
    --color-oren: #f1ac0b;
    --color-oren-dark: #d99800;
    --color-grey: #b3b3b3;
}

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@import './components/loading.css';
CSS;

        if (File::exists($appCssPath)) {
            $cssContent = File::get($appCssPath);

            $cssContent = str_replace('@import "tailwindcss";', '', $cssContent);
            $cssContent = str_replace("@import 'tailwindcss';", '', $cssContent);

            if (! str_contains($cssContent, '--color-hijau')) {
                File::put($appCssPath, $themeConfig."\n".trim($cssContent));
            }
        } else {
            File::put($appCssPath, $themeConfig);
        }
    }

    protected function updateSessionConfig()
    {
        $this->info('Updating config/session.php for Idle Timeout...');
        $sessionConfigPath = base_path('config/session.php');

        if (File::exists($sessionConfigPath)) {
            $content = File::get($sessionConfigPath);
            if (! str_contains($content, 'idle_timeout_enabled')) {
                $idleConfig = <<<'PHP'

    'idle_timeout_enabled' => env('IDLE_TIMEOUT_ENABLED', false),
    'idle_timeout_minutes' => env('IDLE_TIMEOUT_MINUTES', 5),

];
PHP;
                $content = preg_replace('/\];\s*$/', $idleConfig, $content);
                File::put($sessionConfigPath, $content);
            }
        }
    }

    protected function updateEnvironmentFile()
    {
        $this->info('Updating environment variables...');
        $envVars = <<<'ENV'

# Auto Logout (Idle Timeout)
IDLE_TIMEOUT_ENABLED=true
IDLE_TIMEOUT_MINUTES=5

# Google reCAPTCHA v2 (TEST KEY)
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe

# Activity Log
ACTIVITY_LOG_RETENTION_DAYS=7
ENV;

        foreach (['.env', '.env.example'] as $file) {
            $path = base_path($file);
            if (File::exists($path)) {
                $content = File::get($path);
                if (! str_contains($content, 'RECAPTCHA_SITE_KEY')) {
                    File::append($path, $envVars."\n");
                }
            }
        }
    }

    protected function updatePackageJson()
    {
        $this->info('Updating package.json dependencies...');
        $packageJsonPath = base_path('package.json');

        if (! File::exists($packageJsonPath)) {
            return;
        }

        $packages = json_decode(File::get($packageJsonPath), true);
        $newDependencies = [
            '@tailwindcss/vite' => '^4.0.0',
            'tailwindcss' => '^4.0.0',
            'axios' => '^1.7.4',
            'gsap' => '^3.15.0',
            'swiper' => '^12.1.4',
            'cropperjs' => '^1.6.2',
            '@fontsource/inter' => '^5.0.0',
        ];

        $packages['dependencies'] = array_merge($packages['dependencies'] ?? [], $newDependencies);
        ksort($packages['dependencies']);
        File::put($packageJsonPath, json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL);
    }

    protected function requireComposerPackages()
    {
        $this->info('Installing Security, Logs & Image Packages...');

        Process::forever()->run('composer require spatie/laravel-permission intervention/image spatie/laravel-activitylog livewire/livewire', function (string $type, string $output) {
            $this->output->write($output);
        });

        Process::run('php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        Process::run('php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"');
    }

    protected function updatePermissionConfig()
    {
        $this->info('Updating config/permission.php...');
        $configPath = base_path('config/permission.php');

        if (File::exists($configPath)) {
            $content = File::get($configPath);
            $content = str_replace(
                "'role' => Spatie\Permission\Models\Role::class,",
                "'role' => App\Models\Role::class,",
                $content
            );
            $content = str_replace(
                "'role' => \Spatie\Permission\Models\Role::class,",
                "'role' => App\Models\Role::class,",
                $content
            );
            $content = str_replace(
                "'role' => Role::class,",
                "'role' => App\Models\Role::class,",
                $content
            );
            File::put($configPath, $content);
        }
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
