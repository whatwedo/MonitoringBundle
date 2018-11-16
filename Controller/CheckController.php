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

namespace whatwedo\MonitoringBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use whatwedo\MonitoringBundle\Enum\GlobalStatusEnum;
use whatwedo\MonitoringBundle\Manager\CheckManager;
use whatwedo\MonitoringBundle\Reporter\ArrayReporter;
use ZendDiagnostics\Runner\Runner;

/**
 * Class CheckController
 * @package whatwedo\MonitoringBundle\Controller
 */
class CheckController extends AbstractController
{
    /**
     * @var CheckManager $checkManager
     */
    protected $checkManager;

    /**
     * CheckController constructor.
     */
    public function __construct(CheckManager $checkManager)
    {
        $this->checkManager = $checkManager;
    }

    /**
     *
     */
    public function checkAction()
    {
        // Get checks
        $checks = $this->checkManager->getChecks();

        // Run checks
        $runner = new Runner();
        $runner->addChecks($checks);
        $reporter = new ArrayReporter();
        $runner->addReporter($reporter);
        $runner->run();

        // Return response
        $response = new JsonResponse(
            [
                'globalStatus' => $reporter->getGlobalStatus(),
                'checks' => $reporter->getResults(),
            ],
            ($reporter->getGlobalStatus() === GlobalStatusEnum::OK ? 200 : 500)
        );
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }
}
