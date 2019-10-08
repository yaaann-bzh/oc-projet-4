<?php
namespace forteroche\app\modules\comments;

use framework\HTTPrequest;
use framework\Application;
use framework\ApplicationComponent;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
use forteroche\vendor\model\PostManager;
use forteroche\vendor\model\CommentManager;
use forteroche\vendor\model\MemberManager;
use forteroche\vendor\model\ReportManager;
use forteroche\vendor\entity\Comment;

class CommentsController extends ApplicationComponent 
{
    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';
    protected $postManager = null;
    protected $commentManager = null;
    protected $memberManager = null;
    protected $reportManager = null;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->postManager = new PostManager(PDOFactory::getMysqlConnexion());
        $this->commentManager = new CommentManager(PDOFactory::getMysqlConnexion());
        $this->memberManager = new MemberManager(PDOFactory::getMysqlConnexion());
        $this->reportManager = new ReportManager(PDOFactory::getMysqlConnexion());
        $this->page = new Page($app);
        $this->module = $module;
        $this->action = $action;
        $this->view = $action;
    }

    public function page()
    {
        return $this->page;
    }

    public function execute()
    {
        $method = 'execute'.ucfirst($this->action);

        if (!is_callable([$this, $method]))
        {
            throw new \RuntimeException('L\'action "'.$this->action.'" n\'est pas définie sur ce module');
        }

        $this->$method($this->app->httpRequest());
    }

    public function executeIndex(HTTPRequest $request)
    {
        $nbComments = 10;
        $nbPages = (int)ceil($this->commentManager->count() / $nbComments);//Arrondi au nombre entier supérieur
        $this->page->addVars('nbPages', $nbPages);

        $index = (int)$request->getData('index');

        if ($index === 1) {
            $prevIndex = '#';
            $nextIndex = 'comments-index-' . ($index + 1);
            $begin = 0;
        } else {
            if ($index === $nbPages) {
                $prevIndex = 'comments-index-' . ($index - 1);
                $nextIndex = '#';
            } else {
                $prevIndex = 'comments-index-' . ($index - 1);
                $nextIndex = 'comments-index-' . ($index + 1);
            }
            $begin = ($index - 1) * $nbComments;
        } 

        $this->page->addVars('index', $index);  
        $this->page->addVars('prevIndex', $prevIndex);
        $this->page->addVars('nextIndex', $nextIndex);

        $comments = $this->commentManager->getList($begin, $nbComments);
        if ($index !== 1 AND empty($comments)) {
            $this->app->httpResponse()->redirect404();
        }

        $posts = [];
        $members = [];
        foreach ($comments as $comment) {
            $post = $this->postManager->getSingle($comment->postId());
            $posts[$comment->id()] = $post->title();
            $members[$comment->id()] = $this->memberManager->getSingle($comment->memberId());
        }

        $this->page->addVars('comments', $comments);
        $this->page->addVars('posts', $posts);
        $this->page->addVars('members', $members);

        $this->page->setTabTitle('Derniers commentaires');
        $this->page->setActiveNav('comments');

        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeIndexByMember(HTTPRequest $request)
    {
        $nbComments = 10;
        $memberId = (int)$request->getData('member');
        $index = (int)$request->getData('index');

        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            $this->app->httpResponse()->redirect404();
        }
        $this->page->addVars('member', $member);

        $nbPages = (int)ceil($this->commentManager->count('memberId', $member->Id()) / $nbComments);//Arrondi au nombre entier supérieur
        $this->page->addVars('nbPages', $nbPages);
    
        if ($index === 1) {
            $prevIndex = '#';
            $nextIndex = 'member-' . $member->Id() . '-' . ($index + 1);
            $begin = 0;
        } else {
            if ($index === $nbPages) {
                $prevIndex = 'member-' . $member->Id() . '-' . ($index - 1);
                $nextIndex = '#';
            } else {
                $prevIndex = 'member-' . $member->Id() . '-' . ($index - 1);
                $nextIndex = 'member-' . $member->Id() . '-' . ($index + 1);
            }
            $begin = ($index - 1) * $nbComments;
        } 
        $this->page->addVars('index', $index);  
        $this->page->addVars('prevIndex', $prevIndex);
        $this->page->addVars('nextIndex', $nextIndex);

        $comments = $this->commentManager->getList($begin, $nbComments, $member->id());
        if ($index !== 1 AND empty($comments)) {
            $this->app->httpResponse()->redirect404();
        }

        $posts = [];
        foreach ($comments as $comment) {
            $post = $this->postManager->getSingle($comment->postId());
            $posts[$comment->id()] = $post->title();
        }

        $this->page->addVars('comments', $comments);
        $this->page->addVars('posts', $posts);

        $this->page->setTabTitle('Derniers commentaires');

        $this->page->setContent(__DIR__.'/view/member.php');
        $this->page->generate();
    }

    public function executeInsert(HTTPRequest $request)
    {
        if ($this->app()->user()->isAuthenticated() AND $request->postExists('comment')) {
            $memberId = (int)$this->app->user()->getAttribute('id');
            $postId = (int)$request->getData('post');
            $content = $request->postData('comment');

            try {
                if ($this->memberManager->exists($memberId) AND $this->postManager->exists($postId)) {
                    $comment = new Comment([
                        'memberId' => $memberId,
                        'postId' => $postId,
                        'content' => $content
                    ]);

                    $this->commentManager->add($memberId, $postId, $content);
                }

                $this->app->httpResponse()->redirect('/post-' . $postId . '#comments');

            } catch (\Exception $e) {
                $intro = 'Erreur lors de l\'ajout du commentaire';
                $message = $e->getMessage();
            }
            
            $this->page->addVars('intro', $intro);
            $this->page->addVars('message', $message);
    
            $this->page->setTabTitle('Erreur');
    
            $this->page->setContent(__DIR__.'/../../../Errors/modelError.php');
            $this->page->generate();
            
        }
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
            $this->reportManager->add($userId, $commentId, $content);
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
