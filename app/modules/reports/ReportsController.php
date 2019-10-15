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
            $members[$comment->id()] = $this->memberManager->getSingle($comment->memberId());
        }

        $this->page->addVars('nbReports', $nbReports);
        $this->page->addVars('members', $members);

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
            $content = $request->postData('motif') . ' : ' . $request->postData('content');
            $report = $this->reportManager->add($userId, $commentId, $content, $comment->content());
            $this->commentManager->setReported($commentId);
        }

        $reportId = (int)$this->reportManager->getId($commentId, $userId);

        if ($reportId !== 0) {
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

    public function executeShow(HTTPRequest $request)
    {
        $commentId = (int)$request->getData('comment');
        $updated = $request->getData('updated');
        $comment = $this->commentManager->getSingle($commentId);
        $filter['commentId'] = '=' . $comment->id();
        $reports = $this->reportManager->getList(null, null, $filter);

        if (empty($comment) OR empty($reports)) {
            $this->app->httpResponse()->redirect404();
        }
        
        $members = [];
        foreach ($reports as $report) {
            $members[$report->id()] = $this->memberManager->getSingle($report->authorId())->pseudo();
        }

        if ($request->postExists('action')) {
            try {
                switch ($request->postData('action')) { 
                    case 'Modifier': 
                        $content = $request->postData('content');
                        $this->commentManager->update($comment->id(), $content);
                        $suffixe = '-updated';
                    break;

                    case 'Supprimer': 
                        $this->commentManager->delete($commentId);
                        $suffixe = '';
                    break;
                }

                $this->reportManager->clear($commentId);
                $this->commentManager->clearReports($commentId);
                $this->app->httpResponse()->redirect('/user/comment-' . $commentId . $suffixe);

            } catch (\Exception $e) {
                $intro = 'Erreur lors de la modification du commentaire';
                $message = $e->getMessage();
            }

            $this->errorPage($intro, $message);
        }

        $member = $this->memberManager->getSingle($comment->memberId());
        $post = $this->postManager->getSingle($comment->postId());
        
        $this->page->addVars('comment', $comment);
        $this->page->addVars('member', $member);
        $this->page->addVars('members', $members);
        $this->page->addVars('reports', $reports);
        $this->page->addVars('post', $post);
        $this->page->addvars('updated', $updated);

        $this->page->setTabTitle('Signalements');

        $this->page->setContent(__DIR__.'/view/single.php');
        $this->page->generate();
    }
}
