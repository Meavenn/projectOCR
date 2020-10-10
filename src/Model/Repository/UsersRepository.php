<?php

namespace App\Model\Repository;

use Exception;

/**
 * Cette classe permet de récupérer en base de données  les informations liées à un utilisateur
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
            }
                return 'Cet utilisateur n\'existe pas.';
        }
    }

    /**
     * @param string $pseudo
     * @return mixed|string
     */
    public function getId(string $pseudo) {
        if (isset($pseudo)) {
            return $this->callDbRead([$this->table, ['pseudo' => $pseudo]])[0]['id'];
        }
    }

    public function getUsers() {
        return $this->callDbRead([$this->table]);
    }

    public function getUser($id) {
        if(isset($this->callDbRead([$this->table, ['id' => $id]])[0])){
            return $this->callDbRead([$this->table, ['id' => $id]])[0];
        }
        return [];

    }

    public function createUser(array $values) {
        try {
            $this->callDbCreate([$this->table, $values]);
        } catch (Exception $e) {
            header('Location: /connect/new');
        }
    }

    public function updateUser(array $values, array $condition) {
        try {
            $this->callDbUpdate([$this->table, $values, $condition]);
        } catch (Exception $e) {
            echo ('Error : ' . $e->getMessage());
        }
    }

    public function getLastUserId(){
        $aUsers = $this->callDbRead([$this->table]);
        $idUsers = [];
        foreach ($aUsers as $aUser){
            $idUsers[] = (int)$aUser['id'];
        }
        return max($idUsers);
    }

    public function updateLoginInsertNewUser(){
        $lastId = $this->getLastUserId();
            $this->callDbUpdate([$this->table, ['login_insert' => $lastId], ['id' => $lastId]]);
    }
}



