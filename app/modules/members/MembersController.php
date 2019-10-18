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
}