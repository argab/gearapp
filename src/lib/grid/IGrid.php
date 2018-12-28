<?php

namespace lib\grid;

interface IGrid
{
    public function setViewPath($path);

    public function render($template);
}
