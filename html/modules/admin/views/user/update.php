<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$model->password = '';

$this->title = 'Update User: ' . $model->username;
if (Yii::$app->user->can('adminInterface')) {
    $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
