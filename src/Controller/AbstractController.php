<?php
namespace App\Controller;

use App\Http\Request;

/**
 * Cette classe permet
 *
 * @author mario
 */
abstract class AbstractController {

    protected $request;

    public function request(){
        return new Request();
    }

    public function getIdConnect() :int{
        if(isset($this->request()->session['id'])){
            return $this->idConnect = $this->request()->session['id'];
        }
    }

    /**
     * @return string
     */
    public function getPseudoConnect() :string{
        if(isset($this->idConnect)){
            return $this->pseudoConnect = $this->request()->session['pseudo'];
        }
    }

    public function isAdmin(){
        if($this->request()->session['status']!="member"){
            $this->isAdmin = true;
        }
    }
}