<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\models;

use omgdef\multilingual\MultilingualTrait;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Post
 */
class PostQuery extends \yii\db\ActiveQuery
{

    use MultilingualTrait;

    public function active()
    {
        $this->andWhere(['status' => Post::STATUS_PUBLISHED]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
