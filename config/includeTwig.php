<?php

use App\Model\Home;
use App\Model\Repository\HomeRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader, []);

if(isset($this->request()->session['id'])) {
    return $aTwig += $aTwig['session'] = $this->request()->session;
}
