<?php

namespace Poa\ForumBundle\Controller;

use Poa\ForumBundle\Form\NewTopicForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Poa\ForumBundle\Entity\Topic;
use Poa\ForumBundle\Entity\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TopicController extends Controller
{
    public function newAction(Category $category = null)
    {
        $form = $this->get('forum.form.new_topic');
        $topic = $this->get('forum.repository.topic')->createNewTopic();
        if ($category) {
            $topic->setCategory($category);
        }
        $form->setData($topic);

        $template = sprintf('%s:new.html.%s', $this->container->getParameter('forum.templating.location.topic'), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'form'      => $form->createView(),
            'category'  => $category
        ));
    }

    public function createAction(Category $category = null)
    {
        $form = $this->get('forum.form.new_topic');
//        $form->bindRequest($this->get('request'));
		$form->bind($this->get('request'));
        $topic = $form->getData();

        if (!$form->isValid()) {
            $template = sprintf('%s:new.html.%s', $this->container->getParameter('forum.templating.location.topic'), $this->getRenderer());
            return $this->get('templating')->renderResponse('PoaForumBundle:Topic:new.html.'.$this->getRenderer(), array(
                'form'      => $form->createView(),
                'category'  => $category
            ));
        }

        $this->get('forum.creator.topic')->create($topic);
        $this->get('forum.blamer.topic')->blame($topic);

        $this->get('forum.creator.post')->create($topic->getFirstPost());
        $this->get('forum.blamer.post')->blame($topic->getFirstPost());

        $objectManager = $this->get('forum.object_manager');
        $objectManager->persist($topic);
        $objectManager->persist($topic->getFirstPost());
        $objectManager->flush();

        $this->get('session')->setFlash('forum_topic_create/success', true);
        $url = $this->get('forum.router.url_generator')->urlForTopic($topic);

        return new RedirectResponse($url);
    }

    public function listAction($categorySlug, array $pagerOptions)
    {
        if (null === $categorySlug) {
            $category = null;
            $topics   = $this->get('forum.repository.topic')->findAll(true);
        } else {
            $category = $this->findCategoryOr404($categorySlug);
            $topics   = $this->get('forum.repository.topic')->findAllByCategory($category, true);
        }

        $topics->setCurrentPage($pagerOptions['page']);
        $topics->setMaxPerPage($this->container->getParameter('forum.paginator.topics_per_page'));

        $template = sprintf('%s:list.%s.%s', $this->container->getParameter('forum.templating.location.topic'), $this->get('request')->getRequestFormat(), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'topics'    => $topics,
            'category'  => $category,
            'pagerOptions' => $pagerOptions
        ));
    }

    public function showAction($categorySlug, $slug)
    {
        $topic = $this->findTopic($categorySlug, $slug);
        $this->get('forum.repository.topic')->incrementTopicNumViews($topic);

        if ('html' === $this->get('request')->getRequestFormat()) {
            $page = $this->get('request')->query->get('page', 1);
            $posts = $this->get('forum.repository.post')->findAllByTopic($topic, true);
            $posts->setCurrentPage($page);
            $posts->setMaxPerPage($this->container->getParameter('forum.paginator.posts_per_page'));
        } else {
            $posts = $this->get('forum.repository.post')->findRecentByTopic($topic, 30);
        }

        $template = sprintf('%s:show.%s.%s', $this->container->getParameter('forum.templating.location.topic'), $this->get('request')->getRequestFormat(), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'topic' => $topic,
            'posts' => $posts
        ));
    }

    public function postNewAction($categorySlug, $slug)
    {
        return $this->forward('PoaForumBundle:Post:new', array(
            'categorySlug'  => $categorySlug,
            'slug'          => $slug
        ));
    }

    public function postCreateAction($categorySlug, $slug)
    {
        return $this->forward('PoaForumBundle:Post:create', array(
            'categorySlug' => $categorySlug,
            'slug'         => $slug
        ));
    }

    public function deleteAction($id)
    {
        $topic = $this->get('forum.repository.topic')->find($id);
        if (!$topic) {
            throw new NotFoundHttpException(sprintf('No topic found with id "%s"', $id));
        }

        $this->get('forum.remover.topic')->remove($topic);
        $this->get('forum.object_manager')->flush();

        return new RedirectResponse($this->get('forum.router.url_generator')->urlForCategory($topic->getCategory()));
    }

    protected function getRenderer()
    {
        return $this->container->getParameter('forum.templating.engine');
    }

    /**
     * Find a topic by its category slug and topic slug
     *
     * @return Topic
     **/
    public function findTopic($categorySlug, $topicSlug)
    {
        $category = $this->get('forum.repository.category')->findOneBySlug($categorySlug);
        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category with slug "%s" does not exist', $categorySlug));
        }
        $topic = $this->get('forum.repository.topic')->findOneByCategoryAndSlug($category, $topicSlug);
        if (!$topic) {
            throw new NotFoundHttpException(sprintf('The topic with slug "%s" does not exist', $topicSlug));
        }

        return $topic;
    }

    /**
     * Finds the category having the specified slug or throws a 404 exception
     *
     * @param  string $slug
     *
     * @return Category
     * @throws NotFoundHttpException
     */
    protected function findCategoryOr404($slug)
    {
        $category = $this
            ->get('forum.repository.category')
            ->findOneBySlug($slug)
        ;

        if (!$category) {
            throw new NotFoundHttpException(sprintf(
                'The category with slug "%s" was not found.',
                $slug
            ));
        }

        return $category;
    }
}
