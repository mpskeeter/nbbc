<?php

namespace Poa\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
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
        return $this->render('PoaMainBundle:Welcome:index.html.twig');
    }
}
