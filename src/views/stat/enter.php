<?php
use yii\helpers\Html;
use akiraz2\stat\AlertWidget;
?>


<div id="enter">


    <?= AlertWidget::widget() ?>


    <div class="hentry group">

        <h3>Статистика посещений</h3>
        <?= Html::beginForm(['forms'], 'post', ['class'=>'form-horizontal']) ?>

        <div class="form-group">
            <label for="Ввод пароля" class="control-label">Ввод пароля</label>
            <input name="password" type="text">
        </div>

        <input name="enter" type="hidden" value="1">
        <button class="button-reset" type="submit">Войти</button>

        <?= Html::endForm() ?>


    </div>
</div>