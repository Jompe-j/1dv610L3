<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-18
 * Time: 11:27
 */

namespace controller;


use view\CalculatorView;

class CalculatorController
{

    private $calculator;
    private $calculatorModel;

    public function __construct(CalculatorView $calculator){
        $this->calculator = new CalculatorView();
        $this->calculatorModel = new \model\CalculatorModel();
    }

    public function isCalculatorPosting(): bool
    {
        return $this->calculator->calculatorPost();
    }

    public function getUpdatedCalculator(): CalculatorView
    {
       $this->calculator->updateInput();
       $input = $this->calculator->getHiddenValue();
        if($this->calculator->isEvaluatingAction() ){
            $result = $this->calculatorModel->calculateInput($input);
            $this->calculator->setHiddenValue($result);
        }
        return $this->calculator;
    }
}