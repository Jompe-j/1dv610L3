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
    private $clearedString = '';
    private $returnString = '';
    private $calculatorString = '|';

    public function actOnAction($getActionValue){
        $this->actionValue = $getActionValue;
    }

    public function getActionValue(){
        return $this->actionValue;
    }

    public function actionHandler($actionValue, $hiddenValue): string {
        if($this->isReset($actionValue)){
            return $this->clearedString;
        }

        if($this->isEvaluateAction($actionValue)){
            return $this->getValueOfEvaluatedString($hiddenValue);
        }

        return $hiddenValue . $actionValue;
    }

    private function getValueOfEvaluatedString($hiddenValue): string {

        $evaluator = new CalculatorEvaluator($hiddenValue);


        return $hiddenValue;
    }

    private function isReset($action) {
        if (\strlen($action) > 1){
            return true;
        }
        return false;
    }



    private function isEvaluateAction($actionValue) {

        if($actionValue === '='){
            return true;
        }
        return false;
    }



}