<?php 


namespace Controllers;

use Router\Router;
use React\EventLoop\Factory;
use React\Http\Browser;
use Psr\Http\Message\ResponseInterface;
use React\Promise\Deferred;

class Bot{
      
    public static function start($message): void{
        Router::getInstance(token: BOT_TOKEN)->sendDatas('sendMessage', ['chat_id' => 1147877199, 'text' => "Ok Respuesta Json: <code>".json_encode($message)."</code>", 'parse_mode'=>'HTML']);
    }
    
    public static function hola($message): void {
        Router::getInstance(token: BOT_TOKEN)->sendDatas('sendMessage', ['chat_id' => 1147877199, 'text' => "Hola como estas!", 'reply_to_message_id' => $message->message_id]);
    }
}