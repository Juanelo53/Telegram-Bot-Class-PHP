<?php 

// Clase de Bot Telegram / Intento de Async numero: 1
// Si no funciona busque que su servidor acepta flujos de Buffer y que tenga una alta capacidad de procesamiento

require __DIR__."/../includes/app.php";

use Router\Router;
use Controllers\Bot;

// Instancia el Token : Revise el archivo app.php en includes
$marin = Router::getInstance(token: BOT_TOKEN);

// Comandos aqui
$marin->addCommand('/start', [Bot::class, 'start']);
$marin->addCommand('/hola', [Bot::class, 'hola']);

// Verifica el comando y manda a llamar ala clase correspondiente
$marin->run();