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

use JMS\JobQueueBundle\Entity\Job;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\Skip;
use ZendDiagnostics\Result\Success;

/**
 * Class JMSJobStatusCheck
 * @package whatwedo\MonitoringBundle\Check
 */
class JMSJobStatusCheck extends AbstractCheck
{

    /**
     * Perform the actual check and return a ResultInterface
     *
     * @return Success|Failure|Skip
     */
    public function check()
    {
        if (!$this->has('doctrine.orm.default_entity_manager')) {
            return new Skip('Entity manager is not available.');
        }
        if (!$this->has('jms_job_queue.scheduler_registry')) {
            return new Skip('JMSJobQueueBundle is not available.');
        }

        // Get entity manager
        $em = $this->get('doctrine.orm.default_entity_manager');

        // Get last failed
        $job = $em->getRepository('JMSJobQueueBundle:Job')->createQueryBuilder('j')
            ->where('j.state IN (:errorStates)')
            ->andWhere('j.originalJob is null')
            ->andWhere('j.closedAt > :at')
            ->orderBy('j.closedAt')
            ->setParameter('errorStates', [Job::STATE_TERMINATED, Job::STATE_FAILED])
            ->setParameter('at', (new \DateTime())->modify('-3 hours'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Check status
        if (!$job) {
            return new Success('There are no failed jobs in the last 3h');
        }
        return new Failure('There are failed jobs in the last 3h');
    }

    /**
     * Return a label describing this test instance.
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Checks JMS Job Queue Bundle job status in last 3h';
    }
}
