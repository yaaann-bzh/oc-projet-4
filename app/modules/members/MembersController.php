<?php
namespace forteroche\app\modules\members;

use framework\HTTPrequest;
use framework\Application;
use framework\Controller;
use framework\Manager;
use framework\Page;
use forteroche\vendor\entity\Member;

class MembersController extends Controller
{
    public function executeInscription(HTTPRequest $request)
    {
        $inputs = array(
            'firstname' => '',
            'lastname' => '',
            'pseudo' => '',
            'email' => '',
            'pass' => '',
            'confirm' => '',
            'agree' => '',
        );
        $nbRequiredInputs = count($inputs);
        $errors = [];

        foreach ($inputs as $key => $input) {
            if ($request->postExists($key) AND !empty($request->postData($key))) {
                $inputs[$key] = $request->postData($key);
            }
            else {
                unset($inputs[$key]);
            }
        }
        
        if ($request->postExists('submit')) {       
            if (count($inputs) === $nbRequiredInputs) {

                if ($inputs['pass'] !== $inputs['confirm']) {
                    $errors['pass_same'] = 'Les mots de passe doivent être identiques';
                }
      
                if (preg_match('#((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)).{8,}#', $inputs['pass']) === 0) {
                    $errors['pass_secu'] = 'Votre mot de passe doit respecter les critères de sécurité';
                }

                if ((int)$this->memberManager->getId($inputs['pseudo']) !== 0) {
                    $errors['pseudo'] = 'Le pseudo choisi existe déjà. Merci d\'en saisir un nouveau';
                }
        
                if ((int)$this->memberManager->getId($inputs['email']) !== 0) {
                    $errors['email'] = 'Il existe déjà un compte associé à cette adresse mail.';
                }

                try {
                    $inputs['firstname'] = ucfirst(strtolower($inputs['firstname']));
                    $inputs['lastname'] = ucfirst(strtolower($inputs['lastname']));
                    $inputs['pass'] = password_hash($inputs['pass'], PASSWORD_DEFAULT);
    
                    $member = new Member($inputs);
        
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }
            else {
                $errors[] = 'Tous les champs de ce formulaire sont requis';
            }
            
            if (empty($errors)) {             
                $this->memberManager->add($member);
                return $this->app->httpResponse()->redirect('/user');
            }
        }

        $this->page->addVars('inputs', $inputs);
        $this->page->addVars('errors', $errors);

        $this->page->setTabTitle('Inscription');
    
        $this->page->setContent(__DIR__.'/view/index.php');
        $this->page->generate();
    }

    public function executeShow(HTTPRequest $request)
    {
        $memberId = (int)$request->getData('member');
        $updated = $request->getData('updated');
        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            return $this->app->httpResponse()->redirect404();
        }

        $userId = (int)$this->app->user()->getAttribute('id');
        $privilege = $this->app->user()->getAttribute('privilege');

        if ($userId !== (int)$member->id()) {
            if ($privilege === null) {
                return $this->app->httpResponse()->redirect403();
            }
        }

        if ($request->postExists('action')) {
            switch ($request->postData('action')) { 
                case 'Modifier': 
                    return $this->app->httpResponse()->redirect('/user/update-' . $member->id());
                break;

                case 'Supprimer': 
                    return $this->app->httpResponse()->redirect('/user/delete-' . $member->id());
                break;
            }
        }
        
        $postsFilter['authorId'] = ' = ' . $member->id();
        $activity['posts'] = $this->postManager->count($postsFilter);
        $commentsFilter['memberId'] = ' = ' . $member->id();
        $activity['comments'] = $this->commentManager->count($commentsFilter);

        $this->page->addVars('member', $member);
        $this->page->addvars('updated', $updated);
        $this->page->addvars('activity', $activity);

        $this->page->setTabTitle(htmlspecialchars($member->pseudo()));

        $this->page->setContent(__DIR__.'/view/profile.php');
        $this->page->generate();
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $memberId = (int)$request->getData('member');
        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            return $this->app->httpResponse()->redirect404();
        }

        $userId = (int)$this->app->user()->getAttribute('id');
        $privilege = $this->app->user()->getAttribute('privilege');

        if ($userId !== (int)$member->id()) {
            if ($privilege === null) {
                return $this->app->httpResponse()->redirect403();
            }
        }

        $inputs = array(
            'firstname' => '',
            'lastname' => '',
            'pseudo' => '',
            'email' => '',
            'pass' => '',
        );
        $nbRequiredInputs = count($inputs);
        $errors = [];

        foreach ($inputs as $key => $input) {
            if ($request->postExists($key) AND !empty($request->postData($key))) {
                $inputs[$key] = $request->postData($key);
            }
            else {
                unset($inputs[$key]);
            }
        }

