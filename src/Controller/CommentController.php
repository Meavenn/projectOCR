<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\CommentStatus;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\UsersRepository;
use Exception;

/**
 * Cette classe permet d'appeler les méthodes d'un objet Comment dont le résultat pourra être retourné via l'index
 *
 * @author marion
 *
 *
 */
class CommentController extends AbstractController
{

    private function getCommentModel()
    {
        return new Comment();
    }

    private function getCommentsRepository()
    {
        return new CommentsRepository();
    }

    public function getDisplayedComments($idPost)
    {
        $comments = $this->getCommentsRepository()->getCommentsPost($idPost);
        $Comments = [];
        foreach ($comments as $comment) {
            $Comment = new Comment($comment);
            if ($Comment->getDisplayedStatus() == CommentStatus::granted) {
                $Comments[] = $Comment;
            }
        }
        return $Comments;
    }

    public function getComments($idPost)
    {
        $comments = $this->getCommentsRepository()->getCommentsPost($idPost);
        $Comments = [];
        foreach ($comments as $comment) {
            $Comment = new Comment($comment);
            $Comments[] = $Comment;
        }
        return $Comments;
    }

    public function addComment($idPost)
    {
        $idUser = $this->getIdConnect() ?: header('Location: /connect/login');
        $User = $this->getCommentModel()->getUser($idUser);

        $idPost = (int)$idPost ?: header('Location: /posts');

        if (!isset($this->request()->post['content'])) {
            throw new Exception(' Le commentaire n\'a pas été ajouté.');
        }

        $values = [];
        foreach ($this->request()->post as $column => $value) {
            $values[$column] = htmlspecialchars($value);
        }
        $values += [
            'id_post' => $idPost,
            'id_author' => $idUser,
            'pseudo_author' => $User->getPseudo(),
            'displayed_status' => $this->getCommentModel()->isAdmin() ? 'granted' : 'pending'
        ];

        $this->getCommentModel()->checkData($values);

        $granted = $this->getCommentsRepository()->createComment($values);

        if ($granted) {
            header('Location: /post/' . $idPost);
        } else {
            header('Location: /posts');
        }
    }

    public function updateComment(int $id)
    {
        $values = [];
        foreach ($this->request()->post as $column => $value) {
            $values[$column] = $value;
        }

        if (!$this->isAdmin()) {
            $values['displayed_status'] = CommentStatus::pending;
        }

        try {
            $this->getCommentsRepository()->updateComment($values, $id);
        } catch (Exception $e) {
            header('Location: /profil');
        }
    }

    public function getCommentsAdmin()
    {
        if ($this->getIdConnect()) {
            $comments = [];
            foreach ($this->getCommentsRepository()->getComments() as $comment) {
                $comments[] = new Comment($comment);
            }

            $aTwig = $this->getATwig([
                'comments' => $comments,
                'posts' => (new PostsRepository())->getPosts(),
                'authors' => (new UsersRepository())->getUsers()
            ]);

            return $this->twig()->render('/backend/admin/commentsManager.twig', $aTwig);
        }
            header('Location: /connect/login');

    }

    public function getFilteredComments()
    {
        $condition = [];
        foreach ($this->request()->post as $column => $value) {
            $condition[$column] = "'$value'";
        }

        $aTwig = $this->getATwig([
            'comments' => $this->getCommentsRepository()->getCondition($condition),
            'posts' => (new PostsRepository())->getPosts(),
            'authors' => (new UsersRepository())->getUsers()
        ]);
        return $this->twig()->render('/backend/admin/commentsManager.twig', $aTwig);
    }


    public function updateCommentAdmin($idComment)
    {
        $this->updateComment($idComment);
        header('Location: /comments');
    }
}