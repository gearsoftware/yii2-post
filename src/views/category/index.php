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
use gearsoftware\post\models\Category;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \gearsoftware\media\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('core/media', 'Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('core/post', 'Posts'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
	Html::a('<i class="ion-ios-plus-outline"></i> '. Yii::t('core', 'Add New'), ['create'], ['class' => 'btn btn-primary btn-sm'])
];

echo GridView::widget([
	'id' => 'post-category-grid',
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
		[
			'attribute' => 'parent_id',
			'label' => Yii::t('core', 'Parent'),
			'value' => function (Category $model) {
				if ($parent = $model->getParent()->joinWith('translations')->one() AND $parent->id > 1) {
					return Html::a($parent->title, ['update', 'id' => $parent->id], ['data-pjax' => 0]);
				} else {
					return null;
				}
			},
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => Category::getCategories(),
			'filterWidgetOptions' => [
				'pluginOptions' => ['allowClear' => true],
			],
			'filterInputOptions' => [
				'placeholder' => Yii::t('core', 'Select an {element}...', ['element' => Yii::t('core', 'Category')]),
				'encodeSpaces' => true,
			],
			'format' => 'raw'
		],
		'description:ntext',
		[
			'class' => 'gearsoftware\grid\columns\StatusColumn',
			'attribute' => 'visible',
			'filterType' => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
				'pluginOptions' => ['allowClear' => true],
			],
			'filterInputOptions' => [
				'placeholder' => Yii::t('core', 'Select a {element}...', ['element' => Yii::t('core', 'Status')])
			],
			'format' => 'raw'
		],
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