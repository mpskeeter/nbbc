<?php

namespace Poa\ForumBundle\Updater;

use Poa\ForumBundle\Entity\Category;
use Poa\ForumBundle\Entity\TopicRepositoryInterface;

class CategoryUpdater
{
    protected $topicRepository;

    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function update(Category $category)
    {
        $topics = $this->topicRepository->findAllByCategory($category, false);
        $category->setNumTopics(count($topics));
        $numPosts = 0;
        $lastPost = $lastTopic = null;
        foreach ($topics as $topic) {
            $numPosts += $topic->getNumPosts();
            $topicLastPost = $topic->getLastPost();
            if ($topicLastPost->isPosteriorTo($lastPost)) {
                $lastPost = $topicLastPost;
                $lastTopic = $topic;
            }
        }
        $category->setNumPosts($numPosts);
        $category->setLastPost($lastPost);
        $category->setLastTopic($lastTopic);
    }
}
