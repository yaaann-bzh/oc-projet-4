<?php
namespace forteroche\vendor\model;

use forteroche\vendor\entity\Post;

class PostManager extends \framework\Manager 
{
    public function getList($debut, $limit, $filters = []) {
        $sql = 'SELECT * FROM posts ORDER BY id DESC';

        if (isset($debut) && isset($limit)) {
            $sql .= ' LIMIT ' .(int) $limit.' OFFSET '.(int) $debut; 
        }

        $req = $this->dao->query($sql);
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Post');
        $posts = $req->fetchAll();

        foreach ($posts as $post)
        {
            $post->setAddDate(new \DateTime($post->addDate()));
            if ($post->updateDate() != null) {
                $post->setUpdateDate(new \DateTime($post->updateDate()));
            }
        }
        
        $req->closeCursor();
        
        return $posts;
    }

    public function getSingle($id)
    {
        $req = $this->dao->prepare('SELECT * FROM posts WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Post');
        
        if ($post = $req->fetch())
        {
            $post->setAddDate(new \DateTime($post->addDate()));
            if ($post->updateDate() != null) {
                $post->setUpdateDate(new \DateTime($post->updateDate()));
            }
            return $post;
        }
        
        return null;  
    }

    public function count(){
        return (int)$this->dao->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    }

    public function add(Post $post)
    {
        $req = $this->dao->prepare('INSERT INTO posts SET authorId = :authorId, title = :title, content = :content, addDate = NOW()');
        
        $req->bindValue(':authorId', $post->authorId(), \PDO::PARAM_INT);
        $req->bindValue(':title', $post->title());
        $req->bindValue(':content', $post->content());
        
        $req->execute();

        $post->setId((int)$this->dao->lastInsertId());
    }

}

