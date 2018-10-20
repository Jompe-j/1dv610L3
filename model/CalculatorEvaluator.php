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
    private $postFixedExpression;
    private $expressionToPostFix;

    public function __construct($input) {
        $this->toEvaluate = $input;
        $this->calculate();
        $this->processPostFixedExpression();
    }

    public function getCalculatedValue() {
        return array_shift($this->stack);
    }

    private function calculate(): void {
        $this->buildStringToSuffixFix();
        $this->infixToSuffix();

    }
    /**
     * @param $hiddenValue
     * @return string
     */
    private function buildStringToSuffixFix() {
        $this->delimitValues();
    }

    private function delimitValues(): void {
        $toEvaluateArr = str_split($this->toEvaluate);
        $tmp = '';
        $separatedInputs = Array();
        foreach ($toEvaluateArr as $char){
             if (is_numeric($char)){
                 $tmp .= $char;
             }   else {
                 $separatedInputs[] = $tmp;
                 $separatedInputs[] = $char;
                 $tmp = '';
             }
        }
        $separatedInputs[] = $tmp;
        $this->expressionToPostFix = $separatedInputs;
        var_dump($separatedInputs);
    }

    private function infixToSuffix(): void {
        foreach ($this->expressionToPostFix as $value){
            if(is_numeric($value)){
                $this->postFixedExpression[] = $value;
            } else {
                $this->setOperator($value);
            }
        }
        $this->emptyStack();
        var_dump($this->postFixedExpression);
    }

    private function setOperator($part): void {
        if(empty($this->stack)){
            $this->stack[] = $part;
            return;
        }
        $this->orderPrecedence($part);

    }

    private function orderPrecedence($part): void {
        $partPrecedence = $this->setPrecedenceValue($part);
        $stackedPrecedence = $this->setPrecedenceValue(end($this->stack));

        while ($partPrecedence < $stackedPrecedence){
            $this->setPostFixedExpressionFromStack();
            $stackedPrecedence = $this->setPrecedenceValue(end($this->stack));
            if(empty($this->stack)){
                $this->stack[] = $part;
                return;
            }
        }

        while ($partPrecedence < $stackedPrecedence){
            $this->setPostFixedExpressionFromStack();
            $stackedPrecedence = $this->setPrecedenceValue(end($this->stack));
            if(empty($this->stack)){
                $this->stack[] = $part;
                return;
            }
        }

        if ($partPrecedence === $stackedPrecedence){         //left association used meaning that first found (on stack)
            $this->setPostFixedExpressionFromStack();    // will be set to string.
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

    private function emptyStack(): void {
        if(empty($this->stack)){
            return;
        }
        while (!empty($this->stack)){
            $this->setPostFixedExpressionFromStack();
        }
    }

    private function setPostFixedExpressionFromStack(): void {
        $this->postFixedExpression[] = array_pop($this->stack);
    }

    private function processPostFixedExpression(){
        foreach ($this->postFixedExpression as $value){
            if (is_numeric($value)){
                $this->stack[] = $value;
            } else {
                $rightVal = array_pop($this->stack);
                $leftVal = array_pop($this->stack);

                switch ($value){ // avoid using eval().
                    case '+':
                        $this->stack[] = $leftVal + $rightVal;
                        break;

                    case '-':
                        $this->stack[] = $leftVal - $rightVal;
                        break;

                    case '*':
                        $this->stack[] = $leftVal * $rightVal;
                        break;

                    case '/':
                        $this->stack[] = $leftVal / $rightVal;
                        break;

                    case '^':
                        $this->stack[] = $leftVal ** $rightVal;
                        break;
                }
            }
        }
    }
}