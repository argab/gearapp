<?php

namespace lib\base;

use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * @property Query $query                      ;
 * @property ActiveRecord $model               ;
 */
class BaseController extends Controller
{
    protected $baseGetter = null;

    protected $getter = null;

    protected $getters = [];

    protected $modelTable = [];

    protected $modelName = null;

    protected $model = null;

    protected $query = null;

    protected $provider = null;

    protected $providerOptions = [];

    protected $pageSize = 25;

    protected $views = [];

    protected $errors = null;

    protected $responseMessage = [];

    protected $rend = true;

    protected $responseJson = false;

    public function createAction($id)
    {
        if ($this->baseGetter === null || empty($this->getters))

            throw new Exception('"$baseGetter" or "$getters" property is not defined.');

        foreach (array_diff(array_keys($this->getters), [$this->baseGetter]) as $getter)
        {
            if (strpos($id, $getter) !== false)
            {
                $this->getter = $getter;

                break;
            }
        }

        if ($this->getter === null)

            $this->getter = $this->baseGetter;

        $id = preg_replace('/\-?' . $this->getter . '\-?/i', '', $id);

        if (false == method_exists($this->getModel(), 'setTable'))

            throw new \Exception(sprintf('The `setTable` method not found in {%s} class.', get_class($this->model)));

        $this->getModel()->setTable($this->modelTable[$this->getter]);

        return parent::createAction($id);
    }

    public function getter()
    {
        return $this->getter ?: $this->baseGetter;
    }

    /**
     * @param int $id
     *
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function getModel($id = null)
    {
        if ($this->model === null)

            $this->setModel();

        if ($id !== null && ($this->model = $this->find($id)->query->one()) === null)

            throw new NotFoundHttpException('The requested page does not exist.');

        return $this->model;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function setModel()
    {
        if ($this->modelName === null || empty($this->modelTable))

            throw new Exception('"$modelName" or "$modelTable" property is not defined.');

        $this->model = new $this->modelName;

        $this->setQuery();

        $this->provider = null;

        return $this;
    }

    public function setQuery(ActiveQuery $query = null)
    {
        $this->query = $query;

        return $this;
    }

    public function find($id = 0, callable $set = null)
    {
        $this->query = $this->query ?: $this->getModel()->{$this->getters[$this->getter]}($id);

        if ($set !== null)

            $this->query = call_user_func($set, $this->query);

        return $this;
    }

    protected function search($params)
    {
        if (method_exists($this->getModel(), 'filter') && $this->getModel()->load($params))

            $this->getModel()->filter($this->query);

        return $this;
    }

    public function setDataProvider()
    {
        if ($this->provider === null)

            $this->provider = new ActiveDataProvider(
                array_merge(
                    (array) $this->providerOptions,
                    [
                        'query'      => $this->query,
                        'pagination' => [
                            'pageSize' => $this->pageSize,
                        ],
                    ]
                )
            );

        return $this;
    }

    public function actionIndex()
    {
        return $this->find()->search(Yii::$app->request->queryParams);
    }

    public function actionCreate(array $data = [])
    {
        if ($this->errors)

            return false;

        $this->getModel()->setScenario($this->model::SCENARIO_CREATE ?? $this->model::SCENARIO_DEFAULT);

        if ($this->getModel()->load($data ?: Yii::$app->request->post()))
        {
            if ($this->getModel()->validate() && $this->getModel()->save())
            {
                $this->errors = false;

                return $this->getModel()::getDb()->getLastInsertID();
            }
        }

        $this->errors = $this->getModel()->getErrors() ?: null;

        if (method_exists($this->getModel(), 'getDefaults'))

            $this->getModel()->getDefaults();

        return null;
    }

    public function actionUpdateAll(array $loadData = [])
    {
        if ($this->errors)

            return false;

        $data = $loadData ?: (Yii::$app->request->post()[$this->getter] ?? null);

        if ($data && $models = $this->find(array_keys($data))->query->all())
        {
            $key = ($this->model->tableSchema->primaryKey)[0] ?? null;

            if ($key === null)

                throw new Exception(get_class($this->model) . '\'s Primary Key is not set.');

            foreach ($models as $k => $model)
            {
                /* @var $model ActiveRecord */

