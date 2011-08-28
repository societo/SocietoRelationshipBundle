<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\RelationshipBundle\Tests\Fixtures;

use Societo\BaseBundle\Tests\Fixtures\LoadAccountData as BaseLoadAccountData;

class LoadAccountData extends BaseLoadAccountData
{
    public function load($manager)
    {
        $this->createAccount($manager, $this->createMember('example'), true);
        $this->createAccount($manager, $this->createMember('example2'));

        $manager->flush();
    }
}
