<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\Reporter;

use GrumPHP\Collection\TaskResultCollection;
use GrumPHP\Extension\Slack\DataCollector\GitInfo;
use GrumPHP\Extension\Slack\Messaging\MessageProvider;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Task\Context\ContextInterface;
use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentField;
use Maknz\Slack\Client;
use Maknz\Slack\Message;

/**
 * Class SlackReporter
 *
 * @package GrumPHP\Extension\Slack\Reporter
 */
class SlackReporter implements ReporterInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var GitInfo
     */
    private $gitInfo;

    /**
     * @var MessageProvider
     */
    private $messageProvider;

    /**
     * SlackReporter constructor.
     *
     * @param Client          $client
     * @param GitInfo         $gitInfo
     * @param MessageProvider $messageProvider
     */
    public function __construct(Client $client, GitInfo $gitInfo, MessageProvider $messageProvider)
    {
        $this->client = $client;
        $this->gitInfo = $gitInfo;
        $this->messageProvider = $messageProvider;
    }

    /**
     * @param ContextInterface     $context
     * @param TaskResultCollection $taskResults
     */
    public function report(ContextInterface $context, TaskResultCollection $taskResults)
    {
        try {
            $message = $this->prepareSlackMessage($context, $taskResults);
            $message->send($this->messageProvider->generateMessage($context, $taskResults));
        } catch (\Exception $e) {
            // Life goes on ...
        }
    }

    /**
     * @param TaskResultCollection $taskResults
     *
     * @return Message
     */
    private function prepareSlackMessage(ContextInterface $context, TaskResultCollection $taskResults): Message
    {
        $attachment = new Attachment([
            'icon' => $this->generateIcon($taskResults),
            'color' => $this->generateColor($taskResults),
            'author_name' => $this->gitInfo->fetchUsername(),
            'author_link' => 'mailto:' . $this->gitInfo->fetchEmail(),
            "author_icon" => 'http://www.gravatar.com/avatar/' . md5($this->gitInfo->fetchEmail()),
        ]);

        $attachment->addField(new AttachmentField([
            'title' => 'Sniffing context',
            'value' => (new \ReflectionClass($context))->getShortName(),
            'short' => false
        ]));

        /** @var TaskResult $taskResult */
        foreach ($taskResults as $taskResult) {
            $attachment->addField(new AttachmentField([
                'title' => $taskResult->getTask()->getName(),
                'value' => $this->generateTaskStatus($taskResult),
                'short' => true,
            ]));
        }

        $message = $this->client->createMessage();
        $message->attach($attachment);

        return $message;
    }

    /**
     * @param TaskResultCollection $taskResults
     *
     * @return string
     */
    private function generateColor(TaskResultCollection $taskResults): string
    {
        switch ($taskResults->getResultCode()) {
            case TaskResult::FAILED:
                return 'danger';
            case TaskResult::NONBLOCKING_FAILED:
                return 'warning';
            case TaskResult::PASSED:
                return 'good';
        }

        return '#AAAAAA';
    }

    /**
     * @param TaskResult $taskResult
     *
     * @return string
     */
    private function generateTaskStatus(TaskResult $taskResult): string
    {
        switch ($taskResult->getResultCode()) {
            case TaskResult::FAILED:
                return 'Failed';
            case TaskResult::NONBLOCKING_FAILED:
                return 'Non blocking';
            case TaskResult::SKIPPED:
                return 'Skipped';
            case TaskResult::PASSED:
                return 'Passed';
        }

        return 'Unknown';
    }

    /**
     * @param TaskResultCollection $taskResults
     *
     * @return string
     */
    private function generateIcon(TaskResultCollection $taskResults): string
    {
        $passed = $taskResults->isPassed() || $taskResults->isEmpty();

        return $passed ? 'grumphp-happy.png' : 'grumphp-grumpy.png';
    }
}