                $model->setScenario($model::SCENARIO_UPDATE ?? $model::SCENARIO_DEFAULT);

                if (isset($model->formName))

                    $model->formName = $loadData ? '' : sprintf('%s[%s]', $this->getter, $model->{$key});

                if (isset($data[$model->{$key}]) && $model->load($data[$model->{$key}], ''))
                {
                    $model->validate();

                    if ($this->errors = $model->getErrors() ?: false)

                        return false;

                    $model->save();
                }
            }

            return true;
        }

        return false;
    }

    public function actionUpdate($id = 0, array $data = [])
    {
        if ($this->errors)

            return false;

        $this->getModel($id)->setScenario($this->model::SCENARIO_UPDATE ?? $this->model::SCENARIO_DEFAULT);

        if ($this->getModel()->load($data ?: Yii::$app->request->post()) && $this->getModel()->validate())
        {
            if ($this->getModel()->save())
            {
                return $id;
            }
        }

        $this->errors = $this->getModel()->getErrors() ?: false;

        return null;
    }

    public function actionDelete($id = null)
    {
        if ($this->errors)

            return false;

        $result = method_exists($this->getModel(), 'deleteItem')

            ? $this->getModel()->deleteItem($id) : $this->getModel($id)->delete();

        if ( ! $result)

            $this->errors = true;

        return $result;
    }

    public function afterAction($action, $result)
    {
        if ($this->rend)
        {
            switch ($action->id)
            {
                case 'index':

                    return $this->view();

                case 'view':

                    return $this->rend('view');

                case 'create':

                    return $result === null ? $this->rend('create') : $this->setModel()->actionView($result)->rend('view');

                case 'update':

                    return $result === null ? $this->rend('update') : $this->actionView($result)->rend('view');

                case 'update-all':

                    return $this->rend('update-all');

                case 'delete':

                    return $this->rend('delete');
            }
        }

        return parent::afterAction($action, $result);
    }

    public function view()
    {
        return $this->setDataProvider()->rend('index');
    }

    public function actionView($id = null)
    {
        $this->getModel($id);

        return $this->setDataProvider();
    }

    public function setRend($rend = true)
    {
        $this->rend = $rend;

        return $this;
    }

    public function setResponseMessage(& $messages = [])
    {
        $messages = array_merge(['errors' => null, 'success' => null], (array) $this->responseMessage);

        $msg = ['error' => [], 'success' => []];

        foreach ($messages as $k => $v)
        {
            if (strpos($k, 'error') !== false)
            {
                if ($messages[$k] = $this->errors ? ($this->errors === true ? $v : (array) $this->errors) : null)
                {
                    if (false == isAjax(false) && is_string($messages[$k]))

                        $msg['error'][] = $messages[$k];
                }
            }
            elseif (strpos($k, 'success') !== false)
            {
                if ($messages[$k] = $this->errors ? false : ($this->errors === null ? ($v ?: true) : true))
                {
                    if (false == isAjax(false) && is_string($messages[$k]))

                        $msg['success'][] = $messages[$k];
                }
            }
            else
            {
                $messages[$k] = $v;
            }
        }

        if ($msg['error'])

            Yii::$app->session->setFlash('mess_error', $msg['error']);

        if ($msg['success'])

            Yii::$app->session->setFlash('mess_success', $msg['success']);
    }

    public function rend($view, array $templateData = [], array $responseData = [])
    {
        $this->setResponseMessage($mess);

        if (null === ($view = $this->views[$this->getter][$view] ?? null))
        {
            if ($this->responseJson || Yii::$app->request->isAjax)

                return responseJson(array_merge($mess, $responseData));

            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($this->responseJson || Yii::$app->request->isAjax)

            return responseJson(array_merge($mess, [
                'html' => $this->getView()->render($view, array_merge([
                    'model'    => $this->getModel(),
                    'provider' => $this->provider
                ], $templateData), $this)
            ], $responseData));

        return $this->render($view, array_merge($mess, [
            'model'    => $this->getModel(),
            'provider' => $this->provider
        ], $templateData));
    }
}
