<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Checker;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Validation\AbstractChecker;

/**
 * @inherit-messages
 */
class AddressChecker extends AbstractChecker implements SingletonInterface
{
    /**
     * {@inheritdoc}
     */
    const MESSAGES = [
        'email' => '[[Must be a valid email address.]]',
        'url'   => '[[Must be a valid URL address.]]',
    ];

    /**
     * Check if email is valid.
     *
     * @link http://www.ietf.org/rfc/rfc2822.txt
     *
     * @param string $address
     *
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
     *
     * @param string $url
     * @param bool   $schemeRequired If true, this will require having a protocol definition.
     *
     * @return bool
     */
    public function url(string $url, bool $schemeRequired = true): bool
    {
        if (!$schemeRequired && stripos($url, '://') === false) {
            //Allow urls without http schema
            $url = 'http://' . $url;
        }

        if ((bool)filter_var($url, FILTER_VALIDATE_URL)) {
            //Double checking http protocol presence
            return stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0;
        }

        return false;
    }

    /**
     * @todo Improve the regexp pattern
     * @link https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
     * @link http://www.ietf.org/rfc/rfc3986.txt
     *
     * @param string $uri
     *
     * @return bool
     */
    public function uri(string $uri): bool
    {
        $pattern = "/^(([^:\/\?#]+):)?(\/\/([^\/\?#]*))?([^\?#]*)(\?([^#]*))?(#(.*))?$/";

        return (bool)preg_match($pattern, $uri);
    }
}
