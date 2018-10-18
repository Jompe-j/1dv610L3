<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-18
 * Time: 11:54
 */

namespace model;


class CalculatorModel
{

    private $actionValue;

    public function actOnAction($getActionValue){
        $this->actionValue = $getActionValue;
    }

    public function getActionValue(){
        return $this->actionValue;
    }
}