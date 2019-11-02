<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Validation\Checker;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Validation\AbstractChecker;

/**
 * @inherit-messages
 */
final class AddressChecker extends AbstractChecker implements SingletonInterface
{
    /**
     * {@inheritdoc}
     */
    public const MESSAGES = [
        'email' => '[[Must be a valid email address.]]',
        'url'   => '[[Must be a valid URL address.]]',
    ];

    /**
     * Check if email is valid.
     *
     * @link http://www.ietf.org/rfc/rfc2822.txt
     * @param string $address
     * @return bool
     */
    public function email($address): bool
    {
        return (bool)filter_var($address, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if URL is valid.
     *
     * @link http://www.faqs.org/rfcs/rfc2396.html
     * @param string      $url
     * @param null|array  $schemas
     * @param null|string $defaultSchema
     * @return bool
     */
    public function url(string $url, ?array $schemas = null, ?string $defaultSchema = null): bool
    {
        //Add default schema if not presented
        if (!$this->hasSchema($url) && !empty($defaultSchema)) {
            $defaultSchema = $this->trimSchema($defaultSchema);
            if (!empty($defaultSchema)) {
                $url = "$defaultSchema://{$this->trimURL($url)}";
            }
        }

        if (empty($schemas)) {
            return (bool)filter_var($url, FILTER_VALIDATE_URL);
        }

        foreach ($schemas as $schema) {
            $schema = $this->trimSchema($schema);
            if (empty($schema) || !$this->containsSchema($url, $schema)) {
                continue;
            }

            return (bool)filter_var($url, FILTER_VALIDATE_URL);
        }

        return false;
    }

    /**
     * @link http://www.ietf.org/rfc/rfc3986.txt
     * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
     * @param string $uri
     * @return bool
     */
    public function uri(string $uri): bool
    {
        // todo: improve the regexp pattern

        $pattern = "/^(([^:\/\?#]+):)?(\/\/([^\/\?#]*))?([^\?#]*)(\?([^#]*))?(#(.*))?$/";

        return (bool)preg_match($pattern, $uri);
    }

    /**
     * @param string $url
     * @return bool
     */
    private function hasSchema(string $url): bool
    {
        return mb_stripos($url, '://') !== false;
    }

    /**
     * @param string $schema
     * @return string
     */
    private function trimSchema(string $schema): string
    {
        return preg_replace('/^([a-z]+):\/\/$/i', '$1', $schema);
    }

    /**
     * @param string $url
     * @param string $schema
     *
     * @return bool
     */
    private function containsSchema(string $url, string $schema): bool
    {
        return mb_stripos($url, "$schema://") === 0;
    }

    /**
     * @param string $url
     * @return string
     */
    private function trimURL(string $url): string
    {
        return preg_replace('/^\/\/(.*)$/i', '$1', $url);
    }
}
