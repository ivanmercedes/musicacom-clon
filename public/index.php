<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


define('ROOT_FOLDER','..');

require_once ROOT_FOLDER.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Phroute\Phroute\RouteCollector;
use Dotenv\Dotenv;

// $dotenv = Dotenv::createImmutable(__DIR__); // para Heroku
// $dotenv = Dotenv::createImmutable(__DIR__.'/..'); // Para hosting
// $dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '66.225.201.54',//$_ENV['DB_HOST'],
    'database'  => 'fucezvqz_musicaclone',//$_ENV['DB_NAME'],
    'username'  => 'fucezvqz_musicaCloneAdmin', //$_ENV['DB_USER'],
    'password'  => 'dGbTv=%pzo3I',//$_ENV['DB_PASS'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$router = new RouteCollector();

$router->filter('auth', function (){
    \App\Helpers\Helpers::isLogin();
});


$router->controller('/', App\Controllers\IndexController::class);
$router->get('letras-de-canciones', [App\Controllers\IndexController::class,'getLetrasDeCnaciones']);
$router->get('nueva-canciones', [App\Controllers\IndexController::class,'getLetrasNuevas']);
$router->get('letra/{slug:[a-zA-Z0-9-]+}', [App\Controllers\LetraController::class,'getIndex']);
$router->post('letra/api', [App\Controllers\LetraController::class,'postApi']);
$router->post('letra/list', [App\Controllers\LetraController::class,'postList']);
$router->get('artista/{slug:[a-zA-Z0-9-]+}', [App\Controllers\ArtistaController::class,'getIndex']);
$router->get('por-letra/{slug:[a-zA-Z]+}', [App\Controllers\IndexController::class,'getPorletra']);

$router->get('buscar', [App\Controllers\IndexController::class,'getLetrasBuscar']);



$router->controller('/auth', App\Controllers\AuthController::class);

$router->group(['prefix' =>'admin','before'=>'auth'],function ($router){
    // Rutas Canciones
    $router->controller('/', App\Controllers\AdminController::class);
    $router->controller('/canciones', App\Controllers\Admin\CancionesController::class);
    $router->controller('/canciones/agregar', App\Controllers\Admin\AgregarController::class);
    $router->controller('/canciones/pendientes', App\Controllers\Admin\PendientesController::class);
    $router->get('/canciones/{id_lyric}/edit', [App\Controllers\Admin\EditarController::class,'getIndex']);
    $router->post('/canciones/{id_lyric}/edit', [App\Controllers\Admin\EditarController::class,'postIndex']);
    $router->get('/canciones/{id_lyric}/delete', ['App\Controllers\Admin\CancionesController','delete']);

    // Rutas Usuarios
    $router->controller('/usuarios', App\Controllers\Admin\UsersController::class);
    $router->get('/usuarios/{id_user}/edit', ['App\Controllers\Admin\UsersController','getEdit']);
    $router->post('/usuarios/{id_user}/edit', ['App\Controllers\Admin\UsersController','postEdit']);
    $router->get('/usuarios/{id_user}/delete', ['App\Controllers\Admin\UsersController','getDelete']);

    // Rutas Generos
    $router->controller('/generos', App\Controllers\Admin\GenerosController::class);

     // Rutas Albums
     $router->controller('/albums', App\Controllers\Admin\AlbumsController::class);
  
     // Rutas Artist
     $router->controller('/artistas', App\Controllers\Admin\ArtistController::class);

     $router->controller('/cuenta', App\Controllers\IndexController::class);

});
// var_dump($router);

# NB. You can cache the return value from $router->getData() so you don't have to create the routes each request - massive speed gains
$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
 
try {
    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
} catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
      header('location:'. '/');
     exit;
} catch (\Phroute\Phroute\Exception\HttpMethodNotAllowedException $e){
     header('location:'. '/');
    exit;
}


// Print out the value returned from the dispatched function
 echo $response;

// echo '<p><b>Consumo</b>: '.ceil(memory_get_usage() / 1024 ). ' Kb</p>'; 