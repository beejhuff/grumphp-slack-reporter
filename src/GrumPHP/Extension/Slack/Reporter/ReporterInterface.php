<?php

namespace GrumPHP\Extension\Slack\Reporter;

use GrumPHP\Collection\TaskResultCollection;
use GrumPHP\Task\Context\ContextInterface;

/**
 * Interface ReporterInterface
 *
 * @package GrumPHP\Extension\Slack\Reporter
 */
interface ReporterInterface
{
    /**
     * @param ContextInterface     $context
     * @param TaskResultCollection $taskResults
     */
    public function report(ContextInterface $context, TaskResultCollection $taskResults);
}
