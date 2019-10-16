<?php
namespace forteroche\app\modules\comments;

use framework\HTTPrequest;
use framework\Application;
use framework\Controller;
use framework\Manager;
use framework\Page;
use forteroche\vendor\entity\Comment;

class CommentsController extends Controller 
{
    public function executeIndex(HTTPRequest $request)
    {
        $nbComments = $this->app->config()->get('nb_comments');
        $filters['removed'] = '=' . 0;

        $nbPages = (int)ceil($this->commentManager->count($filters) / $nbComments);//Arrondi au nombre entier supérieur
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

        $comments = $this->commentManager->getList($begin, $nbComments, $filters);
        if ($index !== 1 AND empty($comments)) {
            return $this->app->httpResponse()->redirect404();
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
        $nbComments = $this->app->config()->get('nb_comments');
        $memberId = (int)$request->getData('member');
        $index = (int)$request->getData('index');

        $member = $this->memberManager->getSingle($memberId);
        if (empty($member)) {
            return $this->app->httpResponse()->redirect404();
        }
        $this->page->addVars('member', $member);

        $filters['memberId'] = '=' . $member->id();
        $filters['removed'] = '=' . 0;

        $nbPages = (int)ceil($this->commentManager->count($filters) / $nbComments);//Arrondi au nombre entier supérieur
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

        $comments = $this->commentManager->getList($begin, $nbComments, $filters);
        if ($index !== 1 AND empty($comments)) {
            return $this->app->httpResponse()->redirect404();
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

                return $this->app->httpResponse()->redirect('/post-' . $postId . '#comments');

            } catch (\Exception $e) {
                $intro = 'Erreur lors de l\'ajout du commentaire';
                $message = $e->getMessage();
            }

            $this->errorPage($intro, $message);            
        }
    }

    public function executeShow(HTTPRequest $request)
    {
        $commentId = (int)$request->getData('comment');
        $updated = $request->getData('updated');
        $comment = $this->commentManager->getSingle($commentId);
        if (empty($comment)) {
            return $this->app->httpResponse()->redirect404();
        } 

        if ($request->postExists('action')) {
            try {
                switch ($request->postData('action')) { 
                    case 'Modifier': 
                        $content = $request->postData('content');
                        $this->commentManager->update($comment->id(), $content);
                        $suffixe = '-updated';
                    break;

                    case 'Supprimer': 
                        $this->commentManager->delete($commentId);
                        $this->reportManager->clear($commentId);
                        $this->commentManager->clearReports($commentId);
                        $suffixe = '';
                    break;
                }

                return $this->app->httpResponse()->redirect('/user/comment-' . $commentId . $suffixe);

            } catch (\Exception $e) {
                $intro = 'Erreur lors de la modification du commentaire';
                $message = $e->getMessage();
            }

            $this->errorPage($intro, $message);
        }

        $member = $this->memberManager->getSingle($comment->memberId());
        $userId = (int)$this->app->user()->getAttribute('id');
        $privilege = $this->app->user()->getAttribute('privilege');

        if ($userId !== (int)$member->id()) {
            if ($privilege === null) {
                return $this->app->httpResponse()->redirect403();
            }
        }

        $post = $this->postManager->getSingle($comment->postId());
        
        $this->page->addVars('comment', $comment);
        $this->page->addVars('member', $member);
        $this->page->addVars('post', $post);
        $this->page->addvars('updated', $updated);

        $this->page->setTabTitle('Modification commentaire');

        $this->page->setContent(__DIR__.'/view/single.php');
        $this->page->generate();
    }
}
