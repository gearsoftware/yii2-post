<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use gearsoftware\grid\GridView;
use gearsoftware\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \gearsoftware\post\models\search\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('core/media', 'Tags');
$this->params['breadcrumbs'][] = ['label' => Yii::t('core/post', 'Posts'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
	Html::a('<i class="ion-ios-plus-outline"></i> '. Yii::t('core', 'Add New'), ['create'], ['class' => 'btn btn-primary btn-sm'])
];

echo GridView::widget([
	'id' => 'post-tag-grid',
	'title' => $this->title,
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'bulkActionOptions' => [
		'actions' => [
			Url::to(['bulk-delete']) => Yii::t('core', 'Delete'),
		]
	],
	'columns' => [
		[
			'class' => 'gearsoftware\grid\columns\SerialColumn'
		],
		'title',
		'slug',
		[
			'class' => 'gearsoftware\grid\columns\ActionColumn',
			'template' => '{update}{delete}',
			'dropdown' => true,
		],
		[
			'class' => 'gearsoftware\grid\columns\CheckboxColumn',
		],
	]
]);