<?php

namespace Poa\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends Controller
{
    public function indexAction()
    {
//		$user = $this->container->get('fos_user.user_manager')
//			->findUserByUsername('mpskeeter');

//		var_dump($user);
//		die;
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in MainController.
         *
         */
		$routeName = $this->getRequest()->get('_route');

//		ladybug_dump('Route Name: ' . $routeName);

		/** @var $routerRepository \Poa\MainBundle\Entity\MenuRepository */
		$routerRepository = $this
			->getDoctrine()
			->getManager()
			->getRepository('PoaMainBundle:Menu');

		$routeId = $routerRepository->getIdForRoute($routeName);

//		ladybug_dump($routeId);

		/** @var $em \Poa\MainBundle\Entity\ContentRepository */
		$em = $this->getDoctrine()->getRepository('PoaMainBundle:Content');
		$content = $em->getNonExpiredMenuForParent($routeId);
//		foreach($content as $content_row)
//			ladybug_dump($content_row->getText());
        return $this->render('PoaMainBundle:Content:index.html.twig',array('content' => $content));
    }

	public function downloadAction($filename)
	{
		$request = $this->get('request');
		$path = $this->get('kernel')->getRootDir(). "/../web/downloads/";
		$content = file_get_contents($path.$filename);

		$response = new Response();

		//set headers
		$response->headers->set('Cache-Control', 'private');
		$response->headers->set('Content-Type', 'application/pdf');
		$response->headers->set('Content-Disposition', 'attachment; filename="'.$filename);
		$response->headers->set('Content-length', filesize($path.$filename));

		$response->sendHeaders();

		$response->setContent($content);
		return $response;
	}
}
