<?php
namespace forteroche\vendor\model;

use forteroche\vendor\entity\Member;

class MemberManager extends \framework\Manager 
{
    public function getSingle($id)
    {
        $req = $this->dao->prepare('SELECT * FROM members WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Member');
        
        if ($member = $req->fetch())
        {
            $member->setInscriptionDate(new \DateTime($member->inscriptionDate()));
            if ($member->deleteDate() != null) {
                $member->setDeleteDate(new \DateTime($member->deleteDate()));
            }
            return $member;
        }
        
        return null; 
    }

    public function getList($debut, $limit, $id = null) {
        # code...
    }

    public function count($admin = null)
    {
        $sql = 'SELECT COUNT(*) FROM member';

        if ($admin === true) {
            $sql .= ' WHERE admin=1';
        } elseif ($admin === false) {
            $sql .= ' WHERE admin=0';
        }

        return (int)$this->dao->query($sql)->fetchColumn();
    }

    public function getId($var)
    {
        $key = '';
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $var))
        {
            $key = 'email';
        }
        else
        {
            $key = 'pseudo';
        }

        $sql = 'SELECT id FROM members WHERE ' . $key . '="' . $var . '"';
        return $this->dao->query($sql)->fetchColumn();
    }

    public function saveConnexionId($id, $connexionId)
    {
        $q = $this->dao->prepare('UPDATE members SET connexionId = :connexionId WHERE id = :id');
        
        $q->bindValue(':connexionId', $connexionId);
        $q->bindValue(':id', $id, \PDO::PARAM_INT);

        $q->execute();
    }

    public function checkConnexionId($connexionId)
    {
        $req = $this->dao->prepare('SELECT * FROM members WHERE connexionId = :connexionId');
        $req->bindValue(':connexionId', $connexionId);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Member');
        
        if ($member = $req->fetch())
        {
            $member->setInscriptionDate(new \DateTime($member->inscriptionDate()));
            return $member;
        }
        
        return null; 
    }

    public function add(Member $member)
    {
        $req = $this->dao->prepare('INSERT INTO members SET pseudo = :pseudo, email = :email, pass = :pass, lastname = :lastname, firstname = :firstname, inscriptionDate = NOW()');
        
        $req->bindValue(':pseudo', $member->pseudo());
        $req->bindValue(':email', $member->email());
        $req->bindValue(':pass', $member->pass());
        $req->bindValue(':lastname', $member->lastname());
        $req->bindValue(':firstname', $member->firstname());
        
        $req->execute();
    }
}
