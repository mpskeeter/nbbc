<?php

namespace Poa\ForumBundle\Creator;

use Poa\ForumBundle\Entity\Topic;
use DateTime;
use LogicException;

class TopicCreator
{
    public function create(Topic $topic)
    {
        if (!$category = $topic->getCategory()) {
            throw new LogicException('Each topic must have a category');
        }

        $category->incrementNumTopics();
        $category->setLastTopic($topic);
    }
}
