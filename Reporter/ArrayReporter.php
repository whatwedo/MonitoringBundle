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

namespace whatwedo\MonitoringBundle\Reporter;

use whatwedo\MonitoringBundle\Enum\GlobalStatusEnum;
use whatwedo\MonitoringBundle\Enum\StatusNameEnum;
use ZendDiagnostics\Runner\Reporter\ReporterInterface;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\SkipInterface;
use ZendDiagnostics\Result\SuccessInterface;
use ZendDiagnostics\Result\WarningInterface;
use ZendDiagnostics\Result\Collection as ResultsCollection;

/**
 * Class ArrayReporter
 * @package whatwedo\MonitoringBundle\Reporter
 */
class ArrayReporter implements ReporterInterface
{

    private $globalStatus = GlobalStatusEnum::OK;
    private $results = [];

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }
    /**
     * @return string
     */
    public function getGlobalStatus()
    {
        return $this->globalStatus;
    }
    /**
     * {@inheritdoc}
     */
    public function onAfterRun(CheckInterface $check, ResultInterface $result, $checkAlias = null)
    {
        switch (true) {
            case $result instanceof SuccessInterface:
                $status = 0;
                $statusName = StatusNameEnum::OK;
                break;
            case $result instanceof WarningInterface:
                $status = 1;
                $statusName = StatusNameEnum::WARNING;
                $this->globalStatus = GlobalStatusEnum::NOT_OK;
                break;
            case $result instanceof SkipInterface:
                $status = 2;
                $statusName = StatusNameEnum::SKIP;
                break;
            default:
                $status = 3;
                $statusName = StatusNameEnum::CRITICAL;
                $this->globalStatus = GlobalStatusEnum::NOT_OK;
        }
        $this->results[] = array(
            'checkName' => $check->getLabel(),
            'message' => $result->getMessage(),
            'status' => $status,
            'status_name' => $statusName,
        );
    }
    /**
     * {@inheritdoc}
     */
    public function onStart(\ArrayObject $checks, $runnerConfig)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function onBeforeRun(CheckInterface $check, $checkAlias = null)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function onStop(ResultsCollection $results)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function onFinish(ResultsCollection $results)
    {
        return;
    }
}
