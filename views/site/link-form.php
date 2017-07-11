<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LinkForm */
/* @var $form ActiveForm */
?>
<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'link') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Parse', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- link-form -->
