<?php
namespace App\Controller;

use App\Model\Repository\UsersRepository;
use App\Model\User;

/**
 * Cette classe permet
 *
 * @author marion
 */
class UserController extends AbstractController {
    private function getUserModel() {
        return new User(NULL);
    }

    private function getUsersRepository() {
        return new UsersRepository();
    }

    /**
     * @param $id
     * @return User
     */
    protected function setUser($id) :User{
        return new User($this->getUsersRepository()->getUser($id));
    }

    public function createAccount() {
        include '../config/includeTwig.php';
        echo $twig->render('/backend/newUserView.twig', [
            'session' => $this->request()->session
        ]);
    }

    public function addUser() {
        //on récupère les données du formulaire
        $values = [];
        foreach ($this->request()->post as $column => $value) {
            $values[$column] = htmlspecialchars($value);
        }
        $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);

        $users = $this->getUsersRepository()->getUsers();

        // if there is already an account with this pseudo, it can't be created
        foreach ($users as $user) {
            if ($user['pseudo'] == $values['pseudo']) {

                include '../config/includeTwig.php';
                echo $twig->render('/backend/newUserView.twig', [
                    'alert'     =>"Le pseudo existe déjà. Veuillez en choisir un autre ou vous connecter.",
                    'session'   => $this->request()->session
                ]);
                die();
            }
        }
        // on vérifie que les données correspondent à la class User
        $this->getUserModel()->checkData($values);

        // on lance la requête d'insertion
        $this->getUsersRepository()->createUser($values);

        header('Location: /connect/login');
    }

    public function connectInterface(User $user = NULL) {
        include '../config/includeTwig.php';
        echo $twig->render('/backend/loginView.twig', [
            'user'    => $user,
            'session' => $this->request()->session
        ]);
    }

    public function newSession() {

        $values =[
            'pseudo'     => $this->request()->post['pseudo'],
            'password'   => $this->request()->post['password']
        ];

        // on vérifie que les données correspondent à la class User
        if(!$this->getUserModel()->checkData($values)){
            echo 'La connexion a échoué.';
        }else{
            // on récupère l'ID correspondant au pseudo et on instancie un User

            try{
                $idUser = $this->getUsersRepository()->getId("'".$values['pseudo']."'");
                $user = $this->setUser($idUser);
            }
            catch (\Exception $e)
            {
                die('Error : ' . $e->getMessage());
            }
            $user = $this->setUser($idUser);

            // on compare le mot de passe saisi à celui en base de données
            $isPassword = password_verify($values['password'], $user->getPassword());
            if(!$isPassword)
            {
                echo 'Le mot de passe est incorrect.';
            }else{

                $_SESSION['pseudo']     = $user->getPseudo();
                $_SESSION['id']         = $user->getId();
                $_SESSION['status']     = $user->getStatus();

                header('Location: /');
           }
        }
    }

    public function disconnect(){
        session_destroy();
        header('Location: /');
    }

    public function getUser() // GET
    {
        include '../config/includeTwig.php';
        $idUser = $this->getIdConnect();
        if ($idUser){

            $user = $this->setUser($idUser);
            $users      = $this->getUsersRepository()->getUsers();
            //$comments   = $this->repository->callDbRead(['comments', ['user_id'=> $idUser], 'comment_date DESC']);
            //$posts      = $this->allPosts;


            echo $twig->render('/backend/accountManager.twig', array(
                'user'     => $user,
                'users'    => $users,
                //'comments' => $comments,
                //'posts'    => $posts,
                'session'  => $this->request()->session));
        }else{
            header('Location: /connect/login');
        }
    }

    public function updateUserRequest($data, User $user) // POST
    {

        //include '../config/includeTwig.php';
        $values =[];
        foreach ($data as $column => $value)
        {
            $values[$column] = htmlspecialchars($value);
        }
if($values['password'] === $user->getPassword()){
    $values['password'] = $user->getPassword();
}else{
    $values['password'] = password_hash($values['password'], PASSWORD_DEFAULT);
}
        try{
            // on vérifie que les données correspondent à la class User
            $this->getUserModel()->checkData($values);

            // on lance la requête de modification
            $this->getUsersRepository()->updateUser($values, ['id'=>$user->getId()]);
        }

        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function updateUser() // POST
    {
        $data = $this->request()->post;
        $user = $this->setUser($this->getIdConnect());
        $this->updateUserRequest($data, $user);
        $this->getUser();
    }
}