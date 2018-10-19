<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 20:04
 */

namespace view;


use model\LoginConstants;

class CalculatorView implements IContentView {
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


    public function contentToString() : string {
        return '
        <div>
        <h1>Hello to my calculator</h1>
         
        <form method="post" action="' . '?'. $this->url . '" >
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
            <input type="submit" value="=" name="calculator">
        </div>
        ';
    }

    public function userDoingAction(): bool{
        if(isset($_POST['calculator'])){
            return true;
        }
        return false;
    }

    public function getActionValue()
    {
        return $_POST['calculator'];
    }

    public function printValue($getActionValue)
    {
        $this->totalInput = $getActionValue;
    }

    public function render()
    {
        return $this->contentToString();
    }

    public function getHiddenValue()
    {
        return $_POST['hiddenField'];
    }

    public function setTotalInput($input){
        $this->totalInput = $input;
    }

    public function setPrevious()
    {
        if(isset($_POST[$this->constants::getToRegister()])){
            $this->previous = $_POST[$this->constants::getToRegister()];
        }
    }

    public function calculatorPost(): bool
    {
        return isset($_POST['calculator']);
    }

    public function setHiddenValue($hiddenInput){
        $this->hiddenInput = $hiddenInput;
    }

    public function updateCalculatorWindow($newTotal) {

       $this->setHiddenValue($newTotal);
       $this->totalInput = $newTotal;
    }


}