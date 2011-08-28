<?php

/**
 * SocietoRelationshipBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\RelationshipBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FollowerRepository extends EntityRepository
{
    public function findFollowerQuery($memberId)
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select('f')
            ->from('Societo\RelationshipBundle\Entity\Follower', 'f')
            ->where('f.destination = :destination')
            ->setParameter('destination', $memberId)
        ;

        return $builder;
    }

    public function findFollowers($memberId, $limit = null, $offset = 0)
    {
        $builder = $this->findFollowerQuery();

        if (null !== $limit) {
            $builder->setFirstResult($offset)->setMaxResults($limit);
        }

        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function findFollowingQuery($memberId)
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select('f')
            ->from('Societo\RelationshipBundle\Entity\Follower', 'f')
            ->where('f.origin = :origin')
            ->setParameter('origin', $memberId)
        ;

        return $builder;
    }

    public function findFollowings($memberId, $limit = null, $offset = null)
    {
        $builder = $this->findFollowingQuery($memberId);

        if (null !== $limit) {
            $builder->setFirstResult($offset)->setMaxResults($limit);
        }

        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function isFollowing($origin, $destination)
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select('f.id')
            ->from('Societo\RelationshipBundle\Entity\Follower', 'f')
            ->where('f.origin = :origin')
            ->andWhere('f.destination = :destination')
            ->setParameter('origin', $origin)
            ->setParameter('destination', $destination)
        ;
        $query = $builder->getQuery();

        try {
            $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return false;
        }

        return true;
    }

    public function isFollowable($origin, $destination)
    {
        if ($origin == $destination) {
            return false;
        }

        return !$this->isFollowing($origin, $destination);
    }
}
