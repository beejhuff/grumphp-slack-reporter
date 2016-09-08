<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\Messaging;

use GrumPHP\Collection\TaskResultCollection;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitCommitMsgContext;
use GrumPHP\Task\Context\GitPreCommitContext;

/**
 * Class MessageProvider
 *
 * @package GrumPHP\Extension\Slack\Messaging
 */
class MessageProvider
{
    /**
     * @param ContextInterface     $context
     * @param TaskResultCollection $taskResults
     *
     * @return string
     */
    public function generateMessage(ContextInterface $context, TaskResultCollection $taskResults): string
    {
        $success = $taskResults->isPassed() || $taskResults->isEmpty();

        $messages = ['I just sniffed your code ...'];
        if ($context instanceof GitPreCommitContext) {
            $messages = $success ? $this->getPreCommitSuccessMessages() : $this->getPreCommitErrorMessages();
        }
        if ($context instanceof GitCommitMsgContext) {
            $messages = $success ? $this->getCommitMessageSuccessMessages() : $this->getCommitMessageErrorMessages();
        }

        return $messages[array_rand($messages)];
    }

    /**
     * @return array
     */
    private function getPreCommitSuccessMessages(): array
    {
        return [
            'I just sniffed your code and it was _AWESOME_!',
        ];
    }

    /**
     * @return array
     */
    private function getCommitMessageSuccessMessages(): array
    {
        return [
            'Wow, even your commit message applies to all my strict rules!',
        ];
    }

    /**
     * @return array
     */
    private function getPreCommitErrorMessages(): array
    {
        return [
            'Darn, You\'ll have to take another look at your code!',
        ];
    }

    /**
     * @return array
     */
    private function getCommitMessageErrorMessages(): array
    {
        return [
            'Oh boy, It seems like you\'re having trouble writing decent commit messages ...'
        ];
    }
}
