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
        $nbPosts = 5;
        $nbPages = (int)ceil($this->manager->count() / $nbPosts);//Arrondi au nombre entier supérieur
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

        $postsList = $this->manager->getList($begin, $nbPosts);
        $this->page->addVars('postsList', $postsList);

        $this->page->setTabTitle('Accueil');
        $tabTitle = $this->page->getTabTitle();
        $this->page->addVars('tabTitle', $tabTitle);

        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeShow(HTTPRequest $request)
    {
        $id = (int)$request->getData('id');
        $post = $this->manager->getSingle($id);
        $nbPosts = $this->manager->count();
        $nbTab = 4;
        $dotBefore = false;
        $dotAfter = false;
        
        if ($id === 1) {
            $prevPost = 'post-' . ($id + 1);
            $nextPost = '#';
            $begin = $nbTab + 2;
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
                $begin = $nbTab + 2;
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
        $this->page->addVars('prevPost', $prevPost);
        $this->page->addVars('nextPost', $nextPost);
        $this->page->addVars('begin', $begin);
        $this->page->addVars('end', $end);
        $this->page->addVars('dotBefore', $dotBefore);
        $this->page->addVars('dotAfter', $dotAfter);

        $this->page->setTabTitle($post->title());
        $tabTitle = $this->page->getTabTitle();
        $this->page->addVars('tabTitle', $tabTitle);

        $this->page->setContent(__DIR__.'/view/single.php');
        $this->page->generate();
    }
}
