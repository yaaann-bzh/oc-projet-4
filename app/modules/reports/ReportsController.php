<?php
namespace forteroche\app\modules\reports;

use framework\HTTPrequest;
use framework\Application;
use framework\Controller;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
use forteroche\vendor\entity\Report;

class ReportsController extends Controller
{
    public function executeIndex(HTTPRequest $request)
    {
        $nbReportedComments = 20;
        $nbPages = (int)ceil($this->reportManager->countComments() / $nbReportedComments);//Arrondi au nombre entier supérieur
        $this->page->addVars('nbPages', $nbPages);

        $index = (int)$request->getData('index');
        
        if ($index === 1) {
            $prevIndex = '#';
            $nextIndex = 'index-' . ($index + 1);
            $begin = 0;
        } else {
            if ($index === $nbPages) {
                $prevIndex = 'index-' . ($index - 1);
                $nextIndex = '#';
            } else {
                $prevIndex = 'index-' . ($index - 1);
                $nextIndex = 'index-' . ($index + 1);
            }
            $begin = ($index - 1) * $nbReportedComments;
        }

        $this->page->addVars('index', $index);  
        $this->page->addVars('prevIndex', $prevIndex);
        $this->page->addVars('nextIndex', $nextIndex);

        $reported['reportDate'] = ' IS NOT NULL';

        $reportedComments = $this->commentManager->getList($begin, $nbReportedComments, $reported);
        if ($request->getData('index') !== null AND $index !== 1 AND empty($reportedComments)) {
            $this->app->httpResponse()->redirect404();
        }
        $this->page->addVars('reportedComments', $reportedComments);

        $nbReports = [];
        $members = [];
        foreach ($reportedComments as $comment) {
            $filter['commentId'] = '=' . $comment->id();
            $nbReports[$comment->id()] = $this->reportManager->count($filter);
            $reports[$comment->id()] = $this->reportManager->getList(null, null, $filter);
            $members[$comment->id()] = $this->memberManager->getSingle($comment->memberId());
        }

        if (!empty($reports)) {
            $this->page->addVars('nbReports', $nbReports);
            $this->page->addVars('reports', $reports);
            $this->page->addVars('members', $members);
        }

        $this->page->setTabTitle('Signalements');
        $this->page->setActiveNav('reports');
        
        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeReport(HTTPRequest $request)
    {
        $commentId = (int)$request->getData('comment');
        $comment = $this->commentManager->getSingle($commentId);
        if (empty($comment)) {
            $this->app->httpResponse()->redirect404();
        }
        $member = $this->memberManager->getSingle($comment->memberId());
        $post = $this->postManager->getSingle($comment->postId());
        $userId = (int)$this->app->user()->getAttribute('id');

        if ($request->postExists('motif')) {
            $content = $request->postData('motif') . ' - ' . $request->postData('content');
            $this->reportManager->add($userId, $commentId, $content, $comment->content());
            $this->commentManager->setReported($commentId);
        }

        $reportId = (int)$this->reportManager->getId($commentId, $userId);

        if ($reportId !== null) {
            $report = $this->reportManager->getSingle($reportId);
            $this->page->addVars('report', $report);
        }

        $this->page->addVars('comment', $comment);
        $this->page->addVars('member', $member);
        $this->page->addVars('post', $post);

        $this->page->setTabTitle('Signalement');
    
        $this->page->setContent(__DIR__.'/view/report.php');
        $this->page->generate();
    }
}
