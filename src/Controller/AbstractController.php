<?php
namespace App\Controller;

use App\Config\TwigInitializer;
use App\Http\Request;


/**
 * Cette classe permet
 *
 * @author mario
 */
abstract class AbstractController extends TwigInitializer {

    public function __construct()
    {

    }

    public function request(){
        return new Request();
    }

    /**
     * @return int
     */
    public function getIdConnect(){
        if(isset($this->request()->session['id'])){
            return $this->request()->session['id'];
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPseudoConnect() :string{
        if(isset($this->idConnect)){
            return $this->request()->session['pseudo'];
        }
        return false;
    }

    public function isAdmin(){
        if($this->request()->session['status']!="member"){
            return true;
        }
        return false;
    }
}