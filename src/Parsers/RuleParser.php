<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Parsers;

use Spiral\Validation\Exceptions\ParserException;
use Spiral\Validation\ParserInterface;

class RuleParser implements ParserInterface
{
    const ARGUMENTS  = ['args', 'params', 'arguments', 'parameters'];
    const MESSAGES   = ['message', 'msg', 'error', 'err'];
    const CONDITIONS = ['if', 'condition', 'conditions', 'where'];

    /** @var ConditionParser */
    private $conditionParser;

    /**
     * RuleParser constructor.
     *
     * @param ConditionParser $conditionParser
     */
    public function __construct(ConditionParser $conditionParser)
    {
        $this->conditionParser = $conditionParser;
    }

    /**
     * @inheritdoc
     */
    public function split($rules): \Generator
    {
        $rules = is_array($rules) ? $rules : [$rules];

        foreach ($rules as $rule) {
            if ($rule instanceof \Closure) {
                yield null => $rule;
                continue;
            }

            yield $this->getID($rule) => $rule;
        }
    }

    /**
     * @inheritdoc
     */
    public function parseCheck($chunk)
    {
        if (is_string($chunk)) {
            $function = str_replace('::', ':', $chunk);
        } else {
            if (!is_array($chunk) || !isset($chunk[0])) {
                throw new ParserException("Validation chunk does not define any check function");
            }

            $function = $chunk[0];
        }

        if (is_string($function)) {
            return str_replace('::', ':', $function);
        }

        return $function;
    }

    /**
     * @inheritdoc
     */
    public function parseArgs($chunk): array
    {
        if (!is_array($chunk)) {
            return [];
        }

        foreach (self::ARGUMENTS as $index) {
            if (isset($chunk[$index])) {
                return $chunk[$index];
            }
        }

        foreach (self::MESSAGES as $index) {
            unset($chunk[0], $chunk[$index], $chunk[$index]);
        }

        return array_values($chunk);
    }

    /**
     * @inheritdoc
     */
    public function parseMessage($chunk): ?string
    {
        if (!is_array($chunk)) {
            return null;
        }

        foreach (self::MESSAGES as $index) {
            if (isset($chunk[$index])) {
                return $chunk[$index];
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function parseConditions($chunk): \SplObjectStorage
    {
        if (is_array($chunk)) {
            foreach (self::CONDITIONS as $index) {
                if (isset($chunk[$index])) {
                    // todo: do something else
                    return $this->conditionParser->parse($chunk[$index]);
                }
            }
        }

        return $this->conditionParser->parse([]);
    }

    /**
     * @param $rule
     * @return string
     */
    protected function getID($rule): string
    {
        return json_encode($rule);
    }
}