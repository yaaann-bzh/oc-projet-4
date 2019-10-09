<?php
namespace forteroche\vendor\entity;

class Post 
{
    protected $id;
    protected $authorId;
    protected $title;
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

    public function getExerpt()
    {
        $exerptLength = 200;
        $textContent = nl2br(htmlspecialchars($this->content), ENT_QUOTES | ENT_SUBSTITUTE);
        $fisrtBrPos = strpos($textContent, '<br />');

        if ($fisrtBrPos <= 200) {
            $exerptLength = $fisrtBrPos;
        }
        return substr($textContent, 0, $exerptLength);
    }

    // SETTERS //

    public function setId($id)
    {
        if (!is_int($id) || empty($id))
        {
            throw new \Exception('Identifiant de publication invalide');
        }

        $this->id = $id;
    }

    public function setAuthorId($authorId)
    {
        if (!is_int($authorId) || empty($authorId))
        {
            throw new \Exception('Identifiant auteur invalide');
        }

        $this->authorId = $authorId;
    }

    public function setTitle($title)
    {
        if (!is_string($title) || empty($title))
        {
            throw new \Exception('Titre du post invalide');
        }

        $this->title = $title;
    }

    public function setContent($content)
    {
        if (!is_string($content) || empty($content))
        {
            throw new \Exception('Contenu du post invalide');
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

    // GETTERS //

    public function id()
    {
        return $this->id;
    }
    public function authorId()
    {
        return $this->authorId;
    }

    public function title()
    {
        return $this->title;
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

}
