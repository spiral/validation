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

    public function testUrlWithSchema()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->url('http://domain.com', ['http']));
        $this->assertTrue($checker->url('http://domain.com', ['http', 'https']));
        $this->assertTrue($checker->url('http://domain.com', ['http://']));
        //No schema requirements
        $this->assertTrue($checker->url('http://domain.com', []));
        $this->assertTrue($checker->url('http://domain.com'));

        //Schema mismatch
        $this->assertFalse($checker->url('http://domain.com', ['https']));
        $this->assertFalse($checker->url('http://domain.com', ['']));
        $this->assertFalse($checker->url('hhttp://domain.com', ['http']));
        //Invalid required schema
        $this->assertFalse($checker->url('http://domain.com', ['http:/']));
    }

    public function testUrlWithoutSchema()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->url('//domain.com', ['http', 'https'], 'http'));
        $this->assertTrue($checker->url('domain.com', ['http', 'https'], 'http'));
        $this->assertTrue($checker->url('domain.com', [], 'http'));
        $this->assertTrue($checker->url('domain.com', null, 'http'));
        $this->assertTrue($checker->url('domain', null, 'http'));
        $this->assertTrue($checker->url('http/domain.com', ['http'], 'http'));

        //Invalid URL
        $this->assertFalse($checker->url('://domain.com', ['http']));
        $this->assertFalse($checker->url('://domain.com', ['http'], 'http'));
        $this->assertFalse($checker->url('http:/domain.com', ['http']));
        $this->assertFalse($checker->url('http/domain.com', ['http']));
        $this->assertFalse($checker->url('/domain.com', ['http', 'https']));
        $this->assertFalse($checker->url('/domain.com', ['http', 'https'], 'http'));
        //No default schema
        $this->assertFalse($checker->url('//domain.com', ['http', 'https']));
        //No schema
        $this->assertFalse($checker->url('domain.com'));
    }

    public function testUri()
    {
        $checker = new AddressChecker();

        $this->assertTrue($checker->uri('https://john.doe:pwd@www.example.com:123/forum/questions/?tag=networking&order=newest#top'));
    }
}