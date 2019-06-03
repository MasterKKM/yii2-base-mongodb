<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\helpers\StatusHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ($model->isNewRecord ? $form->field($model, 'username') : '') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'authKey') ?>

    <?= $form->field($model, 'accessToken') ?>

    <?= $form->field($model, 'status')->dropDownList(StatusHelper::getAllStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
