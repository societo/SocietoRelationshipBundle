<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\RelationshipBundle\Tests\Controller;

use Societo\BaseBundle\Test\WebTestCase;

class RelationshipControllerTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadFixtures(array(
            'Societo\RelationshipBundle\Tests\Fixtures\LoadAccountData',
        ));
    }

    public function getCsrfToken($client)
    {
        $client->request('GET', '/test/csrf');
        return $client->getResponse()->getContent();
    }

    public function getPostParameters($client)
    {
        return array('form' => array(
            '_token' => $this->getCsrfToken($client),
        ));
    }

    public function testFollow()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $account = $em->find('SocietoAuthenticationBundle:Account', 1);
        $this->login($client, $account);

        $client->request('POST', '/follow/2', $this->getPostParameters($client));
        $this->assertEquals('Your following was done correctly.', $client->getContainer()->get('session')->getFlash('success'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDuplicateFollow()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $account = $em->find('SocietoAuthenticationBundle:Account', 1);
        $this->login($client, $account);

        $client->request('POST', '/follow/2', $this->getPostParameters($client));
        $client->request('POST', '/follow/2', $this->getPostParameters($client));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testSelfFollow()
    {
        $client = static::createClient(array('root_config' => __DIR__.'/../config/config.php'));
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $account = $em->find('SocietoAuthenticationBundle:Account', 1);
        $this->login($client, $account);

        $client->request('POST', '/follow/1', $this->getPostParameters($client));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
