<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 20:04
 */

namespace view;
use model\CalculatorConstants;

class CalculatorView {
    private $totalInput;
    private $constants;
    private $url;
    private $hiddenInput;

    public function __construct()
    {
        $this->constants = new CalculatorConstants();
        $this->url = $_SERVER['QUERY_STRING'];
    }

    public function render(bool $isLoggedIn) : string {
        return '
        <div>
        <h1>Hello to my calculator</h1>
        ' . $this->isLoggedInHTML($isLoggedIn) . '
        ' . $this->formHTML() .'
        
        </div>
        ';
    }

    public function isEvaluatingAction(): bool {
        return $this->getUserInput() === $this->constants::getValueEqual();
    }

    public function calculatorPost(): bool
    {
        return isset($_POST[$this->constants::getCalculator()]);
    }

    public function getUserInput()
    {
        return $_POST[$this->constants::getCalculator()];
    }

    /*    public function render(bool $loggedInStatus): string {
            return $this->contentToString($loggedInStatus);
        }*/

    public function getExpression()
    {
        return $_POST[$this->constants::getHiddenExpression()];
    }

    public function updateInput(): void {
        $this->addOperator();
        $this->addNumber();
        $this->reset();
        $this->totalInput = $this->hiddenInput;
    }

    public function setHiddenValue($hiddenInput): void {
        $this->hiddenInput = $hiddenInput;
        $this->totalInput = $hiddenInput;
    }

    private function addOperator(): void {
        if($this->isUserInputOperator() && $this->getExpression() !== '' ){
            if($this->checkForOperator($this->getLastValueOfInput())){
                $this->replaceLastOperatorWithNew();
            } else {
                $this->addUserInputToExpression();
            }
        }
    }

    private function addNumber(): void {
        if(is_numeric($this->getUserInput())) {
            $this->addUserInputToExpression();
        }
    }

    private function reset(): void {
        if ($this->getUserInput() === $this->constants::getValueReset()){
            $this->hiddenInput = '';
        }
    }

    private function isUserInputOperator(): bool {
        return $this->checkForOperator($this->getUserInput());
    }

    private function checkForOperator(string $input): bool {
        return !(is_numeric($input) || $input === $this->constants::getValueEqual());
    }

    private function getLastValueOfInput() {
        return substr($this->getExpression(), -1);
    }

    private function replaceLastOperatorWithNew(): void {
        $this->hiddenInput = substr_replace($this->getExpression(), $this->getUserInput(), -1);
    }

    private function addUserInputToExpression(): void {
        $this->hiddenInput = $this->getExpression() . $this->getUserInput();
    }

    private function isLoggedInHTML(bool $isLoggedIn): string {
        if($isLoggedIn){
            return '<h2>You are logged in </h2>';
        }
        return '<h2> You are not logged in </h2>';
    }

    private function formHTML(): string {
        return ' <form method="post" action="' . '?'. $this->url . '" >
        <input type="hidden" value="' . $this->hiddenInput . '" name="' . $this->constants::getHiddenExpression() .'">
        <textarea> ' . $this->totalInput .' </textarea>
        <br>
            <input type="submit" value="' . $this->constants::getValue1() . '" name="' . $this->constants::getCalculator() . '">
            <input type="submit" value="' . $this->constants::getValue2() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue3() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue4() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue5() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue6() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue7() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue8() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue9() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValue0() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueAdd() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueSubtract() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueMultiply() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueDivide() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueExponent() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueReset() . '" name="' . $this->constants::getCalculator() .'">
            <input type="submit" value="' . $this->constants::getValueEqual() . '" name="' . $this->constants::getCalculator() .'"> ';
    }
}