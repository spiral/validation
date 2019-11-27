<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Tests\Validation\Checkers;

use PHPUnit\Framework\TestCase;
use Spiral\Validation\Checker\DatetimeChecker;

class DatetimeTest extends TestCase
{
    /**
     * @dataProvider futureProvider
     * @param mixed $value
     * @param bool  $expected
     */
    public function testFuture($value, bool $expected): void
    {
        $checker = new DatetimeChecker();

        $this->assertSame($expected, $checker->future($value));
    }

    /**
     * @return array
     */
    public function futureProvider(): array
    {
        return [
            [time() + 10, true],
            ['tomorrow 10am', true],
            ['now + 10 seconds', true],
            [time() - 10, false],
            ['', false],
            [0, false],
            [1.1, false],
            ['date', false],
            [false, false],
            [true, false],
            [null, false],
            [[], false],
            [new \stdClass(), false],
        ];
    }

    /**
     * @dataProvider pastProvider
     * @param mixed $value
     * @param bool  $expected
     */
    public function testPast($value, bool $expected): void
    {
        $checker = new DatetimeChecker();

        $this->assertSame($expected, $checker->past($value));
    }

    /**
     * @return array
     */
    public function pastProvider(): array
    {
        return [
            [time() - 10, true],
            ['yesterday 10am', true],
            ['now - 10 seconds', true],
            [time() + 10, false],
            ['', true],
            [0, true],
            [1.1, true],
            ['date', false],
            [false, false],
            [true, false],
            [null, false],
            [[], false],
            [new \stdClass(), false],
        ];
    }

    /**
     * @dataProvider validProvider
     * @param mixed $value
     * @param bool  $expected
     */
    public function testValid($value, bool $expected): void
    {
        $checker = new DatetimeChecker();

        $this->assertSame($expected, $checker->valid($value));
    }

    /**
     * @return array
     */
    public function validProvider(): array
    {
        return [
            [time() - 10, true],
            [time(), true],
            [date('u'), true],
            [time() + 10, true],
            ['', true],
            ['tomorrow 10am', true],
            ['yesterday 10am', true],
            ['now', true],
            ['now + 10 seconds', true],
            ['now - 10 seconds', true],
            [0, true],
            [1.1, true],
            ['date', false],
            ['~#@', false],
            [false, false],
            [true, false],
            [null, false],
            [[], false],
            [new \stdClass(), false],
        ];
    }

    /**
     * @dataProvider formatProvider
     * @param mixed  $value
     * @param string $format
     * @param bool   $expected
     */
    public function testFormat($value, string $format, bool $expected): void
    {
        $checker = new DatetimeChecker();

        $this->assertSame($expected, $checker->format($value, $format));
    }

    /**
     * @return array
     */
    public function formatProvider(): array
    {
        return [
            ['2019-12-27T14:27:44+00:00', 'c', true], //this one is converted using other format chars
            ['2019-12-27T14:27:44+00:00', 'Y-m-d\TH:i:sT', true], //like the 'c' one
            ['Wed, 02 Oct 19 08:00:00 EST', \DateTime::RFC822, true],
            ['Wed, 02 Oct 19 08:00:00 +0200', \DateTime::RFC822, true],
            ['2019-12-12', 'Y-m-d', true],
            ['2019-12-12', 'Y-d-m', true],
            ['2019-13-12', 'Y-m-d', true],
            ['2019-12-13', 'Y-d-m', true],
            ['2019-12-Nov', 'Y-d-M', true],
            ['2019-12-Nov', 'Y-m-\N\o\v', true],
            ['2019-12-Nov', 'Y-M-d', false],
            ['2019-12-Nov', '123', false],
            ['2019+12-Nov', 'Y-m-d', false],
            ['-2019-12-Nov', 'Y-m-d', false],
            ['2019-12-Abc', 'Y-d-M', false],
        ];
    }

    public function testTimezone(): void
    {
        $checker = new DatetimeChecker();

        foreach (\DateTimeZone::listIdentifiers() as $identifier) {
            $this->assertTrue($checker->timezone($identifier));
            $this->assertFalse($checker->timezone(str_rot13($identifier)));
        }

        $this->assertFalse($checker->timezone('Any zone'));
    }
}
