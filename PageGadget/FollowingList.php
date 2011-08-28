<?php

/**
 * SocietoRelationshipBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\RelationshipBundle\PageGadget;

use Societo\PageBundle\PageGadget\AbstractPageGadget;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class FollowingList extends AbstractPageGadget
{
    protected $caption = 'Following List';

    protected $description = 'A block for displaying list of your following members';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');

        $maxResults = $gadget->getParameter('max_results');

        $member = $parentAttributes->get('member', $user->getMember());

        $builder = $em->getRepository('SocietoRelationshipBundle:Follower')->findFollowingQuery($member->getId());
        $adapter = new DoctrineORMAdapter($builder->getQuery());
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setMaxPerPage($maxResults)
            ->setCurrentPage($this->get('request')->query->get('page', 1))
        ;

        return $this->render('SocietoRelationshipBundle:PageGadget:following_list.html.twig', array(
            'gadget'     => $gadget,
            'followings' => $pagerfanta->getCurrentPageResults(),
            'route_to_more_page' => $gadget->getParameter('route_to_more_page'),
            'has_pager' => $gadget->getParameter('has_pager'),
            'pagerfanta' => $pagerfanta,
            'attributes' => $parentAttributes,
            'show_name' => $gadget->getParameter('show_name'),
        ));
    }

    public function getOptions()
    {
        return array(
            'show_name' => array(
                'type' => 'checkbox',
                'options' => array(
                    'required' => false,
                ),
            ),

            'max_results' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),

            'route_to_more_page' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),

            'has_pager' => array(
                'type' => 'choice',
                'options' => array(
                    'choices' => array(
                        0 => 'No',
                        1 => 'Yes',
                    ),
                ),
            ),
        );
    }
}
