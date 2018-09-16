<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use yii\db\Migration;

class m160414_212558_add_view_post extends Migration
{
    const POST_TABLE = '{{%post}}';
    
    public function safeUp()
    {
        $this->addColumn(self::POST_TABLE, 'view', $this->string(255)->notNull()->defaultValue('post'));
        $this->addColumn(self::POST_TABLE, 'layout', $this->string(255)->notNull()->defaultValue('main'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::POST_TABLE, 'view');
        $this->dropColumn(self::POST_TABLE, 'layout');
    }
}