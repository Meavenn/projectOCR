<?php

session_start();

require_once '../vendor/autoload.php';
require_once '../src/router/Router.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, []);

use App\router\Router;
use App\Http\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$request = new Request();
$router = new Router($request->server['REQUEST_URI']);

$router->get('/', 'Home#goHome');

//Affichage des posts et des commentaires
$router->get('/posts', 'Post#getPosts');



/****** BACKEND ******/
// Création d'un compte
$router->get('/connect/new', 'User#createAccount');
$router->post('/connect/created', 'User#addUser');

// Gestion des sessions
$router->get('/connect/login', 'User#connectInterface');
$router->post('/connect', 'User#newSession');
$router->get('/disconnect', 'User#disconnect');

// Gestion des profils
$router->get('/profil', 'User#getUser');
$router->post('/profil', 'User#updateUser');

// Admins only !
/* onglet 'Les articles' */
$router->get('/postsAdmin', 'Post#getPostsAdmin');
$router->get('/newPost', 'Post#newPost');
$router->post('/postsAdmin', 'Post#createPost');

$router->run();
