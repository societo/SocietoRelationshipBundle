<?php

/**
 * SocietoRelationshipBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\RelationshipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RelationshipController extends Controller
{
    public function followAction($member)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('SocietoRelationshipBundle:Follower');

        $form = $this->createForm('form');
        $form->bindRequest($this->get('request'));
        if (!$form->isValid())
        {
            throw $this->createNotFoundException();
        }

        if (!$repository->isFollowable($user->getMemberId(), $member->getId())) {
            throw $this->createNotFoundException();
        }

        $follower = new \Societo\RelationshipBundle\Entity\Follower($user->getMember(), $member);
        $em->persist($follower);
        $em->flush();

        $this->get('session')->setFlash('success', 'Your following was done correctly.');

        return new RedirectResponse($this->generateUrl('profile', array('member' => $member)));
    }
}
