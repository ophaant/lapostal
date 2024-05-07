<?php

namespace Ophaant\Lapostal;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Ophaant\Lapostal\Console\Commands\LapostalInstallPackage;

class LapostalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $migrationsData = $this->getMigrationsData()->get();

            if (empty(count($migrationsData))) {
                $this->publishResources();
            } else {
                //check migration file exist
                if ($this->checkConfigExist('migrations/lapostal/')) {
                    File::deleteDirectory(database_path('migrations/lapostal'));
                }

                //check seeder file exist
                if ($this->checkConfigExist('seeders/PostalCodeSeeder.php')) {
                    File::delete(database_path('seeders/PostalCodeSeeder.php'));
                }
                $this->getMigrationsData()->delete();
                $this->publishResources();
            }
            $this->commands([
                LapostalInstallPackage::class,
            ]);
        }
    }

    private
    function getMigrationsData()
    {
        $tables = ['%create_provinces_table%', '%create_cities_table%', '%create_subdistricts_table%', '%create_villages_table%'];
        $db = DB::table('migrations')->where(function ($query) use ($tables) {
            foreach ($tables as $table) {
                $query->orWhere('migration', 'like', $table);
            }

        });

        return $db;
    }

    protected
    function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_provinces_table.php.stub' => database_path('migrations/lapostal/' . date('Y_m_d_His', time()) . '_create_provinces_table.php'),
            __DIR__ . '/../database/migrations/create_cities_table.php.stub' => database_path('migrations/lapostal/' . date('Y_m_d_His', time()) . '_create_cities_table.php'),
            __DIR__ . '/../database/migrations/create_subdistricts_table.php.stub' => database_path('migrations/lapostal/' . date('Y_m_d_His', time()) . '_create_subdistricts_table.php'),
            __DIR__ . '/../database/migrations/create_villages_table.php.stub' => database_path('migrations/lapostal/' . date('Y_m_d_His', time()) . '_create_villages_table.php'),
        ], 'lapostal-migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders/' => database_path('seeders'),
        ], 'lapostal-seeders');
    }

    /**
     * @return void
     */
    public
    function checkConfigExist($path): bool
    {
        return File::exists(database_path($path));
    }

    public
    function register()
    {
        $this->app->bind('lapostal', function () {
            return new Lapostal();
        });
    }
}

