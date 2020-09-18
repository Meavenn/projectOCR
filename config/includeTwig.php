<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader, [

]);