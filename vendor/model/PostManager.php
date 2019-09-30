<?php
namespace forteroche\vendor\model;

class PostManager extends \framework\Manager 
{
    public function getList($debut, $fin) {
        $sql = 'SELECT * FROM posts ORDER BY id DESC';

        if (isset($debut) && isset($fin)) {
            $sql .= ' LIMIT ' .(int) $fin.' OFFSET '.(int) $debut; 
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Post');
        $posts = $requete->fetchAll();

        foreach ($posts as $post)
        {
            $post->setAddDate(new \DateTime($post->addDate()));
            if ($post->updateDate() != null) {
                $post->setUpdateDate(new \DateTime($post->updateDate()));
            }
        }
        
        $requete->closeCursor();
        
        return $posts;
    }

    public function count(){
        return $this->dao->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    }

    public function add(Post $post)
    {
        $requete = $this->dao->prepare('INSERT INTO posts(authorId, title, content, addDate) VALUES(:authorId, :title, :content, NOW()) ');
        $requete->execute(array(
            'authorId' => $post->getAuthorId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ));
    }

}

