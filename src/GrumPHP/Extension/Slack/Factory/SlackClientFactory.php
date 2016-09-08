<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\Factory;

use GrumPHP\Extension\Slack\Config\SlackConfig;
use Maknz\Slack\Client;

/**
 * Class SlackClientFactory
 *
 * @package GrumPHP\Extension\Slack\Factory
 */
class SlackClientFactory
{
    /**
     * @param SlackConfig $config
     *
     * @return Client
     */
    public function factory(SlackConfig $config) : Client
    {
        return new Client($config->getWebhook(), [
            'channel' => $config->getChannel(),
        ]);
    }
}
