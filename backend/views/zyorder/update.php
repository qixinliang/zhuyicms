<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ZyOrder */

$this->title = '更新订单: ' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Zy Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="zy-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
