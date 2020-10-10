<?php
namespace App\Model;

/**
 * Cette classe permet
 *
 * @author macbook <macbook@aouka.com>
 */
class Comment extends AbstractModel {
    /**
     * Cette classe permet de représenter un post
     *
     * @author marion
     */
    private
        $id,
        $id_post,
        $id_author,
        $pseudo_author,
        $content,
        $displayed_status,
        $login_insert,
        $date_insert,
        $login_mod,
        $date_mod;

    /***** GETTERS *****/
    public function getId() {
        return $this->id;
    }

    public function getIdPost() {
        return $this->id_post;
    }

    public function getIdAuthor() {
        return $this->id_author;
    }

    public function getPseudoAuthor() {
        return $this->pseudo_author;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDisplayedStatus() {
        return $this->displayed_status;
    }

    public function getLoginInsert() {
        return $this->login_insert;
    }

    public function getDateInsert() {
        return $this->date_insert;
    }

    public function getLoginMod() {
        return $this->login_mod;
    }

    public function getDateMod() {
        return $this->date_mod;
    }


    /***** SETTER ******/
    /**
     * @param mixed $idAuthor
     * @return Post
     */
    public function setId_author(int $idAuthor) {
        if (isset($idAuthor)) {
            return $this->id_author = htmlspecialchars($idAuthor);
        }
    }

    /**
     * @param string|null $pseudoAuthor
     */
    public function setPseudo_author($pseudoAuthor = NULL) {
        if (is_string($pseudoAuthor)) {
            return $this->pseudo_author = $pseudoAuthor;
        } else {
            if (is_int($pseudoAuthor)) {
                return $this->pseudo_author = $this->getUser($pseudoAuthor)->getPseudo();
            } else {
                if (isset($this->idAuthor)) {
                    return $this->pseudo_author = $this->getUser($this->idAuthor)->getPseudo();
                }
            }
        }
    }

    /**
     * @param mixed $content
     * @return string
     */
    public function setContent(string $content) {
        if (isset($content)) {
            return $this->content = htmlspecialchars($content);
        }
    }

    public function setDisplayed_status(string $displayedStatus) {
        //if ($displayedStatus == CommentStatus::granted || $displayedStatus == CommentStatus::pending || $displayedStatus == CommentStatus::rejected) {
        return $this->displayed_status = htmlspecialchars($displayedStatus);
        //}
    }

    /**
     * @param null $loginInsert
     * @return mixed
     */
    public function setLogin_insert($loginInsert = NULL) {
        $idConnect = $this->getIdConnect();
        if (isset($idConnect)) {
            return $this->login_insert = $this->getPseudoConnect();
        } else {
            if (isset($loginInsert)) {
                return $this->login_insert = htmlspecialchars($loginInsert);
            }
        }
    }

    /**
     * @param null $loginMod
     * @return string
     */
    public function setLogin_mod($loginMod = NULL) {
        $idConnect = $this->getIdConnect();
        if (isset($idConnect)) {
            return $this->login_mod = $this->getPseudoConnect();
        } else {
            if (isset($loginMod)) {
                return $this->login_mod = htmlspecialchars($loginMod);
            }
        }
    }

    /**
     * @return false|string
     */
    public function setDate_insert($dateInsert) {
        if ($dateInsert) {
            return $this->date_insert = $dateInsert;
        } else {
            return new \DateTime();
        }
    }

    /**
     *
     */
    public function setDate_mod() {
        return $this->date_mod = new \DateTime();
    }

    /**
     *
     */
    public function setId(int $id) {
        if($id>0){
            return $this->id = $id;
        }
    }

    /**
     *
     */
    public function setId_post(int $id) {
        if($id>0){
            return $this->id_post = $id;
        }
    }
}