<?php

/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Valentin Vintsukevich (vvval)
 */

declare(strict_types=1);

namespace Spiral\Validation\Checker;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Validation\AbstractChecker;

/**
 * @inherit-messages
 */
final class DatetimeChecker extends AbstractChecker implements SingletonInterface
{
    //Possible format mapping
    private const MAP_FORMAT = [
        'c' => 'Y-m-d\TH:i:sT'
    ];

    /**
     * {@inheritdoc}
     */
    public const MESSAGES = [
        'future' => '[[Not a future date.]]',
        'past'   => '[[Not a past date.]]',
        'valid'  => '[[Not a valid date.]]',
        'format' => '[[Value should match the specified date format {1}.]]',
    ];

    /**
     * Check if date is in the future.
     *
     * @param int|string $value
     * @return bool
     */
    public function future($value): bool
    {
        $date = $this->date($value);
        $now = $this->now();
        if ($date === null || $now === null) {
            return false;
        }

        return $date > $now;
    }

    /**
     * Check if date is in the past.
     *
     * @param int|string $value
     * @return bool
     */
    public function past($value): bool
    {
        $date = $this->date($value);
        $now = $this->now();
        if ($date === null || $now === null) {
            return false;
        }

        return $date < $now;
    }

    /**
     * Check if date format matches the provided one.
     *
     * @param int|string $value
     * @param string     $format
     * @return bool
     */
    public function format($value, string $format): bool
    {
        if (!$this->isApplicableValue($value)) {
            return false;
        }

        $date = \DateTime::createFromFormat(self::MAP_FORMAT[$format] ?? $format, (string)$value);

        return $date !== false;
    }

    /**
     * Check if date is valid. Empty values are acceptable.
     *
     * @param int|string $value
     * @return bool
     */
    public function valid($value): bool
    {
        return $this->date($value) !== null;
    }

    /**
     * Value has to be a valid timezone.
     *
     * @param mixed $value
     * @return bool
     */
    public function timezone($value): bool
    {
        if (!is_scalar($value)) {
            return false;
        }

        return in_array((string)$value, \DateTimeZone::listIdentifiers(), true);
    }

    /**
     * @param string|int $value
     * @return \DateTime|null
     */
    private function date($value): ?\DateTime
    {
        if (!$this->isApplicableValue($value)) {
            return null;
        }

        try {
            return new \DateTime(is_numeric($value) ? ('@' . (int)$value) : (string)$value);
        } catch (\Throwable $e) {
            //here's the fail;
        }

        return null;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isApplicableValue($value): bool
    {
        return is_string($value) || is_numeric($value);
    }

    /**
     * @return \DateTime
     */
    private function now(): ?\DateTime
    {
        try {
            return new \DateTime('now');
        } catch (\Throwable $e) {
            //here's the fail;
        }

        return null;
    }
}
