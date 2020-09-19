<?php

namespace App\Model;


/**
 * Cette classe permet de représenter un post
 *
 * @author marion
 */
class Post extends AbstractModel {

    private
        $id,
        $title,
        $idAuthor,
        $pseudoAuthor,
        $content,
        $shortContent,
        $displayDate,
        $loginInsert,
        $dateInsert,
        $loginMod,
        $dateMod;

    /***** GETTERS *****/
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIdAuthor() {
        return $this->idAuthor;
    }

    public function getPseudoAuthor() {
        return $this->pseudoAuthor;
    }

    public function getContent() {
        return $this->content;
    }

    public function getShortContent() {
        return $this->shortContent;
    }

    public function getDisplayDate() {
        return $this->displayDate;
    }

    public function getLoginInsert() {
        return $this->loginInsert;
    }

    public function getDateInsert() {
        return $this->dateInsert;
    }

    public function getLoginMod() {
        return $this->loginMod;
    }

    public function getDateMod() {
        return $this->dateMod;
    }


    /***** SETTER ******/
    /**
     * @param mixed $idAuthor
     * @return Post
     */
    public function setIdAuthor(int $idAuthor) {
        if (isset($idAuthor)) {
            return $this->idAuthor = htmlspecialchars($idAuthor);
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
    public function setPseudoAuthor($pseudoAuthor = NULL) {
        if (is_string($pseudoAuthor)) {
            return $this->pseudoAuthor = $pseudoAuthor;
        } else {
            if (is_int($pseudoAuthor)) {
                return $this->pseudoAuthor = $this->getUser($pseudoAuthor)->getPseudo();
            } else {
                if (isset($this->idAuthor)) {
                    return $this->pseudoAuthor = $this->getUser($this->idAuthor)->getPseudo();
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
    public function setShortContent(string $shortContent = NULL, $content = NULL) {
        if (!isset($content)) {
            $content = $this->getContent();
        }
        if (!isset($shortContent) || (strlen($shortContent) > 100)) {
            return $this->shortContent = substr($this->setContent($content), 0, 100);
        }
        return $this->shortContent = htmlspecialchars($shortContent);
    }

    /**
     * @param mixed $displayDate
     */
    public function setDisplayDate($displayDate) {
        if (isset($displayDate)) {
            return $this->displayDate = $displayDate;
        } else {
            return $this->displayDate = date('Y-m-d H:i:s');
        }
    }

    /**
     * @param null $loginInsert
     * @return mixed
     */
    public function setLoginInsert($loginInsert = NULL) {
        if (isset($this->idConnect)) {
            return $this->loginInsert = $this->getPseudoConnect();
        } else {
            if (isset($loginInsert)) {
                return $this->loginInsert = htmlspecialchars($loginInsert);
            }
        }
    }

    public function setLoginMod($loginMod = NULL) {
        if (isset($this->idConnect)) {
            return $this->loginMod = $this->getPseudoConnect();
        } else {
            if (isset($loginMod)) {
                return $this->loginMod = htmlspecialchars($loginMod);
            }
        }
    }

    /**
     * @return false|string
     */
    public function setDateInsert() {
        if ($this->dateInsert) {
            return $this->dateInsert = $this->getDateInsert();
        } else {
            return new \DateTime();
        }
    }

    /**
     *
     */
    public function setDateMod() {
        return $this->dateMod = new \DateTime();
    }
}