<?php

namespace App\Config;

use App\Http\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigInitializer
{
    protected $aTwig=[];
    protected $twig;


    public function getATwig(array $aValues = null)
    {
        $this->aTwig = [];
        $session= (new Request())->session;
        if (isset($session['id'])) {
            $this->aTwig['session'] = $session;
        }

        if(isset($aValues)){
            foreach ($aValues as $k => $value) {
                $this->aTwig[$k] = $value;
            }
        }
        return $this->aTwig;

    }

    public function twig()
    {
        $this->getATwig();
        $loader = new FilesystemLoader('../templates');
        return $this->twig = new Environment($loader, []);
        }

}