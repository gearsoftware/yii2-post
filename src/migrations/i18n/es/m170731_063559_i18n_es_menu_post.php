<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

class m170731_063559_i18n_es_menu_post extends yii\db\Migration
{
	public function up()
	{
		$this->insert('{{%menu_link_lang}}', ['link_id' => 'post', 'label' => 'Circulares', 'language' => 'es-ES']);
		$this->insert('{{%menu_link_lang}}', ['link_id' => 'post-post', 'label' => 'Circulares', 'language' => 'es-ES']);
		$this->insert('{{%menu_link_lang}}', ['link_id' => 'post-category', 'label' => 'Categorías', 'language' => 'es-ES']);
	}
}
