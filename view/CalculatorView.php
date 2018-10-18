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
    private $input;
    private $constants;
    private $previous;
    private $url;

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
        <textarea> ' . $this->input .' </textarea>
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
        $this->input = $getActionValue;
    }

    public function render()
    {
        return $this->contentToString();
    }

    public function setPrevious()
    {
        if(isset($_POST[$this->constants::getToRegister()])){
            $this->previous = $_POST[$this->constants::getToRegister()];
        }
    }

}