<?php

// Clase de Bot Telegram / Intento de Async numero: 1
// Si no funciona busque que su servidor acepta flujos de Buffer y que tenga una alta capacidad de procesamiento

namespace Router;

use Exception;
use React\Http\Browser;
use React\Promise\Deferred;
use React\EventLoop\Factory;
use React\Stream\ReadableStream;
use Psr\Http\Message\ResponseInterface;
use React\Stream\ReadableStreamInterface;


class Router {
    private static $instance;
    private string $token;
    private array $commands = [];
    private string $loop;
    private array $charsets = [];
  
    /**
     * Recibe como parametro el Token de su Bot de Telegram
     *
     * @param string $token
     */
    private function __construct(string $token) {
      $this->token = $token;
      $this->loop = Factory::create();
    }
  
    /**
     * Crea una Instancia de la clase de usando la forma singler
     *
     * @param string $token
     * @return void
     */
    public static function getInstance(string $token): Router {
      if (!self::$instance) {
        self::$instance = new Router(token: $token);
      }
  
      return self::$instance;
    }
  
    /**
     * Recibe como parametro el Comando que quiere para su bot ya sea /start etc..
     * 
     * Uso $bot->addCommand('/start', [Bot::class, 'start']);
     *
     * @param string $command
     * @param callable $fn
     * @return void
     */
    public function addCommand(string $command, callable $fn): void {
      $this->commands[$command] = $fn;
    }
    
    /**
     * Corre de Forma semi asincrona el codigo aun se estan haciendo pruebas :(
     *
     * @return void
     */
    public function run(): void {
            $data = json_decode(json: file_get_contents(filename: 'php://input'));
                if ($data && isset($data->message)) {
                    $message = $data->message;
                    $text = $message->text;
                    $charsets = ['/', '$', '%', '=', '#', '!', '?', '*'];
                    foreach($charsets as $key){
                        if (strpos(haystack: $text, needle: $key) === 0) {
                            $command = explode(separator: ' ', string: $text)[0];
                            $fn = $this->commands[$command] ?? null;
                            if (isset($fn)) {
                                call_user_func(callback: $fn, args: $message);
                            }
                        } continue;
                    }
                }
            $this->loop->run();
    }

    public function sendDatas(string $tipo, array $datas): \React\Promise\PromiseInterface {
        $deferred = new Deferred();
    
        $url = "https://api.telegram.org/bot" . $this->token . "/" . $tipo;
        $client = new Browser();
        $client->post(
            url: $url,
            headers: array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            body: http_build_query(data: $datas)
        )->then(
            onFulfilled: function (ResponseInterface $response) use ($deferred): void {
                $deferred->resolve(value: (string)$response->getBody());
            },
            onRejected: function (Exception $e) use ($deferred): void {
                $deferred->reject(reason: $e);
            }
        );
    
        return $deferred->promise();
    }
}

