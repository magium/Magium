<?php

namespace Magium\TestCase;

class Executor
{

    protected $reservedTrue = [
        'true'
    ];

    protected $reservedFalse = [
        'false', 'null', ''
    ];

    protected $operators = [
        T_IS_EQUAL, T_IS_NOT_EQUAL, T_IS_SMALLER_OR_EQUAL, T_IS_GREATER_OR_EQUAL
    ];

    protected $stringOperators = [
        '>', '<'
    ];

    public function evaluate($string)
    {
        $string = '<?php ' . $string;
        $comparison1 = $comparison2 = '';
        $operator = null;
        $tokens = token_get_all($string);
        array_shift($tokens);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $this->operators)) {
                    $operator = $token[1];
                } else if ($token[0] == T_STRING || $token[0] == T_WHITESPACE || $token[0] == T_LNUMBER) {
                    if ($operator === null) {
                        $comparison1 .= $token[1];
                    } else {
                        $comparison2 .= $token[1];
                    }
                }
            } else if (in_array($token, $this->stringOperators)) {
                $operator = $token;
            }
        }

        $comparison1 = trim($comparison1);
        $comparison2 = trim($comparison2);

        if ($operator === null) {
            $isTrue = in_array($comparison1, $this->reservedTrue);
            if ($isTrue) return true;

            $isFalse = in_array($comparison1, $this->reservedFalse);
            if ($isFalse) return false;

            return (boolean)$comparison1;
        } else {
            switch ($operator) {
                case '==':
                    return $comparison1 == $comparison2;
                    break;
                case '!=':
                    return $comparison1 != $comparison2;
                    break;
                case '>':
                    return $comparison1 > $comparison2;
                    break;
                case '<':
                    return $comparison1 < $comparison2;
                    break;
                case '>=':
                    return $comparison1 >= $comparison2;
                    break;
                case '<=':
                    return $comparison1 <= $comparison2;
                    break;
                default:
                    return false;
                    break;

            }
        }
        return false;
    }

}
