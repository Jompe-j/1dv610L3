<?php

namespace controller;

use view\CalculatorView;

class CalculatorController
{
    private $calculator;
    private $calculatorModel;

    public function __construct(){
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
        if($this->calculator->isEvaluatingAction() ){
            $input = $this->calculator->getExpression();
            $result = $this->calculatorModel->calculateInput($input);
            $this->calculator->setHiddenValue($result);
        }
        return $this->calculator;
    }
}