<?php

namespace tests\AbstractBabel\CrossRefClient;

use GuzzleHttp\Psr7\Request;
use PHPUnit_Framework_Constraint;
use PHPUnit_Framework_Constraint_IsEqual;
use PHPUnit_Framework_ExpectationFailedException;
use function GuzzleHttp\Psr7\str;

final class RequestConstraint extends PHPUnit_Framework_Constraint
{
    public function __construct($value)
    {
        parent::__construct();

        $this->value = $value;
        $this->wrapped = new PHPUnit_Framework_Constraint_IsEqual(str($this->value));
    }

    public static function equalTo(Request $expected)
    {
        return new self($expected);
    }

    /**
     * @param Request $other Value or object to evaluate
     *
     * @return bool
     *
     * @throws PHPUnit_Framework_ExpectationFailedException
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        return $this->wrapped->evaluate(
            str($other),
            $description,
            $returnResult
        );
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf(
            'is equal to %s',
            str($this->value)
        );
    }
}
