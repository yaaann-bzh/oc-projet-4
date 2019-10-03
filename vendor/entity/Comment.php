<?php
namespace forteroche\vendor\entity;

class Comment 
{
    protected $id;
    protected $postId;
    protected $userId;
    protected $content;
    protected $addDate;
    protected $updateDate;

    //Mother
    public function __construct(array $donnees = [])
    {
        if (!empty($donnees))
        {
            $this->hydrate($donnees);
        }
    }

    //Mother
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $attribut => $valeur)
        {
            $methode = 'set'.ucfirst($attribut);

            if (is_callable([$this, $methode]))
            {
                $this->$methode($valeur);
            }
        }
    }

    // GETTERS //

    public function id()
    {
        return $this->id;
    }
    
    public function postId()
    {
        return $this->postId;
    }    
    
    public function userId()
    {
        return $this->userId;
    }    
    
    public function content()
    {
        return $this->content;
    }
    
    public function addDate()
    {
        return $this->addDate;
    }
    
    public function updateDate()
    {
        return $this->updateDate;
    }

    // Setters //
    
    public function setUserId()
    {
        if (!is_int($userId) || empty($userId))
        {
            throw new Exception('Identifiant auteur invalide');
        }

        $this->userId = $userId;
    }    
    
    public function setContent($content)
    {
        if (!is_string($content) || empty($content))
        {
            throw new Exception('Contenu du post invalide');
        }

        $this->content = $content;
    }
    
    public function setAddDate(\DateTime $addDate)
    {
        $this->addDate = $addDate;
    }
    
    public function setUpdateDate(\DateTime $updateDate)
    {
        $this->updateDate = $updateDate;
    }

}