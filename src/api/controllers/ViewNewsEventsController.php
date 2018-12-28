<?php

namespace api\controllers;

use api\controllers\base\ApiAuthAndFilterController;
use api\forms\news\NewsForm;
use common\base\Assert;
use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use common\entities\news\News;
use common\forms\news\SearchNewsForm;
use common\forms\news\SearchViewNewsEventsForm;
use lib\helpers\Response;
use lib\services\NewsService;
use lib\services\ViewNewsEventService;
use yii\web\Request;

class ViewNewsEventsController extends ApiAuthAndFilterController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ViewNewsEventService
     */
    private $service;


    public function __construct($id,
        $module,
        Request $request,
        ViewNewsEventService $service,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->request = $request;
        $this->service = $service;
    }
    
    public function actionIndex()
    {
        $form = new SearchViewNewsEventsForm();
        $form->load($this->request->post(), '');
        $form->validate();

        $dataProvider = $this->service->search($form);

        $data = [
            'items'    => $this->service->serializeItems($dataProvider->getModels()),
            'paginate' => [
                'page'       => $dataProvider->pagination->page + 1,
                'pageCount'  => $dataProvider->pagination->pageCount,
                'pageSize'   => $dataProvider->pagination->pageSize,
                'totalCount' => $dataProvider->pagination->totalCount,
            ],
        ];

        Response::responseSuccess($data, 200);
    }

}
