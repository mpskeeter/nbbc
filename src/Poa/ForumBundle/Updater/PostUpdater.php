<?php

namespace Poa\ForumBundle\Updater;

use Poa\ForumBundle\Entity\Post;

class PostUpdater
{

    public function __construct()
    {
    }

    public function update(Post $post)
    {
		$post->setModifiedAuthor($this->get('security.context')->getToken()->getUser());
    }
}
