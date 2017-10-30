<?php use yii\helpers\Html;?>
<p>You have entered the following information:</p>

<br><br>
<ul>
    <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
</ul