<?php
namespace forteroche\vendor\model;

class PostManager extends \framework\Manager 
{
    public function getList($debut, $limit, $id = null) {
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
        $req = $this->dao->prepare('INSERT INTO posts(authorId, title, content, addDate) VALUES(:authorId, :title, :content, NOW()) ');
        $req->execute(array(
            'authorId' => $post->getAuthorId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ));
    }

}

