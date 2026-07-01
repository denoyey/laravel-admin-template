<?php

namespace App\Services\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionGeneratorService
{
    /**
     * Get the default actions for any resource.
     */
    public static function getDefaultActions(): array
    {
        return [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];
    }

    /**
     * Models that should NOT have permissions generated for them.
     */
    protected static array $ignoreModels = [];

    /**
     * External models that should have permissions generated for them.
     */
    protected static array $externalModels = [
        'role' => 'App\Models\Role',
        'activity' => 'Spatie\Activitylog\Models\Activity',
    ];

    /**
     * Dynamically scan the app/Models directory to get resources.
     * The keys are the internal names, values are labels.
     */
    public static function getResources(): array
    {
        $resources = [];
        $modelsPath = app_path('Models');

        if (File::isDirectory($modelsPath)) {
            $files = File::allFiles($modelsPath);
            foreach ($files as $file) {
                $filename = $file->getFilenameWithoutExtension();
                $className = 'App\\Models\\'.$filename;

                if (class_exists($className) && is_subclass_of($className, Model::class)) {
                    if (in_array($className, self::$ignoreModels)) {
                        continue;
                    }

                    $resourceName = Str::snake($filename);
                    $humanReadable = ucwords(str_replace('_', ' ', $resourceName));
                    $resources[$resourceName] = $humanReadable." ($className)";
                }
            }
        }

        foreach (self::$externalModels as $resourceName => $className) {
            $humanReadable = ucwords(str_replace('_', ' ', $resourceName));
            $resources[$resourceName] = $humanReadable." ($className)";
        }

        ksort($resources);

        return $resources;
    }

    /**
     * Get all structured permissions grouped by resource.
     */
    public static function getGroupedPermissions(): array
    {
        $actions = self::getDefaultActions();
        $resources = self::getResources();

        $grouped = [];

        foreach ($resources as $resource => $label) {
            $permissions = [];
            foreach ($actions as $action) {
                $permissions[] = "{$action}_{$resource}";
            }
            $grouped[$resource] = [
                'label' => $label,
                'permissions' => $permissions,
            ];
        }

        return $grouped;
    }

    /**
     * Define the custom permissions here.
     */
    public static function getCustomPermissions(): array
    {
        return [
            'access_admin_panel' => 'Access Admin Panel',
            'manage_system_settings' => 'Manage System Settings',
        ];
    }

    /**
     * Sync all defined permissions to the database.
     * Run this command during seeder or via command line.
     */
    public static function syncToDatabase(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $grouped = self::getGroupedPermissions();
        foreach ($grouped as $resource => $data) {
            foreach ($data['permissions'] as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }
        }

        foreach (self::getCustomPermissions() as $name => $label) {
            Permission::firstOrCreate(['name' => $name]);
        }
    }
}
