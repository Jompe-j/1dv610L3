<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-19
 * Time: 20:15
 */

namespace model;


class CalculatorEvaluator {
    private $toEvaluate;
    private $stack = Array();
    private $finishedExpression;

    public function __construct($input) {
        $this->toEvaluate = $input;
        $this->calculate();

    }

    private function calculate() {
        $this->buildStringToSuffixFix();
        $this->infixToSuffix();

    }
    /**
     * @param $hiddenValue
     * @return string
     */
    private function buildStringToSuffixFix(){

        $this->delimitValues();

        //$this->infixToSuffix($buildstring);
    }

    private function delimitValues() {
        $toEvaluateArr = str_split($this->toEvaluate);
        $buildstring = '|';

        foreach ($toEvaluateArr as $char) {
            if ($char === '+' || $char === '-' || $char === '*' || $char === '/' || $char === '^'){
                $buildstring .= '|' . $char . '|';
            } else {
                $buildstring .= $char;
            }
        }

        $buildstring .= '|';

        var_dump($buildstring);

        $this->toEvaluate = $buildstring;
    }

    private function infixToSuffix() {
        $inputExpression = str_split($this->toEvaluate);
        $counter = 0;
        $tmpNumber = '';

        foreach ($inputExpression as $part){
            if(is_numeric($part) || $part === '|'){
                $tmpNumber .= $part;
            } else {
                $this->setOperator($part);
            }

            if ($tmpNumber !== '' && $part === '|'){
                $this->finishedExpression .= $tmpNumber;
                $tmpNumber = '';
            }
        }

        $this->emptyStack();

        var_dump($this->stack);
        var_dump($this->finishedExpression);

    }

    private function setOperator($part) {
        if(empty($this->stack)){
            $this->stack[] = $part;
            return;
        }

        $this->orderPrecedence($part);

    }

    private function orderPrecedence($part) {
        $partPrecedence = $this->setPrecedenceValue($part);
        $stackedPrecedence = $this->setPrecedenceValue(end($this->stack));

        while ($partPrecedence < $stackedPrecedence){
            $this->finishedExpression .= array_pop($this->stack);
            $stackedPrecedence = $this->setPrecedenceValue(end($this->stack));
            if(empty($this->stack)){
                $this->stack[] = $part;
                return;
            }
        }

        if ($partPrecedence === $stackedPrecedence){         //left association used meaning that first found (on stack)
            $this->finishedExpression .= array_pop($this->stack);    // will be set to string.
            $this->stack[] = $part;
        }

        if ($partPrecedence > $stackedPrecedence){
            $this->stack[] = $part;
        }


    }

    private function setPrecedenceValue($operator): int {
        if($operator ==='+' || $operator === '-'){
            return 10; // use constants to indicate order of precedence while leaving room for further development.
        }

        if($operator === '^'){
            return 20; // high order of precedence.
        }
        return 15; // normally all other operators have higher precedence.
    }

    private function emptyStack() {
        if(empty($this->stack)){
            return;
        }
        while (!empty($this->stack)){
            $this->finishedExpression .= array_pop($this->stack);
        }
    }
}