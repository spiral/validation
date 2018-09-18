<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Validation\Checkers;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Validation\Checker\AddressChecker;

class AddressTest extends TestCase
{
    public function testEmail()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->email('test@email.com'));
        $this->assertTrue($checker->email('te.st@email.uk'));

        $this->assertFalse($checker->email('test#email.com'));
        $this->assertFalse($checker->email('test.email.com'));
    }

    public function testUrlWithSchemeRequired()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->url('http://domain.com'));
        $this->assertTrue($checker->url('https://domain.uk'));

        $this->assertFalse($checker->url('domain.com'));
        $this->assertFalse($checker->url('javascript://domain.com'));
    }

    public function testUrlWithoutSchemeRequired()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->url('domain.com', false));
        $this->assertTrue($checker->url('domain.uk', false));

        $this->assertFalse($checker->url('ht:domain.com', false));
        $this->assertFalse($checker->url('javascript://domain.com', false));
    }

    public function testUri()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->uri('https://john.doe:pwd@www.example.com:123/forum/questions/?tag=networking&order=newest#top'));
    }
}