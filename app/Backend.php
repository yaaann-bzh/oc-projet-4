<?php
namespace forteroche\app;

use framework\Application;
use forteroche\app\modules\connexion\ConnexionController;

class Backend extends Application 
{
    protected $name = 'Backend';

    public function run()
    {
        if ($this->user->isAuthenticated())
        {
            $controller = $this->getController();
        }
        else
        {
            $controller = new ConnexionController($this, 'connexion', 'index');
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
