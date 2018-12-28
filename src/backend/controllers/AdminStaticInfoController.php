<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\traits\TAdminController;
use Yii;

use backend\models\StaticInfo;

class AdminStaticInfoController extends Controller
{
    use TAdminController;

    protected function _accessRules()
    {
        return [];
    }

    private $_provider;

    private $_model;

    private $_query;

    public $pageSize = 25;

    public function getModel($get=true, $id=null)
    {
        if( ! $this->_model)
        {
            $this->_model = new StaticInfo;

            if($id !== null)
            {
                if(($this->_model = $this->_model->findOne($id)) === null)

                    throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $get ? $this->_model : $this;
    }

    public function getStaticInfo()
    {
        $this->_query = $this->getModel()->findInfo();

        return $this;
    }

    protected function search($params)
    {
        if ($this->getModel()->load($params) && $this->_model->validate())
        {
            $this->_query
                ->andFilterWhere(['=', StaticInfo::tableName() . '.id', $this->_model->id])
                ->andFilterWhere(['=', StaticInfo::tableName() . '.group_key', $this->_model->group_key])
                ->andFilterWhere(['like', StaticInfo::tableName() . '.key', $this->_model->key])
                ->andFilterWhere(['like', StaticInfo::tableName() . '.name', $this->_model->name])
                ->andFilterWhere(['like', StaticInfo::tableName() . '.value', $this->_model->value])
                ->andFilterWhere(['like', StaticInfo::tableName() . '.show', $this->_model->show])
                ->andFilterWhere(['like', StaticInfo::tableName() . '.priority', $this->_model->priority])
            ;
        }

        $this->_provider = new ActiveDataProvider([
            'query' => $this->_query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
            'sort'=> ['defaultOrder' => ['priority' => SORT_ASC]],
        ]);

        return $this;
    }

    public function actionIndex()
    {
        return $this->getStaticInfo()
            ->search(Yii::$app->request->queryParams)
            ->view()
        ;
    }

    public function actionSave()
    {
        if($data = Yii::$app->request->post('priority'))
        {
            $model = new StaticInfo;

            foreach ($data as $key => $val)
            {
                if($i = $model->findOne($key))
                {
                    $i->priority = $val;
                    $i->save();
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreate()
    {
        $this->getModel()->setScenario(StaticInfo::SCENARIO_CREATE);

        if ($this->_model->load(Yii::$app->request->post()) && $this->_model->save())
        {
            if($this->_model->errors)
            {
                Yii::$app->getSession()->setFlash('mess_error', $this->_model->errors);

                return $this->redirect(Yii::$app->request->referrer);
            }

            Yii::$app->getSession()->setFlash('mess_success', 'Изменения сохранены');

            return $this->redirect(['view', 'id' => $this->_model->id]);
        }
        else
        {
            Yii::$app->getSession()->setFlash('mess_error', $this->_model->errors);

            return $this->render('adm_static_info_create', [
                'model' => $this->_model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $this->getModel(true, $id)->setScenario(StaticInfo::SCENARIO_UPDATE);

        if ($this->_model->load(Yii::$app->request->post()) && $this->_model->save())
        {
            if($this->_model->errors)
                Yii::$app->getSession()->setFlash('mess_error', $this->_model->errors);
            else
                Yii::$app->getSession()->setFlash('mess_success', 'Изменения сохранены');

            return $this->redirect(['view', 'id' => $this->_model->id]);
        }
        else
        {
            if($this->_model->errors)

                Yii::$app->getSession()->setFlash('mess_error', $this->_model->errors);

            if( ! $item = $this->getItem($id))

                return $this->redirect('index');

            return $this->render('adm_static_info_update', [
                'model' => $item,
            ]);
        }
    }

    protected function getItem($id)
    {
        $this->getStaticInfo();

        return $this->_query->where([StaticInfo::tableName() . '.id' => $id])->one();
    }

    public function actionDelete($id)
    {
        $this->getModel()->deleteItem($id);

        Yii::$app->getSession()->setFlash('mess_success', 'Изменения сохранены');

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function view()
    {
        return $this->render('adm_static_info', [
            'provider' => $this->_provider,
            'model' => $this->_model,
        ]);
    }

    public function actionView($id)
    {
        if( ! $item = $this->getItem($id))

            return $this->redirect('index');

        return $this->render('adm_static_info_view', [
            'model' => $item,
        ]);
    }

}
