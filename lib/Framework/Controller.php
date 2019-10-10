<?php
namespace framework;
   
use framework\HTTPrequest;
use framework\Application;
use framework\Controller;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
use forteroche\vendor\model\PostManager;
use forteroche\vendor\model\CommentManager;
use forteroche\vendor\model\MemberManager;
use forteroche\vendor\model\ReportManager;
//use forteroche\vendor\entity\Post;

class Controller extends ApplicationComponent
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

        if (empty($_SESSION)) {
            $this->defineUser();
        }

        $this->$method($this->app->httpRequest());
    }
    
    public function defineUser() {  
        $auth = $this->app->httpRequest()->cookieData('auth');

        if ($auth !== null) {
            $member = $this->memberManager->checkConnexionId($auth);
            if ($member !== null) {

                $this->app->user()->setAuthenticated(true);
                $this->app->user()->setAttribute('id', $member->id());
                $this->app->user()->setAttribute('pseudo', $member->pseudo());
                $this->app->user()->setAttribute('privilege', $member->privilege());
            }
        }  
    }
}