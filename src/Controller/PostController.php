<?php
namespace App\Controller;

use App\Model\Comment;
use App\Model\CommentStatus;
use App\Model\Post;
use App\Model\Repository\CommentsRepository;
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


    public function getPosts() {
        if ($this->getPostsRepository()->nbDisplayPost() === 0) {
            $aTwig = [
                'content' => 'Aucun article n\'est encore disponible. Un peu de patience !'
            ];
        } else {
            $posts = [];
            $aTwig = [
                'posts' => $this->getPostsRepository()->getDisplayedPosts()
            ];
        }

        include '../config/includeTwig.php';
        echo $twig->render('/frontend/postsView.twig', $aTwig);
    }

    public function getComments($idPost) {
        return (new CommentController())->getComments($idPost);
    }

    public function getDisplayedComments($idPost) {
        return (new CommentController())->getDisplayedComments($idPost);
    }

    public function getPost(int $idPost) {
        $exist = $this->getPostsRepository()->postExist($idPost);

        // si l'id du post n'existe pas, on est redirigé vers la liste des posts
        if (!$exist) {
            include '../config/includeTwig.php';
            $aTwig['content'] = 'Cet article n’existe pas (encore) !';
            $this->getPosts();
        }
        //sinon, on instancie un nouvel objet post

        $comments = $this->getDisplayedComments($idPost);
        $aPost = $this->getPostsRepository()->getPost($idPost);
        $oPost = new Post($aPost);
        $noComment = "";

        if (!$comments) {
            $noComment = "Il n'y a aucun commentaire. Pour le moment !";
        }

        $aTwig = [
            'post'      => $oPost,
            'comments'  => $comments,
            'noComment' => $noComment
            //'pending_comments' => $this->pending_comments
        ];

        include '../config/includeTwig.php';
        echo $twig->render('/frontend/postView.twig', $aTwig);
    }

    public function getPostsAdmin() {
        if (!$this->request()->session['status']) {
            header('Location: /connect/login');
        }
        if (!$this->request()->session['status'] == 'member') {
            header('Location: /profil');
        }


        $posts = $this->getPostsRepository()->getPosts();
        //$collapse = isset($this->idUrl)?'show': NULL;

        $aTwig = [
            'collapse' => 'show',
            'posts'    => $posts
        ];

        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/postsManager.twig', $aTwig);
    }

    public function newPost() {
        $this->getPostsAdmin();
        $aTwig[] = '';
        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/newPostsManager.twig', $aTwig);
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
            'id_author'        => $this->getIdConnect(),
            'pseudo_author'    => $this->getPostModel()->getUser($this->getIdConnect())->getPseudo(),
            'content_short'    => $this->getPostModel()->setShortContent($this->request()->post['content_short'] ?: NULL, $values['content']),
            'login_insert'     => $this->getIdConnect(),
            'displayed_status' => 0
        ];

        // on vérifie que les données correspondent à la class Post
        $this->getPostModel()->checkData($values);
        // on insère les données dans la bdd
        $this->getPostsRepository()->createPost($values);
        $this->getPostsAdmin();
    }

    public function getPostAdmin($idPost) {
        $this->getPostsAdmin();
        if (!isset($idPost) || $idPost < 1) {
            throw new \Exception('Cet article n’existe pas !');
        }
        $comments = $this->getComments($idPost);

        $aTwig = [
            'posts'    => $this->getPostsRepository()->getPosts(),
            'post'     => $this->getPostsRepository()->getPost($idPost),
            'comments' => $comments
            //'pending_comments' => $this->pending_comments
        ];
//        echo '<pre>';
//        var_dump($comments);
//        echo '</pre>';
        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/postUpdateManager.twig', $aTwig);
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

        $this->getPostsRepository()->updatePost($idPost, $values);
        $this->getPostAdmin($idPost);
    }

    public function deletePost($idPost) {
        if (!isset($idPost) || $idPost < 1 || !$this->getPostsRepository()->exist('post', $idPost)) {
            throw new \Exception('Cet article n’existe pas !');
        }
        $this->getPostsRepository()->deletePost($idPost);
        $this->getPostAdmin($idPost);
    }

    public function updateCommentPostAdmin($idComment){
        $Comment = new Comment((new CommentsRepository())->getComment($idComment));
        (new CommentController())->updateComment($idComment);

        header('Location: /postAdmin/'.$Comment->getIdPost());
    }

}