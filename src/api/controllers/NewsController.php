<?php

namespace api\controllers;

use api\controllers\base\ApiAuthAndFilterController;
use api\forms\news\NewsForm;
use common\base\Assert;
use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use common\entities\news\News;
use common\forms\news\SearchNewsForm;
use lib\helpers\Response;
use lib\services\NewsService;
use yii\web\Request;

class NewsController extends ApiAuthAndFilterController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var NewsService
     */
    private $newsService;

    
    public function __construct($id,
        $module,
        Request $request,
        NewsService $newsService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->request = $request;
        $this->newsService = $newsService;
    }
    
    public function actionIndex()
    {
        $form = new SearchNewsForm();
        $form->load($this->request->post(), '');
        $form->validate();
        
        $dataProvider = $this->newsService->search($form);

        $data = [
            'items'    => News::serialize($dataProvider->getModels()),
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
        $form = new NewsForm();
        $form->load($this->request->post(), '');
        $form->validate();

        Assert::hasFormError($form);

        $news = $this->newsService->apiCreate($form);
        
        return Response::responseItem(News::serializeItem($news));
        
    }
    
    public function actionUpdate($id)
    {
        $item = News::findById($id);
        Assert::notFound($item);
    
        Assert::hasPermission($item->canUpdate());

        $form = new NewsForm();
        $form->load($this->request->post(), '');
        $form->validate();
        Assert::hasFormError($form);

        $news  = $this->newsService->apiUpdate($item, $form);

        return Response::responseItem(News::serializeItem($news));

    }

    public function actionDelete($id)
    {
        $item = News::findById($id);
        Assert::notFound($item);

        Assert::hasPermission($item->canDelete());
        
        $this->newsService->delete($item);

        return Response::responseSuccess([]);
    }

    public function actionById($id)
    {
        $item = News::findActive($id);
        Assert::notNull($item, 'Item not found');

        $item->addViews();

        return Response::responseItem(News::serializeItem($item));
    }

    public function actionLike($id)
    {
        $item = News::findActive($id);
        Assert::notNull($item, 'Item not found');

        $item->setLike();

        return Response::success([]);
    }

    public function actionDislike($id)
    {
        $item = News::findActive($id);
        Assert::notNull($item, 'Item not found');

        $item->setDislike();

        return Response::success([]);
    }

    public function actionToFavorite($id)
    {
        $item = News::findActive($id);
        Assert::notNull($item, 'Item not found');

        $item->toFavorite();

        return Response::success([]);
    }

    public function actionUnFavorite($id)
    {
        $item = News::findActive($id);
        Assert::notNull($item, 'Item not found');

        $item->unFavorite();

        return Response::success([]);
    }

    public function actionDictionaries()
    {
        if ($this->request->get('type'))
            return Response::responseItems(NewsType::allToResponse());
        
        if ($this->request->get('status'))
            return Response::responseItems(NewsStatus::allToResponse());
        
        return Response::responseItems([
            'type'   => NewsType::allToResponse(),
            'status' => NewsStatus::allToResponse()
        ]);
    }
    
}
