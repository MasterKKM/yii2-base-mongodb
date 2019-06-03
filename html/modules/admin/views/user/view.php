<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\helpers\StatusHelper;
use app\modules\admin\models\RolesModel;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
if (Yii::$app->user->can('adminInterface')) {
    $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?php
        if (Yii::$app->user->can('adminInterface')) {
            echo Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'username',
            'password',
            'authKey',
            'accessToken',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    /* @var $data \app\models\User */
                    return StatusHelper::statusFullText($data);
                }
            ],
        ],
    ]) ?>

    <div style="padding: 10px;border:1px solid #dddddd">
        <?php
        $roles = new RolesModel($model);
        if (Yii::$app->user->can('adminInterface')) {
            // Forms for edit user roles.
            $form = ActiveForm::begin(['action' => ['roles', 'id' => (string)$model->_id]]);
            foreach ($roles->attributeLabels() as $nameItem => $attributeLabel) {
                echo $form->field($roles, $nameItem)->checkbox();
            }
            echo '<div class="form-group">' . Html::submitButton('Save', ['class' => 'btn btn-success']) . '</div>';
            ActiveForm::end();
        } else {
            foreach ($roles->attributeLabels() as $nameItem => $attributeLabel) {
                if (Yii::$app->user->can($nameItem)) {
                    echo '<p>' . $attributeLabel . '</p>';
                }
            }
        }
        ?>
    </div>
</div>
