<?php

declare(strict_types=1);

namespace Spiral\Scheduler;

use Symfony\Component\Process\PhpExecutableFinder;

final class CommandRunner
{
    public function run(string $command)
    {
    }

    /**
     * Determine the proper PHP executable.
     */
    public function phpBinary(): string
    {
        return ProcessUtils::escapeArgument(
            (new PhpExecutableFinder())->find(false)
        );
    }

    /**
     * Determine the proper spiral binary executable.
     */
    public function spiralBinary(): string
    {
        return defined('SPIRAL_BINARY') ? ProcessUtils::escapeArgument(SPIRAL_BINARY) : 'app.php';
    }

    /**
     * Format the given command as a fully-qualified executable command.
     */
    public function formatCommandString(string $string): string
    {
        return sprintf('%s %s %s', $this->phpBinary(), $this->spiralBinary(), $string);
    }
}
