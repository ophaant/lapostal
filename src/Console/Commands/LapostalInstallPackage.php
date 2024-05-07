<?php

namespace Ophaant\Lapostal\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class LapostalInstallPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lapostal:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Lapostal Package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('.____          __________               __         .__
|    |   _____ \______   \____  _______/  |______  |  |
|    |   \__  \ |     ___/  _ \/  ___/\   __\__  \ |  |
|    |___ / __ \|    |  (  <_> )___ \  |  |  / __ \|  |__
|_______ (____  /____|   \____/____  > |__| (____  /____/
        \/    \/                   \/            \/      ');
        $this->info('========================================================');
        $this->info('||             Laravel Seeder Postal Code             ||');
        $this->info('||                     by <fg=yellow>Ophaant</>🇮🇩                 ||');
        $this->info('========================================================');

        $this->outputComponents()->info('🚀 PROGRESSING...');


        if ($this->tableExists('provinces') and $this->tableExists('cities')
            and $this->tableExists('subdistricts') and $this->tableExists('villages')) {
            if ($this->shouldOverwriteTable()){
                $this->dropTable();
                $this->info('Dropped table');
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            }
            else {
                $this->info('Existing configuration was not overwritten');
            }
        }else {
            $this->publishConfiguration();
            $this->info('Published configuration');
        }

        $this->outputComponents()->info('🏁 FINISHED 🏁');
        $this->info('========================================================');
        $this->info('||                   🎉 Thank You 🎉                  ||');
        $this->info('========================================================');
    }

    private function tableExists($tableName)
    {
        return Schema::hasTable($tableName);
    }

    private function shouldOverwriteTable()
    {
        return $this->confirm(
            'Table file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function dropTable()
    {
        Schema::drop('provinces');
        Schema::drop('cities');
        Schema::drop('subdistricts');
        Schema::drop('villages');
    }
    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Ophaant\Lapostal\LapostalServiceProvider"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
        $this->call('migrate:refresh', ['--path' => 'database/migrations/lapostal']);
        $this->call('db:seed',['--class'=>'PostalCodeSeeder']);

    }
}
