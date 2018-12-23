<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\SwooleWebsocket\SwooleWebsocket;
class LaravelSwooleWebsocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:websocket {action?} {--mark=true}';

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
        //
        $action=$this->argument('action');
        $mark = $this->option('mark');

        switch($action){
            case 'start':
                $this->showInfo('start swoole websocket');
                $this->startWebsocket();
                break;
            case 'stop':
                break;
            case 'restart':
                break;
            default:
                $this->info('no action');
        }
    }

    public function startWebsocket(){

        $a=new SwooleWebsocket();
        $a->start();
    }


    /**
     * @param $string
     */
    public function showInfo($string){

        if(preg_match("/cli/i", PHP_SAPI)){
            $this->info($string);
        }else{
            echo $string.'\n';
        }

    }
}
