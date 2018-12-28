<?php

namespace lib\grid;

use lib\grid\IGridProvider;

interface IGridFormProvider extends IGridProvider
{
    public function fieldTypes(): array;

    public function inputTypes(): array;

    public function getInputOptions(): array;

    public function getErrorMessages(): array;

    public function getSafeInputs(): array;
}
