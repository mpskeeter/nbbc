<?php

namespace Poa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Poa\ForumBundle\Entity\Topic;
use Poa\ForumBundle\Entity\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostController extends Controller
{
    public function newAction($categorySlug, $slug)
    {
        $topic    = $this->findTopicOr404($categorySlug, $slug);
        $form     = $this->get('forum.form.post');
        $template = sprintf(
            '%s:new.html.%s',
            $this->container->getParameter('forum.templating.location.post'),
            $this->getRenderer()
        );

        return $this->render(
            $template,
            array(
                'form'  => $form->createView(),
                'topic' => $topic,
            )
        );
    }

    public function createAction($categorySlug, $slug)
    {
        $topic = $this->findTopicOr404($categorySlug, $slug);
        $form  = $this->get('forum.form.post');
        $post  = $this->get('forum.repository.post')->createNewPost();
        $post->setTopic($topic);
//        $form->bindRequest($this->get('request'));
		$form->bind($this->get('request'));

        if (!$form->isValid()) {
            $template = sprintf('%s:new.html.%s', $this->container->getParameter('forum.templating.location.post'), $this->getRenderer());
            return $this->get('templating')->renderResponse('PoaForumBundle:Post:new.html.'.$this->getRenderer(), array(
                'form'  => $form->createView(),
                'topic' => $topic,
            ));
        }

        $post = $form->getData();
        $post->setTopic($topic);
        $this->get('forum.creator.post')->create($post);
        $this->get('forum.blamer.post')->blame($post);

        $objectManager = $this->get('forum.object_manager');
        $objectManager->persist($post);
        $objectManager->flush();

        $this->get('session')->setFlash('forum_post_create/success', true);
        $url = $this->get('forum.router.url_generator')->urlForPost($post);

        return new RedirectResponse($url);
    }

//	public function editGetAction($id)
//	{
//		$post = $this->get('forum.repository.post')->findOneById($id);
//		if (!$post) {
//			throw new NotFoundHttpException(sprintf('No post found with id "%s"', $id));
//		}
//
////		$form     = $this->get('forum.form.post');
//		$form = $this->createForm(new PostFormType(), $post);
//		$template = sprintf(
//			'%s:edit.html.%s',
//			$this->container->getParameter('forum.templating.location.post'),
//			$this->getRenderer()
//		);
//
//		return $this->render(
//			$template,
//			array(
//				'form' => $form->createView(),
//				'post' => $post,
//			)
//		);
//	}
//
//	public function editPostAction($id)
//	{
//		$post = $this->get('forum.repository.post')->findOneById($id);
//		if (!$post) {
//			throw new NotFoundHttpException(sprintf('No post found with id "%s"', $id));
//		}
//
//		$form  = $this->get('forum.form.post');
//		$form->bind($this->get('request'));
//
//		$post = $form->getData();
//
//		if (!$form->isValid()) {
//			$template = sprintf('%s:new.html.%s', $this->container->getParameter('forum.templating.location.post'), $this->getRenderer());
//			return $this->get('templating')->renderResponse('PoaForumBundle:Post:edit.html.'.$this->getRenderer(), array(
//				'form'  => $form->createView(),
//				'post' => $post,
//			));
//		}
//
//		$this->get('forum.object_manager')->flush();
//
//		$objectManager = $this->get('forum.object_manager');
//		$objectManager->persist($post);
//		$objectManager->flush();
//
//		$this->get('session')->setFlash('forum_post_create/success', true);
//		$url = $this->get('forum.router.url_generator')->urlForPost($post);
//
//		return new RedirectResponse($url);
//	}

	public function deleteAction($id)
    {
        $post = $this->get('forum.repository.post')->find($id);
        if (!$post) {
            throw new NotFoundHttpException(sprintf('No post found with id "%s"', $id));
        }

        $precedentPost = $this->get('forum.repository.post')->getPostBefore($post);
        $this->get('forum.remover.post')->remove($post);
        $this->get('forum.object_manager')->flush();

        return new RedirectResponse($this->get('forum.router.url_generator')->urlForPost($precedentPost));
    }

    protected function findTopicOr404($categorySlug, $slug)
    {
        $category = $this
            ->get('forum.repository.category')
            ->findOneBySlug($categorySlug)
        ;

        if (null === $category) {
            throw new NotFoundHttpException(sprintf(
                'The category with slug "%s" was not found.',
                $categorySlug
            ));
        }

        $topic = $this
            ->get('forum.repository.topic')
            ->findOneByCategoryAndSlug($category, $slug)
        ;

        if (null === $topic) {
            throw new NotFoundHttpException(sprintf(
                'The topic with slug "%s" was not found in category with slug "%s".',
                $slug,
                $categorySlug
            ));
        }

        return $topic;
    }

    protected function getRenderer()
    {
        return $this->container->getParameter('forum.templating.engine');
    }
}
