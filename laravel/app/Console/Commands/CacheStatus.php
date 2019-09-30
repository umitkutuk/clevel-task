<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:status {statu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            if($this->argument('statu') == 'true' || $this->argument('statu') == 'false'){

                if ($this->confirm('Do you wish to continue? [yes|no]')) {
                    $path = base_path('.env');
                    $cache_is_active = $this->laravel['config']['app.cache_is_active'] ? 'true' : 'false';
                    $cache_driver = $this->laravel['config']['cache.default'];
                    $cache_driver_replace = $this->argument('statu') == 'true' ? 'redis' : 'file';
                    if (file_exists($path)) {
                        file_put_contents($path, str_replace(
                            'CACHE_IS_ACTIVE='.$cache_is_active,
                            'CACHE_IS_ACTIVE='.$this->argument('statu'),
                            file_get_contents($path)
                        ));

                        file_put_contents($path, str_replace(
                            'CACHE_DRIVER='.$cache_driver,
                            'CACHE_DRIVER='.$cache_driver_replace,
                            file_get_contents($path)
                        ));
                    }
                    $this->info('Successfully');
                }else{
                    $this->info('Cancelled');
                }

            }else{
                $this->error('Command "'.$this->argument('statu').'" is not defined.');

            }
        } catch (Exception $e) {
            $this->error('An unexpected error occurred! Error:'. $e);
        }
    }
}
