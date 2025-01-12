<?php

declare(strict_types=1);

namespace Spiral\Scheduler\Tests\Mutex;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Psr\SimpleCache\CacheInterface;
use Spiral\Scheduler\Mutex\CacheJobMutex;
use Spiral\Scheduler\Tests\TestCase;

final class CacheJobMutexTest extends TestCase
{
    private \Mockery\MockInterface $cache;
    private CacheJobMutex $mutex;

    public function testCreates(): void
    {
        $this->cache->shouldReceive('set')->with('job-id', true, 100 * 60)->andReturnTrue();
        $this->cache->shouldReceive('has')->with('job-id')->andReturnFalse();

        $this->assertTrue($this->mutex->create('job-id', 100));
    }

    public function testCreatesShouldReturnFalseIfJobIdExist(): void
    {
        $this->cache->shouldReceive('has')->with('job-id')->andReturnTrue();
        $this->assertFalse($this->mutex->create('job-id', 100));
    }

    public function testExists(): void
    {
        $this->cache->shouldReceive('has')->with('job-id')->andReturnTrue();
        $this->assertTrue($this->mutex->exists('job-id'));
    }

    #[DoesNotPerformAssertions]
    public function testForget(): void
    {
        $this->cache->shouldReceive('delete')->with('job-id');
        $this->mutex->forget('job-id');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->mockContainer(CacheInterface::class);
        $this->mutex = new CacheJobMutex($this->cache);
    }
}
