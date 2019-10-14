<?php
namespace forteroche\vendor\model;

class ReportManager extends \framework\Manager 
{

    public function getList($debut, $fin, $filters = []){
        $sql = 'SELECT * FROM reports';

        if (!empty($filters)) {
            $sql .= ' WHERE ';
            foreach ($filters as $key => $filter) {
                $sql .= $key . $filter . ' AND ';
            }
            $sql = substr($sql, 0, -5);
        }

        $sql .= ' ORDER BY reportDate DESC';

        if ($debut !== null && $limit !== null) {
            $sql .= ' LIMIT ' .(int) $limit.' OFFSET '.(int) $debut; 
        }

        $req = $this->dao->query($sql);
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'forteroche\vendor\entity\Report');
        $reports = $req->fetchAll();

        foreach ($reports as $report)
        {
            $report->reportDate(new \DateTime($report->reportDate()));
        }
        
        $req->closeCursor();
        
        return $reports;
    }

    public function countComments() {
        return (int)$this->dao->query('SELECT COUNT(*) FROM reports GROUP BY commentId')->fetchColumn();
    }

    public function count($filters=[])
    {
        $sql = 'SELECT COUNT(*) FROM reports';

        if (!empty($filters)) {
            $sql .= ' WHERE ';
            foreach ($filters as $key => $filter) {
                $sql .= $key . $filter . ' AND ';
            }
            $sql = substr($sql, 0, -5);
        }
        return (int)$this->dao->query($sql)->fetchColumn();
    }

    public function add($authorId, $commentId, $content, $commentContent)
    {
        $q = $this->dao->prepare('INSERT INTO reports SET authorId = :authorId, commentId = :commentId, content = :content, commentContent = :commentContent, reportDate = NOW()');
        
        $q->bindValue(':authorId', $authorId);
        $q->bindValue(':commentId', $commentId);
        $q->bindValue(':content', $content);
        $q->bindValue(':commentContent', $commentContent);
        
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