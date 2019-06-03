<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\helpers\StatusHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'username',
            'authKey',
            'accessToken',
            [
                'attribute' => 'status',
                'content' => function ($data) {
                    /* @var $data \app\models\User */
                    return StatusHelper::statusFullText($data);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
