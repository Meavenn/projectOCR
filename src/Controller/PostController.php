<?php
namespace App\Controller;

use App\Model\Post;
use App\Model\Repository\PostsRepository;

/**
 * Cette classe permet d'appeler les méthodes d'un objet Post dont le résultat pourra être retourné via l'index
 *
 * @author marion
 *
 *
 */
class PostController extends AbstractController {
    private function getPostModel() {
        return new Post();
    }

    private function getPostsRepository() {
        return new PostsRepository();
    }

    public function __construct(int $id = NULL) {
    }

    public function getPosts() {
        $aTwig = ['session' => $this->request()->session];

        if ($this->getPostsRepository()->nbDisplayPost() === 0) {
            $aTwig = [
                'content' => 'Aucun article n\'est encore disponible. Un peu de patience !'
            ];
        } else {
            $posts = [];

            $aTwig['posts'] = $this->getPostsRepository()->getDisplayedPosts();
        }

        include '../config/includeTwig.php';
        echo $twig->render('/frontend/postsView.twig', $aTwig);
    }

    public function getPost($idPost) {
        if (!isset($idPost) || $idPost < 1) {
            throw new \Exception('Cet article n’existe pas !');
        }
        //$comments   = $this->repository->callDbRead(['comments', ['post_id' =>$idPost, 'validated' => 1],'date', 'ID, author, comment, DATE_FORMAT(comment_date, "%d/%m%/%Y") AS date']);

        include '../config/includeTwig.php';
        echo $twig->render('/frontend/postView.twig', [
            'post'    => $this->getPostsRepository()->getPost($idPost),
            //'comments'  => $comments,
            //'pending_comments' => $this->pending_comments,
            'session' => $this->request()->session
        ]);
    }

    public function getPostsAdmin() {
        if (!$this->request()->session['status']) {
            header('Location: /connect/login');
        }
        if (!$this->request()->session['status'] == 'member') {
            header('Location: /profil');
        }


        $posts = $this->getPostsRepository()->getPosts();
        //$idPost = $this->idUrl;
        //$collapse = isset($this->idUrl)?'show': NULL;

        $aTwig = [
            'session'  => $this->request()->session,
            'collapse' => 'show',
            'posts'    => $posts
        ];

        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/postsManager.twig', $aTwig);
    }

    public function newPost() {
        $this->getPostsAdmin();

        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/newPostsManager.twig', [
            'session' => $this->request()->session
        ]);
    }

    public function createPost() {
        // on récupère les données transmises par le formulaire
        $values = [];
        foreach ($this->request()->post as $key => $value) {
            if ($value) {
                $values [$key] = $value;
            }
        }
        $values += [
            'id_author'     => $this->getIdConnect(),
            'pseudo_author' => $this->getPostModel()->getUser($this->getIdConnect())->getPseudo(),
            'content_short' => $this->getPostModel()->setShortContent($this->request()->post['content_short'] ?: NULL, $values['content']),
            'login_insert'  => $this->getIdConnect(),
            'displayed_status' => 0
        ];

        // on vérifie que les données correspondent à la class Post
        $this->getPostModel()->checkData($values);
        // on insère les données dans la bdd
        var_dump($values);
        $this->getPostsRepository()->createPost($values);
        $this->getPostsAdmin();
    }

    public function getPostAdmin($idPost) {
        $this->getPostsAdmin();
        if (!isset($idPost) || $idPost < 1) {
            throw new \Exception('Cet article n’existe pas !');
        }
        //$comments   = $this->repository->callDbRead(['comments', ['post_id' =>$idPost, 'validated' => 1],'date', 'ID, author, comment, DATE_FORMAT(comment_date, "%d/%m%/%Y") AS date']);

        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/postUpdateManager.twig', [
            'post'    => $this->getPostsRepository()->getPost($idPost),
            //'comments'  => $comments,
            //'pending_comments' => $this->pending_comments,
            'session' => $this->request()->session
        ]);
    }

    public function updatePostAdmin($idPost) {
        if (!isset($idPost) || $idPost < 1) {
            throw new \Exception('Cet article n’existe pas !');
        }
        $values = [];
        foreach ($this->request()->post as $key => $value) {
            if ($key) {
                $values [$key] = $value;
            }
        }
        var_dump($values);
        $this->getPostsRepository()->updatePost($idPost, $values);
        $this->getPostAdmin($idPost);
    }

    public function deletePost($idPost) {
        if (!isset($idPost) || $idPost < 1 || !$this->getPostsRepository()->exist('post',$idPost)) {
            throw new \Exception('Cet article n’existe pas !');
        }
        $this->getPostsRepository()->deletePost($idPost);
        $this->getPostAdmin($idPost);
    }
}