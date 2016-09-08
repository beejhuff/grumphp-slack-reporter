<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\EventListener;

use GrumPHP\Event\RunnerEvent;
use GrumPHP\Event\RunnerFailedEvent;
use GrumPHP\Extension\Slack\Reporter\ReporterInterface;
use GrumPHP\Task\Context\RunContext;

/**
 * Class ReportFailedTasksListener
 *
 * @package GrumPHP\Extension\Slack\EventListener
 */
class SlackReporterListener
{
    /**
     * @var ReporterInterface
     */
    private $reporter;

    /**
     * SlackReporterListener constructor.
     *
     * @param ReporterInterface $reporter
     */
    public function __construct(ReporterInterface $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * @param RunnerFailedEvent $event
     */
    public function onFailed(RunnerFailedEvent $event)
    {
        $this->report($event);
    }

    /**
     * @param RunnerEvent $event
     */
    public function onSuccess(RunnerEvent $event)
    {
        $this->report($event);
    }

    /**
     * @param RunnerEvent $event
     */
    private function report(RunnerEvent $event)
    {
        if ($event->getContext() instanceof RunContext) {
            return;
        }

        $this->reporter->report($event->getContext(), $event->getTaskResults());
    }
}
