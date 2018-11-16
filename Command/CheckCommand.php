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

namespace whatwedo\MonitoringBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use whatwedo\MonitoringBundle\Manager\CheckManager;
use whatwedo\MonitoringBundle\Reporter\ConsoleReporter;
use ZendDiagnostics\Runner\Runner;

/**
 * Class CheckCommand
 * @package whatwedo\MonitoringBundle\Command
 */
class CheckCommand extends Command
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

        parent::__construct();
    }
    
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('whatwedo:monitoring:check')
            ->setDescription('Runs all monitoring checks');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get checks
        $checks = $this->checkManager->getChecks();

        // Run checks
        $runner = new Runner();
        $runner->addChecks($checks);
        $reporter = new ConsoleReporter($output);
        $runner->addReporter($reporter);
        $results = $runner->run();

        // Return exit code
        return $results->getFailureCount() > 0 ? 1 : 0;
    }


}
