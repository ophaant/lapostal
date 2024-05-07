<?php

namespace Ophaant\Lapostal\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function getEnvironmentSetUp($app)
    {
        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../database/migrations/create_provinces_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_cities_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_subdistricts_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_villages_table.php.stub';

        // run the up() method of that migration class
        (new \CreateProvincesTable)->up();
        (new \CreateCitiesTable)->up();
        (new \CreateSubdistrictsTable)->up();
        (new \CreateVillagesTable)->up();
    }
}
