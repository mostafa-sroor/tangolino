<?php
use yii\bootstrap\Progress;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\FirstWidget ;
use yii\helpers\HtmlPurifier;
?>
<?= Progress::widget(['percent' => 60, 'label' => 'Progress 60%']) ?>

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'email') ?>

<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>
<?php ActiveForm::end(); ?>
<?= FirstWidget::widget() ;?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        This is the About page. You may modify the following file to customize its content:
    </p> <p>
        <?= Html::encode("<script>alert('alert!');</script><h1>ENCODE EXAMPLE</h1>>") ?>
    </p> <p>
        <?= HtmlPurifier::process("<script>alert('alert!');</script><h1> HtmlPurifier EXAMPLE</h1>") ?>
    </p>
    <code><?= __FILE__ ?></code>
</div>

