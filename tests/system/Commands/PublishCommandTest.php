<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Commands;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\StreamFilterTrait;
use Tests\Support\Publishers\TestPublisher;

/**
 * @internal
 */
final class PublishCommandTest extends CIUnitTestCase
{
    use StreamFilterTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerStreamFilterClass()
            ->appendOutputStreamFilter()
            ->appendErrorStreamFilter();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->removeOutputStreamFilter()->removeErrorStreamFilter();
        TestPublisher::setResult(true);
    }

    public function testDefault()
    {
        command('publish');

        $this->assertStringContainsString(lang('Publisher.publishSuccess', [
            TestPublisher::class,
            0,
            WRITEPATH,
        ]), $this->getStreamFilterBuffer());
    }

    public function testFailure()
    {
        TestPublisher::setResult(false);

        command('publish');

        $this->assertStringContainsString(lang('Publisher.publishFailure', [
            TestPublisher::class,
            WRITEPATH,
        ]), $this->getStreamFilterBuffer());
    }
}
