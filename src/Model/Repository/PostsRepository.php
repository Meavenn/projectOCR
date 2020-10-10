<?php

namespace App\Model\Repository;


use DateTime;
use DateTimeZone;

/**
 * Cette classe permet d'effectuer des reqêtes sur les articles en base de données
 *
 * @author marion
 */
class PostsRepository extends Db
{
    protected $table = 'post';

    /**
     * @param $id
     * @return bool
     */
    public function postExist($id)
    {

        return $this->exist($this->table, $id);
    }

    public function getPosts()
    {
        return $this->callDbRead([$this->table]);
    }

    public function getPost($id)
    {
        if (isset($id)) {
            return $this->callDbRead([$this->table, ['id' => $id]])[0];
        }
    }

    public function setDisplayed()
    {
        $posts = $this->getPosts();
        $displayedPosts = [];
        foreach ($posts as $post) {
            //on convertit le format en timestamp
            $date = (int)(new DateTime($post['date_display'], new DateTimeZone('Europe/Paris')))->format('U');
            // si la date est inférieure au timestamp actuel, on l'ajoute dans displayedPosts
            if ($date < time()) {
                $displayedPosts[] = $post['id'];
            }
        }
        // on transforme $displayedPosts en string
        if (!$displayedPosts) {
            return null;
        }
        $displayedPosts = implode(',', $displayedPosts);
        //on update le champ displayed_status de la table post

        $request = "UPDATE $this->table SET `displayed_status`= 1 WHERE id IN ($displayedPosts)";
        $req = $this->query($request);
        return $req->fetchAll();
    }

    public function getDisplayedPosts(bool $asc = true)
    {
        $this->setDisplayed();
        return $this->callDbRead([$this->table, ['displayed_status' => 1], 'date_display ' . ($asc ? 'asc' : 'desc')]);
    }

    public function createPost($values)
    {
        $this->callDbCreate([$this->table, $values]);
    }

    public function nbDisplayPost()
    {
        $this->setDisplayed();
        $request = "SELECT COUNT(*) FROM $this->table WHERE `displayed_status`= 1";
        $req = $this->query($request);
        return (int)$req->fetchAll()[0][0];
    }

    public function updatePost($idPost, $values)
    {
        $this->callDbUpdate([$this->table, $values, ['id' => $idPost]]);
    }

    public function deletePost($idPost)
    {
        $this->callDbDelete([$this->table, ['id' => $idPost]]);
    }


}