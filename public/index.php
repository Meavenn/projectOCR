<?php

session_start();

require_once '../vendor/autoload.php';
require_once '../src/router/Router.php';

use App\router\Router;
use App\Http\Request;

$request = new Request();
$router = new Router($request->server['REQUEST_URI']);

/****** FRONTEND ******/

$router->get('/', 'Home#goHome');
$router->post('/email', 'Home#sendEmail');

//Affichage des posts et des commentaires
$router->get('/posts', 'Post#getPosts');
$router->get('/post/:id', 'Post#getPost')->with('id', '[0-9]+');
$router->post('/post/:id', 'Comment#addComment')->with('id', '[0-9]+');



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
$router->post('/profil/comment/:id', 'User#updateCommentUser')->with('id', '[0-9]+');

// Admins only !

/* onglet 'Home' */
$router->get('/home', 'Home#getHomeAdmin');
$router->post('/home', 'Home#updateHomeAdmin');

/* onglet 'Les articles' */
$router->get('/postsAdmin', 'Post#getPostsAdmin');
$router->get('/newPost', 'Post#newPost');
$router->post('/postsAdmin', 'Post#createPost');
$router->get('/postAdmin/:id', 'Post#getPostAdmin')->with('id', '[0-9]+');
$router->post('/postSelected', 'Post#getPostSelectedAdmin')->with('id', '[0-9]+');
$router->post('/postAdmin/:id', 'Post#updatePostAdmin')->with('id', '[0-9]+');
$router->post('/postDelete/:id', 'Post#deletePost')->with('id', '[0-9]+');
$router->post('/postAdmin/comment/:id', 'Post#updateCommentPostAdmin')->with('id', '[0-9]+');

/* onglet 'Les commentaires' */
$router->get('/comments', 'Comment#getCommentsAdmin');
$router->post('/comments', 'Comment#getFilteredComments');
$router->post('/comments/comment/:id', 'Comment#updateCommentAdmin')->with('id', '[0-9]+');

/* onglet 'Les membres' */
$router->get('/users', 'User#getUsersAdmin');
$router->get('/user/:id', 'User#getUserAdmin')->with('id', '[0-9]+');
$router->post('/userSelected', 'User#getUserSelectedAdmin')->with('id', '[0-9]+');
$router->post('/user/:id', 'User#updateUserAdmin')->with('id', '[0-9]+');
$router->post('/user/comment/:id', 'User#updateCommentUserAdmin')->with('id', '[0-9]+');

echo $router->run();
