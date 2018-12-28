<?php

namespace api\controllers\team;

use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\team\Team;
use common\entities\team\TeamHistory;
use lib\helpers\Response;
use yii\rest\Controller;

final class TeamHistoryController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;

    public function actionById($team_id)
    {
        $item = Team::findByIdOrFail($team_id);
        $item->failIfAuthUserNotOwner();

        return Response::responseItems(
            TeamHistory::serialize($item->history)
        );
    }
}
