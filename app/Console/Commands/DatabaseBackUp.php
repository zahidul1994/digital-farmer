<?php

namespace App\Console\Commands;

use App\Models\Databackup;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:databasebackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'database backup command';

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
     * @return int
     */
    public function handle()
    {
        if (!is_dir(public_path() . "/storage/mysqlbackup")) {
            mkdir(public_path() . "/storage/mysqlbackup", 0777, true);
        }
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";

        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " .  env('DB_DATABASE') . " > " . public_path()."/storage/mysqlbackup/" . $filename;


        $returnVar = NULL;
        $output  = NULL;

        exec($command, $output, $returnVar);
        $filepath=public_path()."/storage/mysqlbackup/backup-". Carbon::now()->format('Y-m-d') . ".sql";
       $info=Databackup::wherebackup_date(date('Y-m-d'))->first();
       if($info){
        $info->file_size=round(filesize($filepath) / 1024 / 1024, 1);
        $info->save();
        $this->info('Database Backup Done');
       }else{
        $databackup=new Databackup();
        $databackup->backup_date=date('Y-m-d');
        $databackup->file_path=$filepath;
        $databackup->file_size=round(filesize($filepath) / 1024 / 1024, 1);
        $databackup->save();
         $this->info('Database Backup Done');
       }
       
    }
}
