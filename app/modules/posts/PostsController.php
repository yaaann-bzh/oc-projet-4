<?php
namespace forteroche\app\modules\posts;

use framework\HTTPrequest;
use framework\Application;
use framework\ApplicationComponent;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
use forteroche\vendor\model\PostManager;
use forteroche\vendor\model\CommentManager;
use forteroche\vendor\model\MemberManager;

class PostsController extends ApplicationComponent
{
    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';
    protected $postManager = null;
    protected $commentManager = null;
    protected $memberManager = null;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->postManager = new PostManager(PDOFactory::getMysqlConnexion());
        $this->commentManager = new CommentManager(PDOFactory::getMysqlConnexion());
        $this->memberManager = new MemberManager(PDOFactory::getMysqlConnexion());
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

        /*if (empty($_SESSION)) {
            $this->defineUser();
        //}*/

        $this->$method($this->app->httpRequest());
    }

    public function defineUser() {  
        $auth = $this->app->httpRequest()->cookieData('auth');
        $userId = $this->app->httpRequest()->cookieData('userId');
        $pseudo = $this->app->httpRequest()->cookieData('pseudo');
        $member = $this->memberManager->getSingle($userId);

        var_dump($auth);
        var_dump($userId);
        var_dump($pseudo);
        var_dump($member);
        var_dump($_COOKIE);

        if ($auth === 'true' AND $userId !== null AND $pseudo !== null AND $member !== null) {
            if ($pseudo === $member->pseudo()) {
                $this->app->user()->setAuthenticated(true);
                $this->app->user()->setAttribute('id', $userId);
                $this->app->user()->setAttribute('pseudo', $pseudo);
                $this->app->user()->setAttribute('privilege', $member->privilege());
            }
        }       
    }

    public function executeIndex(HTTPRequest $request)
    {
        // Insérer redirection vers index-1 si url ='/'
        $nbPosts = 5;
        $nbPages = (int)ceil($this->postManager->count() / $nbPosts);//Arrondi au nombre entier supérieur
        $this->page->addVars('nbPages', $nbPages);

        $index = (int)$request->getData('index');
    
        if ($index === null OR $index === 0) {
            $index = 1;
        }

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
            $begin = ($index - 1) * $nbPosts;
        }

        $this->page->addVars('index', $index);  
        $this->page->addVars('prevIndex', $prevIndex);
        $this->page->addVars('nextIndex', $nextIndex);

        $postsList = $this->postManager->getList($begin, $nbPosts);
        if ($request->getData('index') !== null AND empty($postsList)) {
            $this->app->httpResponse()->redirect404();
        }
        $this->page->addVars('postsList', $postsList);

        $nbComments = [];
        foreach ($postsList as $post) {
            $filters['postId'] = $post->id();
            $nbComments[$post->id()] = $this->commentManager->count($filters);
        }
        $this->page->addVars('nbComments', $nbComments);

        $this->page->setTabTitle('Accueil');
        $this->page->setActiveNav('home');
        
        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeShow(HTTPRequest $request)
    {
        $id = (int)$request->getData('id');
        $post = $this->postManager->getSingle($id);
        if (empty($post)) {
            $this->app->httpResponse()->redirect404();
        }
        $comments = $this->commentManager->getByPost($id);
        
        $members = [];
        foreach ($comments as $comment ) {
            $members[$comment->id()] = $this->memberManager->getSingle($comment->memberId());
        }

        $nbPosts = $this->postManager->count();
        $nbTab = 4;
        $dotBefore = false;
        $dotAfter = false;
        
        if ($id === 1) {
            $prevPost = 'post-' . ($id + 1);
            $nextPost = '#';
            $begin = $nbTab + 1;
            $end = 1;
            $dotBefore = true;
        } elseif ($id === $nbPosts) {
            $prevPost = '#';
            $nextPost = 'post-' . ($id - 1);
            $begin = $nbPosts ;
            $end = $nbPosts - $nbTab - 1;
            $dotAfter = true;
        } else {
            if ($id <= $nbTab / 2 ) {
                $begin = $nbTab + 1;
                $end = 1;
                $dotBefore = true;
            } elseif ($id >= $nbPosts - $nbTab / 2) {
                $begin = $nbPosts ;
                $end = $nbPosts - $nbTab - 1;
                $dotAfter = true;
            } else {
                $dotBefore = true;
                $dotAfter = true;
                $begin = $id + (int)($nbTab / 2);
                $end = $id - (int)($nbTab / 2);
            }
            $prevPost = 'post-' . ($id + 1);
            $nextPost = 'post-' . ($id - 1);
        }

        $this->page->addVars('post', $post);
        $this->page->addVars('comments', $comments);
        $this->page->addVars('members', $members);

        $this->page->addVars('prevPost', $prevPost);
        $this->page->addVars('nextPost', $nextPost);
        $this->page->addVars('begin', $begin);
        $this->page->addVars('end', $end);
        $this->page->addVars('dotBefore', $dotBefore);
        $this->page->addVars('dotAfter', $dotAfter);

        $this->page->setTabTitle($post->title());

        $this->page->setContent(__DIR__.'/view/single.php');
        $this->page->generate();
    }

    public function executeRedaction(HTTPRequest $request)
    {
        if ($this->app()->user()->isAdmin() AND $request->postExists('title') AND $request->postExists('content')) {
            $memberId = (int)$this->app->user()->getAttribute('id');
            $title = (int)$request->getData('title');
            $content = $request->postData('content');
            var_dump($memberId);
            var_dump($title);
            var_dump($content);
        }

        $this->page->setTabTitle('Redaction');
        $this->page->setActiveNav('redaction');

        $this->page->setContent(__DIR__.'/view/redaction.php');
        $this->page->generate();
        
    }
}
