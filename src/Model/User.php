<?php
namespace App\Model;

/**
 * Cette classe permet de représenter un utilisateur (membre, admin, superadmin)
 *
 * @author marion
 */
class User extends AbstractModel {
    private
        $id,
        $pseudo,
        $password,
        $status,
        $lname,
        $fname,
        $email,
        $loginInsert,
        $dateInsert,
        $loginMod,
        $dateMod;

    /***** GETTERS *****/
    public function getId() {
        return $this->id;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getLname() {
        return $this->lname;
    }

    public function getFname() {
        return $this->fname;
    }

    public function getEmail() {
        return $this->email;
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


    /***** SETTERS *****/

    /**
     * @param mixed $pseudo
     */
    public function setPseudo(string $pseudo) {
        if (isset($pseudo)) {
            return $this->pseudo = htmlspecialchars($pseudo);
        }
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword(string $password) {
        if (isset($password)) {
            return $this->password = $password;
        }
    }

    /**
     * @param mixed $status
     */
    public function setStatus(string $status) {
        if (isset($status) && ($status == UserStatus::member || $status == UserStatus::admin || $status == UserStatus::superadmin)) {
            return $this->status = htmlspecialchars($status);
        }
    }

    /**
     * @param mixed $lname
     */
    public function setLname(string $lname) {
        if (isset($lname)) {
            return $this->lname = htmlspecialchars($lname);
        }
    }

    /**
     * @param string $fname
     */
    public function setFname(string $fname) {
        if (isset($fname)) {
            return $this->fname = htmlspecialchars($fname);
        }
    }

    /**
     * @param string $email
     * @return string|void
     */
    public function setEmail(string $email = NULL) {
        if (!isset($email)) {
            return;
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->email = htmlspecialchars($email);
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
        if ($this->id) {
            return $this->dateInsert = $this->getDateInsert();
        } else {
            return date('Y-m-d H:i:s');
        }
    }

    /**
     *
     */
    public function setDateMod() {
        return $this->dateMod = date('Y-m-d H:i:s');
    }

    /**
     * @param int|null $id
     */
    public function setId(int $id) {
        if (isset($id) && (int)$id) {
            return $this->id = $id;
        }
    }
}