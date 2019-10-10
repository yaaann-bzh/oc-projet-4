<?php
namespace forteroche\vendor\entity;

class Member
{
    protected $id;
    protected $privilege;
    protected $pseudo;
    protected $email;
    protected $pass;
    protected $memberName;
    protected $firstname;
    protected $inscriptionDate;
    protected $connexionId;

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

    // SETTERS //
    public function setPseudo($pseudo)
    {
        if (!is_string($pseudo) || empty($pseudo))
        {
            throw new Exception('Pseudo non valide');
        }

        $this->pseudo = $pseudo;
    }

    public function setEmail($email)
    {
        if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']))
        {
            throw new Exception('Adresse email non valide');
        }

        $this->email = $email;
    }

    public function setPass($pass)
    {
        if (!is_string($pass) || empty($pass))
        {
            throw new Exception('Mot de passe non valide');
        }

        $this->pass = $pass;
    }

    public function setMemberName($memberName)
    {
        if (!is_string($memberName) || empty($memberName))
        {
            throw new Exception('Nom non valide');
        }

        $this->memberName = $memberName;
    }

    public function setFirstname($firstname)
    {
        if (!is_string($firstname) || empty($firstname))
        {
            throw new Exception('Prénom non valide');
        }

        $this->firstname = $firstname;
    }

    public function setInscriptionDate(\DateTime $inscriptionDate)
    {
        $this->inscriptionDate = $inscriptionDate;
    }

    public function setConnexionId($connexionId)
    {
        if (!is_string($connexionId) || empty($connexionId))
        {
            throw new Exception('Le système a rencontré un problème : connexionId');
        }

        $this->connexionId = $connexionId;
    }

    // GETTERS //
    public function id()
    {
        return $this->id;
    }

    public function privilege()
    {
        return $this->privilege;
    }

    public function pseudo()
    {
        return $this->pseudo;
    }

    public function email()
    {
        return $this->email;
    }

    public function pass()
    {
        return $this->pass;
    }

    public function completeName()
    {
        return $this->memberName . ' ' . $this->firstname;
    }

    public function inscriptionDate()
    {
        return $this->inscriptionDate;
    }

    public function connexionId()
    {
        return $this->connexionId;
    }
}
