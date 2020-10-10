<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\Post;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\PostsRepository;
use Exception;

/**
 * Cette classe permet d'appeler les méthodes d'un objet Post dont le résultat pourra être retourné via l'index
 *
 * @author marion
 *
 *
 */
class PostController extends AbstractController
{
    private function getPostModel()
    {
        return new Post();
    }

    private function getPostsRepository()
    {
        return new PostsRepository();
    }


    public function getPosts()
    {
        if ($this->getPostsRepository()->nbDisplayPost() === 0) {
            $aTwig = $this->getATwig([
                'content' => 'Aucun article n\'est encore disponible. Un peu de patience !'
            ]);
        } else {
            $aTwig = $this->getATwig([
                'posts' => $this->getPostsRepository()->getDisplayedPosts(false)
            ]);
        }

        return $this->twig()->render('/frontend/postsView.twig', $aTwig);
    }

    public function getComments($idPost)
    {
        return (new CommentController())->getComments($idPost);
    }

    public function getDisplayedComments($idPost)
    {
        return (new CommentController())->getDisplayedComments($idPost);
    }

    public function getPost(int $idPost)
    {
        $exist = $this->getPostsRepository()->postExist($idPost);

        // si l'id du post n'existe pas, on est redirigé vers la liste des posts
        if (!$exist) {
        return $this->getPosts();
        }
        //sinon, on instancie un nouvel objet post
        $comments = $this->getDisplayedComments($idPost);
        $aPost = $this->getPostsRepository()->getPost($idPost);
        $noComment = "";

        if (!$comments) {
            $noComment = "Il n'y a aucun commentaire. Pour le moment !";
        }

        $aTwig = $this->getATwig([
            'post' => $aPost,
            'comments' => $comments,
            'noComment' => $noComment
        ]);
        return $this->twig()->render('/frontend/postView.twig', $aTwig);
    }

    public function getPostsAdmin()
    {
        if ($this->getIdConnect()) {
            if (!$this->request()->session['status']) {
                header('Location: /connect/login');
            }
            if (!$this->request()->session['status'] == 'member') {
                header('Location: /profil');
            }

            $posts = $this->getPostsRepository()->getPosts();

            $aTwig = $this->getATwig([
                'collapse' => 'show',
                'posts' => $posts
            ]);

            return $this->twig()->render('/backend/admin/postsManager.twig', $aTwig);
        } else {
            header('Location: /connect/login');
        }

    }

    public function newPost()
    {
        $this->getPostsAdmin();
        $aTwig = $this->getATwig();
        return $this->twig()->render('/backend/admin/newPostsManager.twig', $aTwig);
    }

    public function chekPostData($postValues)
    {
        $values = [];
        foreach ($postValues as $key => $value) {
            if ($value) {
                $values [$key] = $value;
            }
        }
        $values += [
            'id_author' => $this->getIdConnect(),
            'pseudo_author' => $this->getPostModel()->getUser($this->getIdConnect())->getPseudo(),
            'content_short' => !$this->request()->post['content_short'] ? $values['content'] : '',
            'login_insert' => $this->getIdConnect(),
            'displayed_status' => 0,
            'login_mod' => $this->getIdConnect(),
            'date_mod' => date('Y-m-d H:i:s')
        ];
        // on vérifie que les données correspondent à la class Post
        $this->getPostModel()->checkData($values);

        return $values;
    }

    public function createPost()
    {
        // on récupère les données transmises par le formulaire
        $postValues = $this->request()->post;

        // on vérifie qu'elles correspondent à Post
        $this->chekPostData($postValues);

        // on insère les données dans la bdd
        $this->getPostsRepository()->createPost($postValues);
        return $this->getPostsAdmin();
    }

    public function getPostAdmin($idPost)
    {
        $this->getPostsAdmin();
        if (!isset($idPost) || $idPost < 1) {
            throw new Exception('Cet article n’existe pas !');
        }
        $exist = $this->getPostsRepository()->postExist($idPost);

        // si l'id du post n'existe pas, on est redirigé vers la liste des posts
        if (!$exist) {
            return $this->getPostsAdmin();
        }
        $comments = $this->getComments($idPost);

        $aTwig = $this->getATwig([
            'posts' => $this->getPostsRepository()->getPosts(),
            'post' => $this->getPostsRepository()->getPost($idPost),
            'comments' => $comments
        ]);
        return $this->twig()->render('/backend/admin/postUpdateManager.twig', $aTwig);
    }


    public function getPostSelectedAdmin()
    {
        $this->getPostAdmin($this->request()->post['postId']);
    }

    public function updatePostAdmin($idPost)
    {
        if (!isset($idPost) || $idPost < 1) {
            throw new Exception('Cet article n’existe pas !');
        }
        // on récupère les données transmises par le formulaire
        $postValues = $this->request()->post;

        // on vérifie qu'elles correspondent à Post
        $values = $this->chekPostData($postValues);
        $this->getPostsRepository()->updatePost($idPost, $values);
        header('Location: /postAdmin/' . $idPost);

    }

    public function deletePost($idPost)
    {
        if (!isset($idPost) || $idPost < 1 || !$this->getPostsRepository()->exist('post', $idPost)) {
            throw new Exception('Cet article n’existe pas !');
        }
        $this->getPostsRepository()->deletePost($idPost);
        return $this->getPostsAdmin();
    }

    public function updateCommentPostAdmin($idComment)
    {
        $Comment = new Comment((new CommentsRepository())->getComment($idComment));
        (new CommentController())->updateComment($idComment);

        header('Location: /postAdmin/' . $Comment->getIdPost());
    }

}