<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model gearsoftware\post\models\Category */

$this->title = Yii::t('core/media', 'Create Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('core/post', 'Posts'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('core/post', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('core', 'Create');
?>

<div class="post-category-create">
    <h3 class="lte-hide-title"><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', compact('model')) ?>
</div>