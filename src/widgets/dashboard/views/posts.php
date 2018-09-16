<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use gearsoftware\assets\core\CoreAsset;
use gearsoftware\comments\models\Comment;
use gearsoftware\helpers\Html;
use gearsoftware\widgets\TimeAgo;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $this yii\web\View */


CoreAsset::register($this);
?>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-control hidden-xs-down">
            <ul class="pager pager-rounded">
                <?php foreach ($posts as $post) : ?>
                    <li><a href="<?=  Url::to($post['url']); ?>"><?= $post['label'] . ' ('. $post['count'] . ')'; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <h3 class="panel-title"><?= Yii::t('core/post', 'Posts Activity'); ?></h3>
    </div>
    <div class="panel-body">
        <?php if (count($recentPosts)): ?>
            <?php foreach ($recentPosts as $post) : ?>
                <div class="media-block">
                    <div class="media">
                        <a class="media-left" href="<?= Url::to(['/user/default/update', 'id' => $post->author->id]) ?>"><img class="img-circle img-sm" alt="Profile Picture" src="<?= $post->author->getAvatar('large'); ?>"></a>
                        <div class="media-body mar-btm pad-btm">
                            <div>
                                <a href="<?= Url::to(['/user/default/update', 'id' => $post->author->id]) ?>" class="btn-link text-semibold media-heading box-inline"><?= HtmlPurifier::process($post->author->fullName); ?></a>
                                <?= Yii::t('core/post', 'posted'); ?>
                                <a href="<?= Url::to(['post/default/view', 'id' => $post->id]) ?>" class="text-semibold"><?= HtmlPurifier::process($post->title); ?></a>
                                <p>
                                    <small class="text-muted">
                                        <i class="ti-time icon-lg"> </i>
                                        <?php if(date('Ymd') == date('Ymd', $post->published_at)) : ?>
                                            <?= TimeAgo::widget(['timestamp' => $post->published_at]); ?>
                                        <?php else : ?>
                                            <?= $post->publishedDateTime; ?>
                                        <?php endif; ?>
                                    </small>
                                    <small class="text-muted pad-lft">
                                        <i class="demo-pli-speech-bubble-3 icon-lg "> </i>
                                        <span class="text-semibold"> <?= Comment::activeCount(\gearsoftware\post\models\Post::className(), $post->id) ?></span>
                                    </small>
                                </p>

                            </div>
                            <div class="<?= (end($recentPosts) !== $post) ? 'pad-btm bord-btm' : '' ?>">
                                <p>
                                    <?= HtmlPurifier::process(mb_substr(strip_tags($post->content), 0, 250, "UTF-8")); ?>
                                    <?= (strlen(strip_tags($post->content)) > 250) ? '...' : '' ?>
                                    <a  href="<?= Url::to($post->slug) ?>"  class="btn btn-trans"><?= Yii::t('core', 'Read more'); ?></a>
                                </p>
                                <?php if ($post->thumbnail): ?>
                                    <img class="img-responsive" src="<?= $post->thumbnail ?>" alt="Image">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <em><?= Yii::t('core/post', 'No posts found.') ?></em>
        <?php endif; ?>
    </div>
</div>