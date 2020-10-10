<?php

namespace App\Model;

use App\Http\Request;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\UsersRepository;

/**
 * Cette classe permet de créer des modèles d'objet
 *
 * @author marion
 */
abstract class AbstractModel {
    /**
     * @var Request
     */
    protected $request;
    protected $idConnect;
    protected $isAdmin = false;
    protected $pseudoConnect;
    protected $users;

    public function __construct(array $data = null) {
        $this->request = new Request();
//        if(empty($data)){
//            throw new \Exception('Cette page n\'existe pas');
//        }
        if (isset($data)){
            $this->hydrate($data);
        }
    }
protected function request(){
        return new Request();
}

    /**
     * @param array $data
     */
    public function hydrate(array $data) {
        if(!$data){
            return;
        }

        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }


    public function getIdConnect() {
        if (isset($this->request()->session['id'])) {
            return $this->idConnect = $this->request()->session['id'];
        }
    }

    /**
     * @return string
     */
    public function getPseudoConnect(): string {
        if (isset($this->idConnect)) {
            return $this->pseudoConnect = $this->request()->session['pseudo'];
        }
    }

    public function isAdmin() {
        if ($this->request()->session['status'] != "member") {
            return $this->isAdmin = true;
        }
    }


    public function checkData($values) {
        foreach ($values as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $values[$key] = $this->$setter($value);
            }
        }
        return $values;
    }

    // TODO : est-ce à placer dans un  AbstractModel ?
    /**
     * @param $id
     * @return User
     */
    public function getUser(int $id) {
       $data = (new UsersRepository())->callDbRead(['user', ['id' => $id]])[0];
       return new User($data);
    }

    /**
     * @param int $id
     * @return Post
     */
    public function getPost(int $id) {
        $data = (new PostsRepository())->callDbRead(['post', ['id' => $id]])[0];
        return new Post($data);
    }
}