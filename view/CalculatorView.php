<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 20:04
 */

namespace view;


class CalculatorView implements IContentView {


    public function contentToString() : string {
        return '
        <div>
        <h1>Hello to my calculator</h1> 
        </div>
        ';

    }
}