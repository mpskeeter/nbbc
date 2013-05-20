<?php

namespace Poa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function listAction()
    {
        $categories = $this->get('forum.repository.category')->findAll();
        
        $template = sprintf('%s:list.html.%s', $this->container->getParameter('forum.templating.location.category'), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array('categories' => $categories));
    }

    public function showAction($slug)
    {
        $category = $this->get('forum.repository.category')->findOneBySlug($slug);

        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category %s does not exist.', $slug));
        }

        $template = sprintf('%s:show.%s.%s', $this->container->getParameter('forum.templating.location.category'), $this->get('request')->getRequestFormat(), $this->getRenderer());
        return $this->get('templating')->renderResponse($template, array(
            'category'  => $category,
            'page'      => $this->get('request')->query->get('page', 1)
        ));
    }

    public function topicNewAction($slug)
    {
        $category = $this->get('forum.repository.category')->findOneBySlug($slug);

        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category "%s" does not exist.', $slug));
        }

        return $this->forward('PoaForumBundle:Topic:new', array('category' => $category));
    }

    public function topicCreateAction($slug)
    {
        $category = $this->get('forum.repository.category')->findOneBySlug($slug);

        if (!$category) {
            throw new NotFoundHttpException(sprintf('The category "%s" does not exist.', $slug));
        }

        return $this->forward('PoaForumBundle:Topic:create', array('category' => $category));
    }

    protected function getRenderer()
    {
        return $this->container->getParameter('forum.templating.engine');
    }
}
