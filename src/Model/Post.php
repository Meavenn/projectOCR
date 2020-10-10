<?php

namespace App\Model;


use DateTime;

/**
 * Cette classe permet de représenter un post
 *
 * @author marion
 */
class Post extends AbstractModel {

    private
        $id,
        $title,
        $id_author,
        $pseudo_author,
        $content,
        $short_content,
        $date_display,
        $login_insert,
        $date_insert,
        $login_mod,
        $date_mod;

    /***** GETTERS *****/
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
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

    public function getShortContent() {
        return $this->short_content;
    }

    public function getDisplayDate() {
        return $this->date_display;
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

    /***** SETTER *****

     /**
     * @param int $id
     * @return int
     */
    public function setId(int $id) {
        if (isset($id)) {
            return $this->id =(int)htmlspecialchars($id);
        }
    }

    /**
     * @param int $idAuthor
     * @return string
     */
    public function setId_author(int $idAuthor) {
        if (isset($idAuthor)) {
            return $this->id_author = htmlspecialchars($idAuthor);
        }
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle(string $title): string {
        if (isset($title)) {
            return $this->title = htmlspecialchars($title);
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

    /**
     * @param mixed $shortContent
     * @param       $content
     * @return string
     */
    public function setContent_short(string $shortContent = NULL, $content = NULL) {
        if (!isset($content)) {
            $content = $this->getContent();
        }
        if (!isset($shortContent) || (strlen($shortContent) > 100)) {
            return $this->short_content = substr($this->setContent($content), 0, 100);
        }
        return $this->short_content = htmlspecialchars($shortContent);
    }

    /**
     * @param mixed $displayDate
     */
    public function setdate_display($displayDate) {
        if (isset($displayDate)) {
            return $this->date_display = $displayDate;
        } else {
            return $this->date_display = date('Y-m-d H:i:s');
        }
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
    public function setDate_insert() {
        if (!empty($this->getId())) {
            return $this->date_insert = $this->getDateInsert();
        } else {
            return new DateTime();
        }
    }

    /**
     *
     */
    public function setDate_mod() {
        return $this->date_mod = new DateTime();
    }
}