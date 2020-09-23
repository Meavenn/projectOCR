<?php
namespace App\Controller;

use App\Http\Request;

/**
 * Cette classe permet
 *
 * @author mario
 */
abstract class AbstractController {

    public function request(){
        return new Request();
    }

    /**
     * @return int
     */
    public function getIdConnect() :int{
        if(isset($this->request()->session['id'])){
            return $this->request()->session['id'];
        }
    }

    /**
     * @return string
     */
    public function getPseudoConnect() :string{
        if(isset($this->idConnect)){
            return $this->request()->session['pseudo'];
        }
    }

    public function isAdmin(){
        if($this->request()->session['status']!="member"){
            return true;
        }
    }
}