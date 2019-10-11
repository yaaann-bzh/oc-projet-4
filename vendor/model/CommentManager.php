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

    public function getList($debut, $limit, $filters=[])
    {
        $sql = 'SELECT * FROM comments';

        if (!empty($filters)) {
            $sql .= ' WHERE ';
            foreach ($filters as $key => $filter) {
                $sql .= $key . '=' . $filter . ' AND ';
            }
            $sql = substr($sql, 0, -5);
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

    public function getSingle($id)
    {
        $req = $this->dao->prepare('SELECT * FROM comments WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Comment');
        
        if ($comment = $req->fetch())
        {
            $comment->setAddDate(new \DateTime($comment->addDate()));
            if ($comment->updateDate() != null) {
                $comment->setUpdateDate(new \DateTime($comment->updateDate()));
            }
            return $comment;
        }
        
        return null;  
    }

    public function count($filters=[])
    {
        $sql = 'SELECT COUNT(*) FROM comments';

        if (!empty($filters)) {
            $sql .= ' WHERE ';
            foreach ($filters as $key => $filter) {
                $sql .= $key . '=' . $filter . ' AND ';
            }
            $sql = substr($sql, 0, -5);
        }
        return (int)$this->dao->query($sql)->fetchColumn();
    }

    public function add($memberId, $postId, $content)
    {
        $q = $this->dao->prepare('INSERT INTO comments SET memberId = :memberId, postId = :postId, content = :content, addDate = NOW()');
        
        $q->bindValue(':memberId', $memberId);
        $q->bindValue(':postId', $postId);
        $q->bindValue(':content', $content);
        
        $q->execute();
    }

    public function update($id, $content)
    {
        $q = $this->dao->prepare('UPDATE comments SET content = :content, updateDate = NOW() WHERE id = :id');
        
        $q->bindValue(':content', $content);
        $q->bindValue(':id', $id, \PDO::PARAM_INT);

        $q->execute();
    }

    public function delete($id)
    {
        $q = $this->dao->prepare('UPDATE comments SET removed = 1, updateDate = NOW() WHERE id = :id');
        
        $q->bindValue(':id', $id, \PDO::PARAM_INT);

        $q->execute();
    }

    public function deleteFromPost($postId)
    {
        $this->dao->exec('DELETE FROM comments WHERE postId = '.(int) $postId);
    }
}
