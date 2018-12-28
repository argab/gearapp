<?php

namespace api\forms;

use common\entities\team\Team;
use yii\base\Model;

class TeamForm extends Model
{
    public $team_id;

    public function rules(): array
    {
        return [
            [['team_id'], 'exist', 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
        ];
    }


}
