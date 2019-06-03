<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\admin\controllers\UserController;
use yii\filters\AccessControl;

/**
 * Класс наследник UserController из админки.
 * Просмотр/редактирование пользователем самого себя.
 * Используются вьюхи админки, подразумеваенся допиливание
 * "под проект".
 */
class DefaultController extends UserController
{
    public $defaultAction = 'view';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $tab = parent::behaviors();
        $tab['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'update'],
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];

        return $tab;
    }

    public function init()
    {
        parent::init();
        // Use views in admin modul.
        $this->setViewPath("@app/modules/admin/views/user");
    }

    /**
     * Renders view for the User model.
     * @param null $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null)
    {
        $viewsData = parent::actionView(Yii::$app->user->getId());
        return $viewsData;
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id = null)
    {
        return parent::actionUpdate(Yii::$app->user->getId());
    }

}
