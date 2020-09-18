<?php

namespace App\Model;

use App\Http\Request;
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
        if (isset($data)){
            $this->hydrate($data);
        }
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

    /**
     * @return int
     */
    public function getIdConnect(): int {
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
            $this->isAdmin = true;
        }
    }

    /**
     * @param $id
     * @return User
     */
    public function getUser(int $id) {
       $data = (new UsersRepository())->callDbRead(['user', ['id' => $id]])[0];
       return new User($data);
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
}