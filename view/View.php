<?php

namespace view;


class View {

    private $layoutView;

    public function __construct()
    {
        $this->layoutView = new LayoutView();

    }

}
