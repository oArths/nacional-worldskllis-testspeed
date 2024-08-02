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
        //pegando o nome da tabela, json e da model
        $ModelsName = $this->argument('name');
        $TableName = $this->argument('table');
        $jsonName = $this->argument('json');

        //criando model com o nome que foi passado
        Artisan::call("make:model {$ModelsName}");
        //listando as colunas da tabela pra colocar na model
        $collums = Schema::getColumnListing($TableName);
        //junstando o array em uma string que deve ser 
        //separada com ',' pra não ter erro por falta de virgula/arpas 
        $fillable = implode("','", $collums);

        //pegando o caminho dos json
        $path = public_path("data\\{$jsonName}.json");
        //pegando o arquivo
        $content = file_get_contents($path);
        //codificando com true pra virar um array associativo
        $encode = json_decode($content, true);
        //inmseriando na tabela
        DB::table($TableName)->insert($encode);

        //pegando o caminho da model
        $pathModels = app_path("Models\\{$ModelsName}.php");
        //pegando o arquivo
        $ModelContent= file_get_contents($pathModels);
        //criando o fillable com os dados anteriores
        //NÃO ESQUECER DE ENVOLVER EM ASPAS '' E FINALIZAR COM PONTO E VIRGULA ;
        $fillableContent = "protected \$fillable = ['{$fillable}'];";
        //criando o nome da tabela
        $tableContent = "protected \$table = '$TableName';";
        //desativanbdo o timestamps
        $timestampsContent = "public \$timestamps = false;";
        //subistituindo com srt_replace o use HasFactory pelo novo conteudo
        $replace = str_replace('use HasFactory;', "use HasFactory; {$fillableContent} {$tableContent} {$timestampsContent}", $ModelContent);
        //guardando a models com o nome e os novos dados
        file_put_contents($pathModels, $replace);
        return Command::SUCCESS;
    }
}
