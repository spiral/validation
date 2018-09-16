<?php

namespace Spiral\Validation\Tests;

use Spiral\Validation\AbstractCondition;
use Spiral\Validation\RulesInterface;
use Spiral\Validation\ValidatorInterface;

class ConditionTest extends BaseTest
{
    /** @var \Spiral\Validation\RulesInterface */
    protected $rules;

    /**
     * @expectedException \Spiral\Core\Exceptions\Container\NotFoundException
     */
    public function testUnknown()
    {
        $rules = $this->rules->getRules([
            'i' => [
                'in_array',
                ['a', 'b'],
                'if' => 'unknownCondition'
            ],
        ]);

        foreach ($rules as $rule) {
            //do nothing
        }
    }

    public function testString()
    {
        $rules = $this->rules->getRules([
            'i' => [
                'in_array',
                ['a', 'b'],
                'if' => TestAbstractCondition::class
            ]
        ]);

        foreach ($rules as $rule) {
            $count = 0;
            foreach ($rule->getConditions() as $condition) {
                $this->assertInstanceOf(TestAbstractCondition::class, $condition);
                $count++;
            }

            $this->assertEquals(1, $count);
        }
    }

    public function testPayload()
    {
        $rules = $this->rules->getRules([
            'i' => [
                'in_array',
                ['a', 'b'],
                'if' => [PayloadAbstractCondition::class => 'j']
            ]
        ]);

        $validator = $this->validation->validate(['i' => 1, 'j' => 2], [], ['k' => 3]);
        foreach ($rules as $rule) {
            foreach ($rule->getConditions() as $condition) {
                $this->assertTrue($condition->isMet($validator, 'i', 1));
                $this->assertTrue($condition->isMet($validator, 'j', 2));
                $this->assertTrue($condition->isMet($validator, 'k', 3));

                $this->assertFalse($condition->isMet($validator, 'l', 4));
            }
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->rules = $this->container->get(RulesInterface::class);
    }
}

class TestAbstractCondition extends AbstractCondition
{
    public function isMet(ValidatorInterface $validator, string $field, $value): bool
    {
        return true;
    }
}

class PayloadAbstractCondition extends AbstractCondition
{
    public function isMet(ValidatorInterface $validator, string $field, $value): bool
    {
        $payload = $this->getPayload($validator)['j'];
        switch ($field) {
            case 'i':
                return $value === $payload - 1;

            case 'j':
                return $value === $payload;

            case 'k':
                return $value === $payload + 1;

            default:
                return false;
        }
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @return array
     */
    protected function getPayload(ValidatorInterface $validator): array
    {
        $payload = [];
        foreach ($this->options as $option) {
            $payload[$option] = $validator->getValue(
                $option,
                $validator->getContext()[$option] ?? null
            );
        }

        return $payload;
    }
}