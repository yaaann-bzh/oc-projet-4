<?php
namespace forteroche\app\modules\posts;

use framework\HTTPrequest;
use framework\Application;
use framework\ApplicationComponent;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
use forteroche\vendor\model\PostManager;


class PostsController extends ApplicationComponent
{
    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';
    protected $manager = null;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->manager = new PostManager(PDOFactory::getMysqlConnexion());
        $this->page = new Page;
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
        $nbPosts = 10;
        
        $postsList = $this->manager->getList(0, $nbPosts);
        $this->page->addVars('postsList', $postsList);
        $this->page->setTabTitle('Accueil');
        $tabTitle = $this->page->getTabTitle();
        $this->page->addVars('tabTitle', $tabTitle);
        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeShow(HTTPRequest $request)
    {
        
    }
}
