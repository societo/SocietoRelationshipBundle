<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\Resource\FileResource;

$collection = new RouteCollection();
$collection->addCollection($loader->import($_SERVER['SYMFONY__KERNEL__ROOT_DIR'].'/config/routing.yml'));

$collection->add('profile', new Route('/member/{member}', array(
    'member' => null,
)));

$controller = '\Societo\BaseBundle\Test\CsrfTestController::renderAction';
$collection->add('csrf', new Route('/test/csrf', array(
    '_controller' => $controller,
)));

return $collection;
