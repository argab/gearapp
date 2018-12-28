<?php

namespace common\traits;

use Yii;
use yii\db\ActiveRecord;

use lib\grid\GridForm;

trait TGridForm
{
    protected $inputTypes = null;

    protected $fieldTypes = null;

    protected $fieldNames = null;

    protected $safeInputs = ['id', 'created_at', 'updated_at'];

    public function fieldNames(): array
    {
        /* @var $this ActiveRecord */

        if ($this->attributeLabels())

            return $this->attributeLabels();

        if ($this->fieldNames === null)

            $this->inputTypes();

        if (empty($this->fieldNames) && $this->inputTypes)
        {
            foreach ($this->inputTypes as $k => $v)
            {
                $this->fieldNames[$k] = $k;

                $this->fieldTypes[$k] = $v;
            }
        }

        return $this->fieldNames;
    }

    public function fieldTypes(): array
    {
        if ($this->fieldTypes === null)

            $this->inputTypes();

        return $this->fieldTypes;
    }

    public function inputTypes(): array
    {
        /* @var $this ActiveRecord */

        if ($this->inputTypes === null)
        {
            if (false == ($this instanceof ActiveRecord))

                throw new \Exception('inputTypes property is empty!');

            foreach ($this->getTableSchema()->columns as $col)
            {
                $this->fieldNames[$col->name] = $col->name;

                $this->fieldTypes[$col->name] = $col->phpType;

                if (strpos($col->dbType, 'tinyint') !== false || $col->type === 'smallint')
                {
                    $this->inputTypes[$col->name] = $col->size < 2 ? 'radio' : 'select';
                }
                elseif (strpos($col->dbType, 'text') !== false)
                {
                    $this->inputTypes[$col->name] = 'textarea';
                }
                else
                {
                    $this->inputTypes[$col->name] = $col->type;
                }
            }
        }

        return $this->inputTypes;
    }

    public function getErrorMessages(): array
    {
        /* @var $this ActiveRecord */

        return $this->getFirstErrors();
    }

    public function getInputOptions(): array
    {
        return [];
    }

    public function getSafeInputs(): array
    {
        return $this->safeInputs;
    }

}
