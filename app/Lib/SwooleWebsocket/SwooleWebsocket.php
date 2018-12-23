<?php
namespace App\Lib\SwooleWebsocket;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use swoole_websocket_server;
/**
 * Created by PhpStorm.
 * User: 95969
 * Date: 2018/4/22
 * Time: 10:44
 */

class SwooleWebsocket{

    public $server;

    public function __construct() {

    }

    public function start(){
        $this->server = new swoole_websocket_server("0.0.0.0", 9501);
        $this->server->on('open', [$this,'onOpen']);
        $this->server->on('message', [$this,'onMessage']);
        $this->server->on('close', [$this,'onClose']);
        $this->server->on('request', [$this,'onReqeust']);
        $this->server->start();
    }


    public function onOpen(swoole_websocket_server $server,$request){
        $server->push($request->fd,"welcome $request->fd \n");
        $server->push($request->fd,$request->get['group']."man \n");
        //dump($request->get);
        $minutes=Carbon::now()->addMinutes(10);
        $arr=Cache::remember('connect_arr',$minutes,function(){
            return [];
        });

        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                if ($v != $request->fd) {
                    $server->push($v, "hello new body");
                }
            }
        }
        $fid=$request->fd;
        if(!in_array($fid,$arr)){
            $arr[]=$fid;
            Cache::put('connect_arr', $arr, $minutes);
        }

       /* foreach($server->connections as $fd){
            if($fd!=$request->fd){
                $server->push($fd, "hello new body");
            }
        }*/
    }

    public function onMessage(swoole_websocket_server $server,$request){
        $server->push($request->fd,"get message \n");
    }

    public function onClose(swoole_websocket_server $server,$fd){
        echo "client {$fd} closed\n";
        $minutes=Carbon::now()->addMinutes(10);
        $arr=Cache::remember('connect_arr',$minutes,function(){
            return [];
        });

        if(in_array($fd,$arr)){
            $k=array_search($fd,$arr);
            unset($arr[$k]);
            Cache::put('connect_arr', $arr, $minutes);
        }

        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                if ($v != $fd) {
                    $server->push($v, "some body close");
                }
            }
        }
    }

    public function onReqeust($request,$response){
        foreach ($this->server->connections as $fd) {
            $this->server->push($fd, $request->get['message']);
        }
    }
}