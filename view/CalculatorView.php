<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 20:04
 */

namespace view;


use model\LoginConstants;

class CalculatorView {
    private $totalInput;
    private $constants;
    private $previous;
    private $url;
    private $hiddenInput;

    public function __construct()
    {
        $this->constants = new LoginConstants();
        $this->url = $_SERVER['QUERY_STRING'];
    }


    public function contentToString(bool $isloggedIn) : string {
        return '
        <div>
        <h1>Hello to my calculator</h1>
        ' . $this->isLoggedInHTML($isloggedIn) . '
        ' . $this->formHTML() .'
        
        </div>
        ';
    }

    public function userDoingAction(): bool{
        if(isset($_POST['calculator'])){
            return true;
        }
        return false;
    }

    public function isEvaluatingAction() {
        if($this->getActionValue() === '='){
            return true;
        }
        return false;
    }

    public function getActionValue()
    {
        return $_POST['calculator'];
    }

    public function render(bool $loggedInStatus)
    {
        return $this->contentToString($loggedInStatus);
    }

    public function getHiddenValue()
    {
        return $_POST['hiddenField'];
    }

    public function updateInput(){
        $this->addOperator();
        $this->addNumber();
        $this->reset();
        $this->totalInput = $this->hiddenInput;
        
    }
    private function addOperator() {
        if($this->checkForOperator($this->getActionValue())){
            if($this->getHiddenValue() === ''){
                $this->hiddenInput = '';
            } else if($this->checkForOperator($this->getLastValueOfInput())){
                $this->hiddenInput = substr_replace($this->getHiddenValue(), $this->getActionValue(), -1);
            } else {
                $this->hiddenInput = $this->getHiddenValue() . $this->getActionValue();
            }
        }
    }

    private function addNumber() {
        if(is_numeric($this->getActionValue())) {
            $this->hiddenInput = $this->getHiddenValue() . $this->getActionValue();
        }
    }

    private function reset() {
        if ($this->getActionValue() === 'Reset'){
            $this->hiddenInput = '';
        }
    }

    public function calculatorPost(): bool
    {
        return isset($_POST['calculator']);
    }

    public function setHiddenValue($hiddenInput){
        $this->hiddenInput = $hiddenInput;
        $this->totalInput = $hiddenInput;
    }

    public function updateCalculatorWindow($newTotal) {

       $this->setHiddenValue($newTotal);
       $this->totalInput = $newTotal;
    }

    private function isLoggedInHTML(bool $isloggedIn): string {
        if($isloggedIn){
            return '<h2>You are logged in </h2>';
        }
        return '<h2> You are not logged in </h2>';
    }

    private function formHTML() {
        return ' <form method="post" action="' . '?'. $this->url . '" >
        <input type="hidden" value="' . $this->hiddenInput . '" name="hiddenField">
        <textarea> ' . $this->totalInput .' </textarea>
        <br>
            <input type="submit" value="1" name="calculator">
            <input type="submit" value="2" name="calculator">
            <input type="submit" value="3" name="calculator">
            <input type="submit" value="4" name="calculator">
            <input type="submit" value="5" name="calculator">
            <input type="submit" value="6" name="calculator">
            <input type="submit" value="7" name="calculator">
            <input type="submit" value="8" name="calculator">
            <input type="submit" value="9" name="calculator">
            <input type="submit" value="-" name="calculator">
            <input type="submit" value="+" name="calculator">
            <input type="submit" value="*" name="calculator">
            <input type="submit" value="/" name="calculator">
            <input type="submit" value="^" name="calculator">
            <input type="submit" value="0" name="calculator">
            <input type="submit" value="Reset" name="calculator">
            <input type="submit" value="=" name="calculator"> ';
    }

    private function checkForOperator(string $input): bool {
        return !(is_numeric($input) || $input === '=');
    }

    private function getLastValueOfInput() {
        return substr($this->getHiddenValue(), -1);
    }

}