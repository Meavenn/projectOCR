<?php
namespace App\Model;

/**
 * Cette classe permet d'instancier un objet Home
 *
 * @author marion
 */
class Home extends AbstractModel {
    private
        $id,
        $title,
        $logo,
        $tagline,
        $photo,
        $presentation,
        $email;

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getTagline() {
        return $this->tagline;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getPresentation() {
        return $this->presentation;
    }

    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $id
     * @return Home
     */
    public function setId(int $id) {
        if (isset($id) && (int)$id = 1) {
            return $this->id = $id;
        }
    }

    /**
     * @param mixed $title
     * @return Home
     */
    public function setTitle(string $title) {
        if (isset($title)) {
            return $this->title = $title;
        }
    }

    /**
     * @param mixed $logo
     * @return Home
     */
    public function setLogo(string $logo) {
        if (isset($logo)) {
            return $this->logo = $logo;
        }
    }

    /**
     * @param mixed $tagline
     * @return Home
     */
    public function setTagline(string $tagline) {
        if (isset($tagline)) {
            return $this->tagline = $tagline;
        }
    }

    /**
     * @param mixed $photo
     * @return Home
     */
    public function setPhoto(string $photo) {
        if (isset($photo)) {
            return $this->photo = $photo;
        }
    }

    /**
     * @param mixed $presentation
     * @return Home
     */
    public function setPresentation(string $presentation) {
        if (isset($presentation)) {
            return $this->presentation = $presentation;
        }
    }

    /**
     * @param mixed $email
     * @return Home
     */
    public function setEmail(string $email) {
        if (!isset($email)) {
            return;
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->email = htmlspecialchars($email);
        }
    }
}