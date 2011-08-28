<?php

/**
 * SocietoRelationshipBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\RelationshipBundle;

use Societo\ActivityBundle\ActivityGenerator\DoctrineEntityGenerator;
use Societo\ActivityBundle\ActivityObject;

class ActivityGenerator extends DoctrineEntityGenerator
{
    const ACTIVITY_TYPE_RELATIONSHIP_FOLLOW = 'follow';

    public function getAvailableType()
    {
        return array(
            self::ACTIVITY_TYPE_RELATIONSHIP_FOLLOW => 'Member follows someone',
        );
    }

    protected function getPersistActivity($entity, $em)
    {
        $this->registerActivity($em, $entity->getOrigin(), 'follow', new ActivityObject($entity->getDestination()->getUsername()), new ActivityObject(), '', self::ACTIVITY_TYPE_RELATIONSHIP_FOLLOW);
    }

    protected function getUpdateActivity($entity, $em)
    {
        // do nothing
    }
}