        if ($request->postExists('submit')) {       
            if (count($inputs) === $nbRequiredInputs) {
                if (!password_verify($inputs['pass'], $member->pass())) {
                    $errors[] = 'Mot de passe incorrect';
                }

                if ($inputs['pseudo'] === $member->pseudo()) {
                    unset($inputs['pseudo']);
                } elseif ((int)$this->memberManager->getId($inputs['pseudo']) !== 0) {
                    $errors['pseudo'] = 'Le pseudo choisi existe déjà. Merci d\'en saisir un nouveau';
                }
        
                if ($inputs['email'] === $member->email()) {
                    unset($inputs['email']);
                } elseif ((int)$this->memberManager->getId($inputs['email']) !== 0) {
                    $errors['email'] = 'Il existe déjà un compte associé à cette adresse mail.';
                }

                if (empty($errors)) {             
                    try {
                        $inputs['firstname'] = ucfirst(strtolower($inputs['firstname']));
                        $inputs['lastname'] = ucfirst(strtolower($inputs['lastname']));
                        unset($inputs['pass']);
        
                        $this->memberManager->update($member->id(), $inputs);

                        if (isset($inputs['pseudo'])) {
                            $this->app->user()->setAttribute('pseudo', $inputs['pseudo']);
                        }

                        return $this->app->httpResponse()->redirect('/user/profile-' . $memberId . '-updated');
            
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    }  
                }
            }
            else {
                $errors[] = 'Tous les champs de ce formulaire sont requis';
            }
        }

        $this->page->addVars('member', $member);
        $this->page->addVars('errors', $errors);

        $this->page->setTabTitle('Modifier mon profil');
    
        $this->page->setContent(__DIR__.'/view/update-profile.php');
        $this->page->generate();
    }

    public function executePasswordUpdate(HTTPRequest $request)
    {
        $memberId = (int)$request->getData('member');
        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            return $this->app->httpResponse()->redirect404();
        }

        $userId = (int)$this->app->user()->getAttribute('id');
        $privilege = $this->app->user()->getAttribute('privilege');

        if ($userId !== (int)$member->id()) {
            if ($privilege === null) {
                return $this->app->httpResponse()->redirect403();
            }
        }

        $inputs = array(
            'pass' => '',
            'newpass' => '',
            'confirm' => '',
        );
        $nbRequiredInputs = count($inputs);
        $errors = [];

        foreach ($inputs as $key => $input) {
            if ($request->postExists($key) AND !empty($request->postData($key))) {
                $inputs[$key] = $request->postData($key);
            }
            else {
                unset($inputs[$key]);
            }
        }

        if ($request->postExists('submit')) {       
            if (count($inputs) === $nbRequiredInputs) {
                if (!password_verify($inputs['pass'], $member->pass())) {
                    $errors[] = 'Mot de passe incorrect';
                }

                if (preg_match('#((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)).{8,}#', $inputs['newpass']) === 0) {
                    $errors['pass_secu'] = 'Votre mot de passe doit respecter les critères de sécurité';
                }

                if (empty($errors)) {             
                    try {
                        unset($inputs['confirm']);
                        $inputs['pass'] = password_hash($inputs['newpass'], PASSWORD_DEFAULT);
                        unset($inputs['newpass']);

                        $this->memberManager->update($member->id(), $inputs);

                        return $this->app->httpResponse()->redirect('/user/profile-' . $memberId . '-updated');
            
                    } catch (\Exception $e) {
                        $errors[] = $e->getMessage();
                    } 
                } 
            }
            else {
                $errors[] = 'Tous les champs de ce formulaire sont requis';
            }
        }

        $this->page->addVars('member', $member);
        $this->page->addVars('errors', $errors);

        $this->page->setTabTitle('Modifier mot de passe');
    
        $this->page->setContent(__DIR__.'/view/update-password.php');
        $this->page->generate();
    }

    public function executeDelete(HTTPRequest $request)
    {
        $memberId = (int)$request->getData('member');
        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            return $this->app->httpResponse()->redirect404();
        }

        $userId = (int)$this->app->user()->getAttribute('id');
        $privilege = $this->app->user()->getAttribute('privilege');

        if ($userId !== (int)$member->id()) {
            if ($privilege === null) {
                return $this->app->httpResponse()->redirect403();
            }
        }

        $errors = [];

        if ($request->postExists('pass')) {
            if (!password_verify($request->postData('pass'), $member->pass())) {
                $errors[] = 'Mot de passe incorrect';
            }

            if (empty($errors)) {
                try {
                    $this->memberManager->delete($member->id());
                    return $this->app->httpResponse()->redirect('/deconnection');
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                } 
            }
        }

        $this->page->addVars('member', $member);
        $this->page->addVars('errors', $errors);

        $this->page->setTabTitle('Suppression de compte');
    
        $this->page->setContent(__DIR__.'/view/delete-profile.php');
        $this->page->generate();



    }
}
