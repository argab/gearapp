<?php

namespace api\forms;

use api\exceptions\ValidationException;
use yii\base\Model;

class ApiForm extends Model
{

    /**
     * @throws ValidationException
     */
    public function afterValidate()
    {
        if ($this->hasErrors())
        {
            foreach ($this->getErrors() as $k => $v)
            {
                throw new ValidationException($k, $v[0]);
            }
        }
        parent::afterValidate();
    }

    /**
     * @param array $params
     *
     * @return static
     */
    public static function loadAndValidate($params = [])
    {
        $form = new static($params);
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        return $form;
    }

}