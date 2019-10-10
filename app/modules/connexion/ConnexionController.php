<?php
namespace forteroche\app\modules\connexion;

use framework\HTTPrequest;
use framework\Application;
use framework\Controller;
use framework\Manager;
use framework\PDOFactory;
use framework\Page;
//use forteroche\vendor\model\MemberManager;

class ConnexionController extends Controller
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->setTabTitle('Connection');
        $this->page->setActiveNav('connect');

        if ($request->postExists('login') AND $request->postExists('password')) {
            $login = $request->postData('login');
            $password = $request->postData('password');
            $member = $this->memberManager->getSingle($this->memberManager->getId($login));

            if ($member !== null) {
                if (password_verify($password, $member->pass())) {
                    $this->app->user()->setAuthenticated(true);
                    $this->app->user()->setAttribute('id', $member->id());
                    $this->app->user()->setAttribute('pseudo', $member->pseudo());
                    $this->app->user()->setAttribute('privilege', $member->privilege());

                    if ($request->postData('remember') !== null) {
                        $connexionId = uniqid('', true);
                        $this->app->httpResponse()->setCookie('auth', $connexionId, time() + 31*24*3600);
                        $member->setConnexionId($connexionId);
                        $this->memberManager->saveConnexionId($member->id(), $connexionId);         
                    }

                    $this->app->httpResponse()->redirect('/');

                } else {
                    $this->page->addVars('invalid', true);
                }
            } else {
                $this->page->addVars('invalid', true);
            }
        }

        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeDisconnect(HTTPRequest $request)
    {
        $this->app->user()->setAuthenticated(false);
        // Suppression des variables de session et de la session
        $_SESSION = array();
        session_destroy();

        // Suppression des cookies de connexion automatique
        $this->app->httpResponse()->setcookie('auth', '');
        $this->app->httpResponse()->setcookie('userId', '');
        $this->app->httpResponse()->setcookie('pseudo', '');

        $this->app->httpResponse()->redirect('/');

    }
}
