<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-18
 * Time: 11:27
 */

namespace controller;


use http\QueryString;
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

 /*   public function handleAction()
    {
        $this->calculatorModel->actOnAction($this->calculator->getActionValue());
        $this->calculator->printValue($this->calculatorModel->getActionValue());

    }*/

    public function getUpdatedCalculator(): CalculatorView
    {
        $newTotal = $this->calculatorModel->actionHandler(
            $this->calculator->getActionValue(),
            $this->calculator->getHiddenValue()
        );

        $this->calculator->updateCalculatorWindow($newTotal);
        return $this->calculator;

    }
}