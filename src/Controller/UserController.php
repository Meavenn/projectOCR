<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\UsersRepository;
use App\Model\User;
use Exception;

/**
 * Cette classe permet
 *
 * @author marion
 */
class UserController extends AbstractController
{
    private function getUserModel()
    {
        return new User(NULL);
    }

    private function getUsersRepository()
    {
        return new UsersRepository();
    }

    /**
     * @param $id
     * @return User
     */
    protected function setUser($id): User
    {
        return new User($this->getUsersRepository()->getUser($id));
    }

    public function createAccount()
    {
        $aTwig = $this->getATwig();
        return $this->twig()->render('/backend/newUserView.twig', $aTwig);
    }

    public function addUser()
    {
        //on récupère les données du formulaire
        $values = [];
        foreach ($this->request()->post as $column => $value) {
            $values[$column] = htmlspecialchars($value);
        }
        $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);

        $users = $this->getUsersRepository()->getUsers();

        // Si un compte existe déjà avec ce pseeudo, la création est bloquée et un message est affiché
        foreach ($users as $user) {
            if ($user['pseudo'] == $values['pseudo']) {
                $aTwig = $this->getATwig([
                    'alert' => "Le pseudo existe déjà. Veuillez en choisir un autre ou vous connecter."
                ]);
                return $this->twig()->render('/backend/newUserView.twig', $aTwig);
            }
        }
        // on vérifie que les données correspondent à la class User
        $this->getUserModel()->checkData($values);

        // on lance la requête d'insertion
        $this->getUsersRepository()->createUser($values);

        //on ajoute a posteriori l'id dun nouvel utilisateur dans le champ login_insert
        $this->getUsersRepository()->updateLoginInsertNewUser();

        header('Location: /connect/login');
    }

    public function connectInterface(User $user = NULL)
    {
        $aTwig = $this->getATwig([
            'user' => $user
        ]);
        return $this->twig()->render('/backend/loginView.twig', $aTwig);
    }

    public function newSession()
    {
        $values = [
            'pseudo' => $this->request()->post['pseudo'],
            'password' => $this->request()->post['password']
        ];

        // on vérifie que les données correspondent à la class User
        if (!$this->getUserModel()->checkData($values)) {
            header('Location: /connect/login');
        } else {
            // on récupère l'ID correspondant au pseudo et on instancie un User

            try {
                $idUser = $this->getUsersRepository()->getId("'" . $values['pseudo'] . "'");
            } catch (Exception $e) {
                header('Location: /connect/login');
            }
            $user = $this->setUser($idUser);

            // on compare le mot de passe saisi à celui en base de données
            $isPassword = password_verify($values['password'], $user->getPassword());
            if (!$isPassword) {
                $aTwig = $this->getATwig([
                    'alert' => 'La connexion a échoué.'
                ]);
                return $this->twig()->render('/backend/loginView.twig', $aTwig);

            } else {
                $_SESSION['pseudo'] = $user->getPseudo();
                $_SESSION['id'] = $user->getId();
                $_SESSION['status'] = $user->getStatus();

                header('Location: /');
            }
        }
    }

    public function disconnect()
    {
        session_destroy();
        header('Location: /');
    }

    public function getUser() // GET
    {
        if ($this->getIdConnect()) {
            $aTwig = $this->getATwig([
                'user' => $this->setUser($this->getIdConnect()),
                'users' => $this->getUsersRepository()->getUsers(),
                'comments' => (new CommentsRepository())->getCommentsAuthor($this->getIdConnect()),
                'posts' => (new PostsRepository())->getPosts()
            ]);
            return $this->twig()->render('/backend/accountManager.twig', $aTwig);


        } else {
            header('Location: /connect/login');
        }
    }

    public function updateUserRequest($data, User $user) // POST
    {
        $values = [];
        foreach ($data as $column => $value) {
            $values[$column] = htmlspecialchars($value);
        }
        if ($values['password'] === $user->getPassword()) {
            $values['password'] = $user->getPassword();
        } else {
            $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);
        }
        $values['date_mod'] = date('Y-m-d H:i:s');
        $values['login_mod'] = $this->getIdConnect();

        // on vérifie que les données correspondent à la class User
        $this->getUserModel()->checkData($values);

        // on lance la requête de modification
        $this->getUsersRepository()->updateUser($values, ['id' => $user->getId()]);

    }

    public function updateUser() // POST
    {
        $data = $this->request()->post;
        $user = $this->setUser($this->getIdConnect());
        $this->updateUserRequest($data, $user);
        return $this->getUser();

    }

    public function updateUserAdmin() // POST
    {
        $data = $this->request()->post;
        $user = $this->setUser($data['id']);
        $this->updateUserRequest($data, $user);
        header('Location: /user/' . $data['id']);
    }

    public function getUsersAdmin() // GET
    {
        if ($this->getIdConnect()) {
            if ($this->isAdmin()) {
                $aTwig = $this->getATwig([
                    'users' => $this->getUsersRepository()->getUsers()
                ]);

                return $this->twig()->render('/backend/admin/usersManager.twig', $aTwig);
            } else {
                if ($this->getIdConnect()) {
                    $this->getUser();
                } else {
                    header('Location: /connect/login');
                }
            }
        } else {
            header('Location: /connect/login');
        }
    }

    public function getUserSelectedAdmin()
    {
        header('Location: /user/' . $this->request()->post['userId']);
    }


    public function getUserAdmin($idUser)
    {
        if ($this->isAdmin()) {
            try {
                $User = new User ($this->getUsersRepository()->getUser($idUser));
            }
            catch (Exception $e){
                header('Location: /users');
            }
            if ($User) {
                $this->getUsersAdmin();

                $aTwig = $this->getATwig([
                    'comments' => (new CommentsRepository())->getCommentsAuthor($idUser),
                    'posts' => (new PostsRepository())->getPosts(),
                    'users' => $this->getUsersRepository()->getUsers(),
                    'user' => $User
                ]);

                return $this->twig()->render('/backend/admin/userUpdateManager.twig', $aTwig);

            } else {
                $this->getUsersAdmin();
            }
        } else {
            header('Location: /connect/login');
        }
    }

    public function updateCommentUserAdmin($idComment)
    {
        $Comment = new Comment((new CommentsRepository())->getComment($idComment));
        (new CommentController())->updateComment($idComment);

        header('Location: /user/' . $Comment->getIdAuthor());
    }

    public function updateCommentUser($idComment)
    {
        (new CommentController())->updateComment($idComment);

        header('Location: /profil');
    }
}