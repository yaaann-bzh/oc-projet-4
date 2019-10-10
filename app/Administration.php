<?php
namespace forteroche\app;

use framework\Application;
use forteroche\app\modules\connexion\ConnexionController;

class Administration extends Application 
{
    protected $name = 'Administration';

    public function run()
    {
        if ($this->user->isAuthenticated())
        {
            if ($this->user->isAdmin()) {
                $controller = $this->getController();
            } else {
                $this->httpResponse->redirect403();
            }
        }
        else
        {
            $controller = new modules\connexion\ConnexionController($this, 'connexion', 'index');
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
