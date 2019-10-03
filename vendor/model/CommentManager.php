<?php
namespace forteroche\vendor\model;

class CommentManager extends \framework\Manager 
{
    public function getByPost($id)
    {
        $req = $this->dao->prepare('SELECT * FROM comments WHERE postId = :postId ORDER BY addDate DESC');
        $req->bindValue(':postId', (int) $id, \PDO::PARAM_INT);
        $req->execute();

        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Comment');

        $comments = $req->fetchAll();

        foreach ($comments as $comment ) {
            $comment->setAddDate(new \DateTime($comment->addDate()));
            if ($comment->updateDate() != null) {
                $comment->setUpdateDate(new \DateTime($comment->updateDate()));
            }
        }
        
        $req->closeCursor();
        
        return $comments;
    }

    public function getList($debut, $limit, $userId = null)
    {
        $sql = 'SELECT * FROM comments';

        if ($userId !== null) {
            $sql .= ' WHERE userId=' . $userId;
        }

        $sql .= ' ORDER BY addDate DESC';

        if (isset($debut) && isset($limit)) {
            $sql .= ' LIMIT ' .(int) $limit.' OFFSET '.(int) $debut; 
        }

        $req = $this->dao->query($sql);
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Comment');
        $comments = $req->fetchAll();

        foreach ($comments as $comment)
        {
            $comment->setAddDate(new \DateTime($comment->addDate()));
            if ($comment->updateDate() != null) {
                $comment->setUpdateDate(new \DateTime($comment->updateDate()));
            }
        }
        
        $req->closeCursor();
        
        return $comments;
    }

    public function count($key = null, $id = null)
    {
        $sql = 'SELECT COUNT(*) FROM comments';

        if ($key !== null AND $id !== null) {
            $sql .= ' WHERE ' . $key . '=' . (int)$id;
        }
        return (int)$this->dao->query($sql)->fetchColumn();
    }
}
