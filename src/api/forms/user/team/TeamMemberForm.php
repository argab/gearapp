<?php

namespace api\forms\user\team;

use api\forms\ApiForm;

class TeamMemberForm extends ApiForm
{
    public $user_label;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_label', 'trim'],
            ['user_label', 'required'],
            ['user_label', 'string', 'max' => 255],
        ];
    }
}
