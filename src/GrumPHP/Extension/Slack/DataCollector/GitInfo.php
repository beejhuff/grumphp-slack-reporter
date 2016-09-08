<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack\DataCollector;

use Exception;
use Gitonomy\Git\Repository;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class GitInfo
 *
 * @package GrumPHP\Extension\Slack\DataCollector
 */
class GitInfo
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * GitInfo constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     * @throws \Gitonomy\Git\Exception\RuntimeException
     */
    public function fetchUsername(): string
    {
        return $this->queryGitConfig('user.name', 'unknown');
    }

    /**
     * @return string
     * @throws \Gitonomy\Git\Exception\RuntimeException
     */
    public function fetchEmail(): string
    {
        return $this->queryGitConfig('user.email');
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @return string
     */
    private function queryGitConfig(string $key, string $default = ''): string
    {
        try {
            $builder = ProcessBuilder::create(['git', 'config', $key]);
            $process = $builder->getProcess();
            $process->run();

            if (!$process->isSuccessful()) {
                return $default;
            }

            $output = $process->getOutput();

            return trim($output);
        } catch (Exception $e) {
            return $default;
        }
    }
}
