<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SaveVoyager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "it's mean to save voyager progress";

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
        $tables= [
            'data_types',
            'data_rows',
            'menus',
            'menu_items',
            // 'roles', // we don't need to save roles, since it is only contain admin and users; we don't need it 
            'permissions',
            'permission_role',
            'settings',
        ];


        $tableList = implode(',', $tables);
        $this->comment($tableList . ' being saved!');

        $bar = $this->output->createProgressBar(count($tables));
        
        foreach ($tables as $key => $table) {
            # code...
            Artisan::call('iseed',[
                'tables'  => $table,
                '--force' => true
            ]);

            $bar->advance();
        }

        $bar->finish();


    }
}
