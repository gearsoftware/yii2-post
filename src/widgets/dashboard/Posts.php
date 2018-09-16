<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\widgets\dashboard;

use gearsoftware\models\User;
use gearsoftware\post\models\Post;
use gearsoftware\post\models\search\PostSearch;
use gearsoftware\widgets\DashboardWidget;
use Yii;

class Posts extends DashboardWidget
{
    /**
     * Most recent post limit
     */
    public $recentLimit = 5;

    /**
     * Post index action
     */
    public $indexAction = 'post/default/index';

    /**
     * Total post options
     *
     * @var array
     */
    public $options;

    public function run()
    {
        if (!$this->options) {
            $this->options = $this->getDefaultOptions();
        }

        if (User::hasPermission('viewPosts')) {
            $searchModel = new PostSearch();
            $formName = $searchModel->formName();

            $recentPosts = Post::find()->multilingual()->orderBy(['id' => SORT_DESC])->limit($this->recentLimit)->all();

            foreach ($this->options as &$option) {
                $count = Post::find()->filterWhere($option['filterWhere'])->count();
                $option['count'] = $count;
                $option['url'] = [$this->indexAction, $formName => $option['filterWhere']];
            }

            return $this->render('posts', [
                'height' => $this->height,
                'width' => $this->width,
                'position' => $this->position,
                'posts' => $this->options,
                'recentPosts' => $recentPosts,
            ]);
        }
    }

    public function getDefaultOptions()
    {
        return [
            ['label' => Yii::t('core', 'All'), 'icon' => 'ok', 'filterWhere' => []],
            ['label' => Yii::t('core', 'Published'), 'icon' => 'ok', 'filterWhere' => ['status' => Post::STATUS_PUBLISHED]],
            ['label' => Yii::t('core', 'Pending'), 'icon' => 'search', 'filterWhere' => ['status' => Post::STATUS_PENDING]],
        ];
    }
}