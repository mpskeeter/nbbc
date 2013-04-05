<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1',
		                                         '10.0.1.173',
		                                         '75.138.196.183',
		                                         '2001:470:8:92e:5940:ab27:6957:776d',
		                                         '2001:470:8:92e:9867:4b0d:c480:df77',
		                                         '2001:470:8:92e:d4a4:6c0c:e5a4:c7dc',
		                                         '2001:470:8:92e:fcaa:2755:b138:e267',
		                                         '2001:470:8:92e:2543:9705:7b:565c',
                                                 '2001:470:8:92e:e8c2:bdc6:f1a2:b841',
		                                         '2001:470:8:92e:1097:2b00:8011:8bd6',
		                                         '2001:470:8:92e:3dc1:bbea:3758:ecdb',
                                                 '2001:470:8:92e:4c74:b489:5bd6:7bd7',
		                                         '2001:470:8:92e:5150:4a4f:a53d:4610',
		                                         'fe80::1',
		                                         '::1'))
	|| strpos(@$_SERVER['REMOTE_ADDR'],'2001:470:8:92e:') === FALSE
) {
    header('HTTP/1.0 403 Forbidden');
	echo 'Host: ' . $_SERVER['REMOTE_ADDR']."<br/>\n";
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
