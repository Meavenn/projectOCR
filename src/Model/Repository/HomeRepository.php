<?php

namespace App\Model\Repository;

/**
 * Cette classe permet de récupérer les informations en base de données à un utilisateur
 *
 * @author marion
 */
class HomeRepository extends Db {
    private $table = 'home';


    public function getHomeData() {
        return $this->callDbRead([$this->table])[0];
    }


    public function updateHomeData(array $values) {
        try {
            $this->callDbUpdate([$this->table, $values, ['id'=>1]]);
        } catch (\Exception $e) {
            header('Location: /home');
        }
    }
}

