<?php

namespace App\Model\Repository;

/**
 * Cette classe permet de récupérer les informations en base de données à un utilisateur
 *
 * @author marion
 */
class UsersRepository extends Db {
    private $table = 'user';

    /**
     * @param int $id
     * @return mixed|string
     */
    public function getPseudo(int $id) {
        if (isset($id) && $id > 0) {
            if ($this->callDbCount([$this->table, ['id' => $id]])[0] = 1) {
                return $this->callDbRead([$this->table, ['id' => $id]])[0]['pseudo'];
            } else {
                return 'Cet utilisateur n\'existe pas.';
            }
        }
    }

    /**
     * @param string $pseudo
     * @return mixed|string
     *                     TODO : revoir pourquoi callDbCount ne fonctionne pas
     */
    public function getId(string $pseudo) {
        if (isset($pseudo)) {
            //            if ($this->callDbCount([$this->table, ['pseudo' => $pseudo]])[0] > 0) {
            return $this->callDbRead([$this->table, ['pseudo' => $pseudo]])[0]['id'];
            //            } else {
            //                return 'Cet utilisateur n\'existe pas.';
            //            }
        }
    }

    public function getUsers() {
        return $this->callDbRead([$this->table]);
    }

    public function getUser($id) {
        //$user= $this->callDbRead([$this->table, ['id' => $id]])[0];
        if(isset($this->callDbRead([$this->table, ['id' => $id]])[0])){
            return $this->callDbRead([$this->table, ['id' => $id]])[0];
        }

    }

    public function createUser(array $values) {
        try {
            $this->callDbCreate([$this->table, $values]);
        } catch (\Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }

    public function updateUser(array $values, array $condition) {
        try {
            $this->callDbUpdate([$this->table, $values, $condition]);
        } catch (\Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }
}



