<?php

use akiraz2\stat\models\WebVisitor;
use akiraz2\stat\Module;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \akiraz2\stat\models\WebVisitorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('stat', 'WebStat Dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="content-top">
        <div class="col-md-4">
            <div class="content-top-1">
                <h3><?= Module::t('stat', 'Own Counter'); ?></h3>
                <p><?= Module::t('stat', 'Direct'); ?>: <?= $counter_direct; ?></p>
                <p><?= Module::t('stat', 'Search'); ?>: <?= $counter_search; ?></p>
                <p><?= Module::t('stat', 'Ads'); ?>: <?= $counter_ads; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="content-top-1">

            </div>
        </div>
        <div class="col-md-4">
            <div class="content-top-1">

            </div>
        </div>
        <div class="col-md-12 content-top-2">
            <?php if ($searchModel->getModule()->ownStat) { ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'cookie_id',
                        'user_id',
                        [
                            'attribute' => 'source',
                            'value' => function ($model) {
                                return $model->getSource();
                            },
                            'filter' => Html::activeDropDownList(
                                $searchModel, 'source', WebVisitor::getSourceList(), ['class' => 'form-control', 'prompt' => 'Все']
                            ),
                        ],
                        'ip_address',
                        'visits',
                        'created_at',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
