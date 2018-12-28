<?php

namespace api\controllers;

use api\controllers\base\ApiAuthAndFilterController;
use api\forms\event\EventForm;
use common\base\Assert;
use common\dictionaries\EventType;
use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use common\dictionaries\Role;
use common\entities\event\Event;
use common\entities\user\User;
use common\forms\event\SearchEventForm;
use lib\helpers\Response;
use lib\services\EventService;
use yii\filters\AccessControl;
use yii\web\Request;

class EventController extends ApiAuthAndFilterController
{
    private $request;
    private $service;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class'        => AccessControl::class,
            'only'         => ['create', 'update', 'delete'],
            'rules'        => [
                [
                    'actions'       => ['create',  'update', 'delete'],
                    'allow'         => true,
                    'roles'         => [Role::R_ORGANIZER, Role::R_ADMIN],
                ],
            ],
        ];

        return $behaviors;
    }


    public function __construct($id,
        $module,
        Request $request,
        EventService $service,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->request = $request;
        $this->service = $service;
    }

    public function actionIndex()
    {
        $form = new SearchEventForm();
        $form->load($this->request->post(), '');
        $form->validate();

        $dataProvider = $this->service->search($form);

        $data = [
            'items' => Event::serialize($dataProvider->getModels()),
            'paginate' => [
                'page'       => $dataProvider->pagination->page + 1,
                'pageCount'  => $dataProvider->pagination->pageCount,
                'pageSize'   => $dataProvider->pagination->pageSize,
                'totalCount' => $dataProvider->pagination->totalCount,
            ],
        ];

        Response::responseSuccess($data, 200);
    }

    public function actionCreate()
    {
        $form = new EventForm();
        $form->load($this->request->post(), '');
        $form->validate();

        Assert::hasFormError($form);

        $item = $this->service->apiCreate($form);

        return Response::responseItem(Event::serializeItem($item));

    }

    public function actionUpdate($id)
    {
        $item = Event::findById($id);
        Assert::notFound($item);

        Assert::hasPermission($item->canUpdate());

        $form = new EventForm();
        $form->load($this->request->post(), '');
        $form->validate();
        Assert::hasFormError($form);

        $news = $this->service->apiUpdate($item, $form);

        return Response::responseItem(Event::serializeItem($news));

    }

    public function actionDelete($id)
    {
        $item = Event::findById($id);
        Assert::notFound($item);

        Assert::hasPermission($item->canDelete());

        $this->service->delete($item);

        return Response::responseSuccess([]);
    }

    public function actionById($id)
    {
        $item = Event::findById($id);
        Assert::notNull($item, 'Item not found');

        $item->addViews();

        return Response::responseItem(Event::serializeItem($item));
    }

    public function actionLike($id)
    {
        $item = Event::findById($id);
        Assert::notNull($item, 'Item not found');

        $item->setLike();

        return Response::success([]);
    }

    public function actionDislike($id)
    {
        $item = Event::findById($id);
        Assert::notNull($item, 'Item not found');

        $item->setDislike();

        return Response::success([]);
    }

    public function actionToFavorite($id)
    {
        $item = Event::findById($id);
        Assert::notNull($item, 'Item not found');

        $item->toFavorite();

        return Response::success([]);
    }

    public function actionUnFavorite($id)
    {
        $item = Event::findById($id);
        Assert::notNull($item, 'Item not found');

        $item->unFavorite();

        return Response::success([]);
    }


    public function actionDictionaries()
    {
        if ($this->request->get('type'))
            return Response::responseItems(EventType::allToResponse());

        return Response::responseItems([
            'type' => EventType::allToResponse(),
        ]);
    }

}
