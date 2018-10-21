<?php

namespace model;

class CalculatorModel
{
    public function calculateInput($hiddenValue) {
        $calculator = new CalculatorEvaluator($hiddenValue);
        return $calculator->getCalculatedValue();
    }
}