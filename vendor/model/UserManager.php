<?php
namespace forteroche\vendor\model;

class UserManager extends \framework\Manager 
{
    public function getSingle($id)
    {
        $req = $this->dao->prepare('SELECT * FROM users WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\User');
        
        if ($user = $req->fetch())
        {
            $user->setInscriptionDate(new \DateTime($user->inscriptionDate()));
            return $user;
        }
        
        return null; 
    }

    public function getList($debut, $limit, $id = null) {
        # code...
    }

    public function count($admin = null)
    {
        $sql = 'SELECT COUNT(*) FROM user';

        if ($admin === true) {
            $sql .= ' WHERE admin=1';
        } elseif ($admin === false) {
            $sql .= ' WHERE admin=0';
        }

        return (int)$this->dao->query($sql)->fetchColumn();
    }
}
