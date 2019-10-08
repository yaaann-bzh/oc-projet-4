<?php
namespace forteroche\vendor\model;

class ReportManager extends \framework\Manager 
{

    public function getList($id = null, $debut, $fin){
        
    }

    public function count(){
        
    }

    public function add($authorId, $commentId, $content)
    {
        $q = $this->dao->prepare('INSERT INTO reports SET authorId = :authorId, commentId = :commentId, content = :content, reportDate = NOW()');
        
        $q->bindValue(':authorId', $authorId);
        $q->bindValue(':commentId', $commentId);
        $q->bindValue(':content', $content);
        
        $q->execute();
    }

    public function getId($commentId, $authorId)
    {
        $sql = 'SELECT id FROM reports WHERE commentId=' . $commentId . ' AND authorId=' . $authorId;

        return $this->dao->query($sql)->fetchColumn();
    }

    public function getSingle($id)
    {
        $req = $this->dao->prepare('SELECT * FROM reports WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->execute();
        
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Report');
        
        if ($report = $req->fetch())
        {
            $report->setReportDate(new \DateTime($report->reportDate()));
            return $report;
        }
        
        return null;  
    }
}