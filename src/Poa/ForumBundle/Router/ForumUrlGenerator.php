<?php

namespace Poa\ForumBundle\Router;

use Poa\ForumBundle\Entity\Category;
use Poa\ForumBundle\Entity\Topic;
use Poa\ForumBundle\Entity\Post;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForumUrlGenerator
{
    protected $urlGenerator;
    protected $nbPostsPerPage;

    public function __construct(UrlGeneratorInterface $urlGenerator, $nbPostsPerPage)
    {
        $this->urlGenerator = $urlGenerator;
        $this->nbPostsPerPage = $nbPostsPerPage;
    }

    public function urlForCategory(Category $category, $absolute = false)
    {
        return $this->urlGenerator->generate('forum_category_show', array(
            'slug' => $category->getSlug()
        ), $absolute);
    }

    public function urlForCategoryAtomFeed(Category $category, $absolute = false)
    {
        return $this->urlGenerator->generate('forum_category_show', array(
            'slug'      => $category->getSlug(),
            '_format'   =>  'xml'
        ), $absolute);
    }

    public function urlForTopic(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->generate('forum_topic_show', array(
            'categorySlug'  => $topic->getCategory()->getSlug(),
            'slug'          => $topic->getSlug()
        ), $absolute);
    }

    public function urlForTopicAtomFeed(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->generate('forum_topic_show', array(
            'categorySlug'  => $topic->getCategory()->getSlug(),
            'slug'          => $topic->getSlug(),
            '_format'       => 'xml'
        ), $absolute);
    }

    public function urlForTopicReply(Topic $topic, $absolute = false)
    {
        return $this->urlGenerator->generate('forum_topic_post_new', array(
            'categorySlug'  => $topic->getCategory()->getSlug(),
            'slug'          => $topic->getSlug()
        ));
    }

    public function urlForPost(Post $post, $absolute = false)
    {
        $topicUrl = $this->urlForTopic($post->getTopic(), $absolute);
        $topicPage = ceil($post->getNumber() / $this->nbPostsPerPage);

        return sprintf('%s?page=%d#%d', $topicUrl, $topicPage, $post->getNumber());
    }

	public function urlForPostEdit(Post $post, $absolute = false)
	{
		return $this->urlGenerator->generate('forum_post_edit_get',
											 array('id' => $post->getId()),
											 $absolute);
	}


    public function getTopicNumPages(Topic $topic)
    {
        return ceil($topic->getNumPosts() / $this->nbPostsPerPage);
    }
}
