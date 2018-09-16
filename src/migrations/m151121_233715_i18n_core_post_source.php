<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use gearsoftware\db\SourceMessagesMigration;

class m151121_233715_i18n_core_post_source extends SourceMessagesMigration
{

    public function getCategory()
    {
        return 'core/post';
    }

    public function getMessages()
    {
        return [
            'Create Tag' => 1,
            'Update Tag' => 1,
            'No posts found.' => 1,
            'Post' => 1,
            'Posted in' => 1,
            'Posts Activity' => 1,
            'Posts' => 1,
            'Tag' => 1,
            'Tags' => 1,
            'Thumbnail' => 1,
	        'posted' => 1,
        ];
    }
}