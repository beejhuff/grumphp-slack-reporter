<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\Config;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SlackConfig
 *
 * @package GrumPHP\Extension\Slack\Config
 */
class SlackConfig
{

    /**
     * @var string
     */
    private $webhook;

    /**
     * @var string
     */
    private $channel;

    /**
     * SlackConfig constructor.
     *
     * @param $webhook
     * @param $channel
     */
    public function __construct($webhook, $channel)
    {
        $this->webhook = $webhook;
        $this->channel = $channel;
    }

    /**
     * @return OptionsResolver
     */
    public static function getConfigurableOptions() : OptionsResolver
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['webhook', 'channel']);

        $resolver->addAllowedTypes('webhook', ['string']);
        $resolver->addAllowedTypes('channel', ['string']);

        return $resolver;
    }

    /**
     * @param array $config
     *
     * @return SlackConfig
     */
    public static function resolveFromConfig(array $config)
    {
        $normalized = self::getConfigurableOptions()->resolve($config);

        return new self($normalized['webhook'], $normalized['channel']);
    }

    /**
     * @return string
     */
    public function getWebhook(): string
    {
        return $this->webhook;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }
}
