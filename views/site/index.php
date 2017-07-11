<?php

/* @var $this yii\web\View */
/* @var $model \app\models\LinkForm */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <?= $this->render('link-form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
