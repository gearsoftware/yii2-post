<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

use gearsoftware\db\TranslatedMessagesMigration;

class m170731_062450_i18n_es_core_post extends TranslatedMessagesMigration
{
	public function getLanguage()
	{
		return 'es-ES';
	}

	public function getCategory()
	{
		return 'core/post';
	}

	public function getTranslations()
	{
		return [
			'Create Tag' => 'Crear etiqueta',
			'Update Tag' => 'Actualizar etiqueta',
			'No posts found.' => 'No se han encontrado circulares.',
			'Post' => 'Circular',
			'Posted in' => 'Publicado en',
			'Posts Activity' => 'Actividad de las circulares',
			'Posts' => 'Circulares',
			'Tag' => 'Etiqueta',
			'Tags' => 'Etiquetas',
			'Thumbnail' => 'Miniatura',
			'posted' => 'publicó',
		];
	}
}
