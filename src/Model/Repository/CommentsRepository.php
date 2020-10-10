<?php
namespace App\Model\Repository;


/**
 * Cette classe permet d'effectuer des reqêtes sur les commentaires en base de données
 *
 * @author marion
 */
class CommentsRepository extends Db {
    protected $table = 'comment';

    public function createComment($values) {
        $this->callDbCreate([$this->table, $values]);
    }

    public function getComment($id) {
        return $this->callDbRead([$this->table, ['id' => $id]])[0];
    }

    public function getComments() {
        return $this->callDbRead([$this->table]);
    }

    public function getCommentsStatus($status) {
        return $this->callDbRead([$this->table, ['disaplayed_status'=>$status]]);
    }

    public function getCommentsAuthor($idAuthor) {
        return $this->callDbRead([$this->table, ['id_author'=>$idAuthor]]);
    }

    public function getCommentsPost($idPost) {
        return $this->callDbRead([$this->table, ['id_post' => $idPost]]);
    }

    public function updateComment($values, $id) {
        $this->callDbUpdate([$this->table, $values, ['id'=>$id]]);
    }

    public function getCondition($condition) {
        return $this->callDbRead([$this->table, $condition]);
    }
}