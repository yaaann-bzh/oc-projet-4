<?php 
if ((int)$user->getAttribute('id') === (int)$comment->memberId() OR $user->isAdmin()) { ?>
    <p class="mt-2 mb-0"><a href="/comment-<?= $comment->id(); ?>">Modifier/Supprimer</a></p>
<?php 
} elseif ($user->isAuthenticated()) { ?>
    <p class="mt-2 mb-0"><a href="/comment-report-<?= $comment->id(); ?>">Signaler</a></p> 
<?php
} ?>                      
