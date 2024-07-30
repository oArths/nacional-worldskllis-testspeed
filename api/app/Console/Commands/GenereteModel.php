<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenereteModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:model {name} {table} {json} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ModelsName = $this->argument('name');
        $TableName = $this->argument('table');
        $jsonName = $this->argument('json');

        Artisan::call("make:model {$ModelsName}");
        $collums = Schema::getColumnListing($TableName);
        $fillable = implode("','", $collums);


        $path = public_path("data\\{$jsonName}.json");
        $content = file_get_contents($path);
        $encode = json_decode($content, true);
        DB::table($TableName)->insert($encode);

        $pathModels = app_path("Models\\{$ModelsName}.php");
        $ModelContent= file_get_contents($pathModels);
        $fillableContent = "protected \$fillable = ['{$fillable}'];";
        $tableContent = "protected \$table = '$TableName';";
        $timestampsContent = "public \$timestamps = false;";
        $replace = str_replace('use HasFactory;', "use HasFactory; {$fillableContent} {$tableContent} {$timestampsContent}", $ModelContent);
        file_put_contents($pathModels, $replace);
        return Command::SUCCESS;
    }
}
