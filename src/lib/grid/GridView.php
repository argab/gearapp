<?php

namespace lib\grid;

use lib\grid\Grid;

class GridView extends Grid
{
    protected $tag = 'table';

    public function render($template = 'grid-view/view.php')
    {
        return parent::render($template);
    }
}
