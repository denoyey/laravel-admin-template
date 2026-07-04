<?php

namespace Denoyey\AdminTemplate\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    protected $signature = 'denoyey:install';

    protected $description = 'Install the Laravel Admin Template and Auth Starter Kit';

    protected $protectedFiles = [
        'resources/js/admin/features/image-protect.js' => [
            'dir' => 'resources/js',
            'ext' => 'js',
            'signatures' => ['contextmenu', 'draggable', 'preventDefault'],
            'message' => 'image protection logic',
        ],
        'app/Http/Middleware/PreventSpamSubmit.php' => [
            'dir' => 'app/Http/Middleware',
            'ext' => 'php',
            'signatures' => ['spam_lock_', 'COOLDOWN_SECONDS', 'Cache::has'],
            'message' => 'Spam/Rate-limiting protection middleware',
        ],
        'app/Http/Middleware/IdleTimeoutMiddleware.php' => [
            'dir' => 'app/Http/Middleware',
            'ext' => 'php',
            'signatures' => ['idle_timeout_enabled', 'last_activity_time'],
            'message' => 'Idle Timeout middleware',
        ],
        'resources/js/admin/features/global-search.js' => [
            'dir' => 'resources/js',
            'ext' => 'js',
            'signatures' => ['Ctrl', 'KeyK', 'fetch'],
            'message' => 'Global Search (Ctrl+K) logic',
        ],
    ];

    protected $selectedFont = 'arial';

    public function handle()
    {
        $this->info("\n" . '🚀 Installing Laravel Admin Template by denoyey...');

        $this->promptForFont();

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

        $this->info("\n" . '✅ Laravel Admin Template installed successfully!');
        $this->info('🎉 Anda siap menggunakannya! Silakan jalankan:');
        $this->warn('php artisan migrate');
        $this->warn('php artisan db:seed --class=UserSeeder');
    }

    protected function promptForFont()
    {
        $this->info("\n" . '🎨 Konfigurasi Font Dashboard (Tailwind CSS v4)');
        $this->info('Silakan lihat daftar font yang tersedia di: https://fontsource.org/fonts');
        $this->info('Masukkan nama package font dalam format kebab-case. Contoh: inter, roboto, open-sans, poppins.');
        
        $fontInput = $this->ask('Kosongkan atau ketik "arial" untuk menggunakan font Arial (bawaan sistem)', 'arial');
        $fontInput = strtolower(trim($fontInput));
        
        $this->selectedFont = empty($fontInput) ? 'arial' : $fontInput;
        
        if ($this->selectedFont !== 'arial') {
            $this->info("✨ Font yang dipilih: " . ucwords(str_replace('-', ' ', $this->selectedFont)) . " (@fontsource/{$this->selectedFont})\n");
        } else {
            $this->info("✨ Menggunakan font bawaan sistem: Arial\n");
        }
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
                    $this->copyDirectorySafe($source, $to, $from);
                } else {
                    $this->copyFileSafe($source, $to, $from);
                }
            }
        }

        if (File::exists(__DIR__.'/../../stubs/resources/js/admin.js')) {
            $this->copyFileSafe(__DIR__.'/../../stubs/resources/js/admin.js', resource_path('js/admin.js'), 'resources/js/admin.js');
        }
    }

    protected function isProtectedFile($stubRelativePath, $targetPath)
    {
        if (! array_key_exists($stubRelativePath, $this->protectedFiles)) {
            return false;
        }

        if (File::exists($targetPath)) {
            return true;
        }

        $rule = $this->protectedFiles[$stubRelativePath];
        
        if (is_array($rule)) {
            return $this->scanForSignature($rule['dir'], $rule['ext'], $rule['signatures'], $rule['message']);
        }

        if (is_string($rule) && method_exists($this, $rule)) {
            return $this->$rule($targetPath);
        }

        return false;
    }

    protected function scanForSignature($dir, $ext, $signatures, $message)
    {
        $searchDir = base_path($dir);
        
        if (! File::isDirectory($searchDir)) {
            return false;
        }

        foreach (File::allFiles($searchDir) as $file) {
            if ($file->getExtension() !== $ext) continue;
            
            $content = file_get_contents($file->getPathname());
            $hasAllSignatures = true;
            
            foreach ($signatures as $sig) {
                if (! str_contains($content, $sig)) {
                    $hasAllSignatures = false;
                    break;
                }
            }

            if ($hasAllSignatures) {
                $this->warn("Found existing {$message} in: " . $file->getRelativePathname());
                return true;
            }
        }

        return false;
    }

    protected function copyDirectorySafe($source, $to, $baseFrom)
    {
        File::ensureDirectoryExists($to);

        foreach (File::allFiles($source) as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath = $to . '/' . $relativePath;
            $stubRelativePath = $baseFrom . '/' . $relativePath;

            if ($this->isProtectedFile($stubRelativePath, $targetPath)) {
                $this->warn("Skipping protected file: {$stubRelativePath}");
                continue;
            }

            File::ensureDirectoryExists(dirname($targetPath));
            File::copy($file->getPathname(), $targetPath);
        }
    }

    protected function copyFileSafe($source, $to, $stubRelativePath)
    {
        if ($this->isProtectedFile($stubRelativePath, $to)) {
            $this->warn("Skipping protected file: {$stubRelativePath}");
            return;
        }

        File::ensureDirectoryExists(dirname($to));
        File::copy($source, $to);
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

        $fontFamily = ucwords(str_replace('-', ' ', $this->selectedFont));
        
        if ($this->selectedFont !== 'arial') {
            $fontImports = <<<CSS
@import '@fontsource/{$this->selectedFont}/400.css';
@import '@fontsource/{$this->selectedFont}/500.css';
@import '@fontsource/{$this->selectedFont}/600.css';
@import '@fontsource/{$this->selectedFont}/700.css';
CSS;
            $fontTheme = "--font-sans: '{$fontFamily}', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';";
        } else {
            $fontImports = "";
            $fontTheme = "--font-sans: Arial, Helvetica, ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';";
        }

        $themeConfig = <<<CSS
@import 'tailwindcss';

{$fontImports}

@theme {
    {$fontTheme}

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
            $cssContent = preg_replace('/@theme\s*\{[^}]*\}/s', '', $cssContent);

            if (! str_contains($cssContent, '--color-hijau')) {
                File::put($appCssPath, $themeConfig."\n\n".trim($cssContent));
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
        ];

        if ($this->selectedFont !== 'arial') {
            $newDependencies["@fontsource/{$this->selectedFont}"] = '^5.0.0';
        }

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
