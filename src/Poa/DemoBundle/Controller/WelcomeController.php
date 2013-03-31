<?php

namespace Poa\DemoBundle\Controller;

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
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        return $this->render('PoaDemoBundle:Welcome:index.html.twig');
    }
}
