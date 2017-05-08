<?php
/*
 * Copyright (c) 2017, whatwedo GmbH
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace whatwedo\MonitoringBundle\Check;

use Doctrine\Bundle\MigrationsBundle\Command\DoctrineCommand;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\ORM\EntityManager;
use ZendDiagnostics\Check\DoctrineMigration;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\Skip;
use Doctrine\DBAL\Connection;
use ZendDiagnostics\Result\Success;

/**
 * Class DoctrineMigrationCheck
 * @package whatwedo\MonitoringBundle\Check
 */
class DoctrineMigrationCheck extends AbstractCheck
{

    /**
     * Perform the actual check and return a ResultInterface
     *
     * @return ResultInterface
     */
    public function check()
    {
        if (!$this->has('doctrine')) {
            return new Skip('Doctrine is not available.');
        }
        if (!class_exists('Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle')) {
            return new Skip('DoctrineMigrationsBundle is not available.');
        }

        /** @var EntityManager $manager */
        $doctrine = $this->get('doctrine');
        $connections = $doctrine->getConnections();

        /** @var Connection $connection */
        foreach ($connections as $connection) {
            $migrationConfig = new Configuration($connection);
            DoctrineCommand::configureMigrations($this->container, $migrationConfig);
            $checkInstance = new DoctrineMigration($migrationConfig);
            $check = $checkInstance->check();
            if (!$check instanceof Success) {
                return $check;
            }
        }

        return new Success();
    }

    /**
     * Return a label describing this test instance.
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Checks if all Doctrine migrations are applied';
    }
}
