<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\assets;

use yii\web\AssetBundle;

class MagicSuggestAsset extends AssetBundle
{

    public $sourcePath = '@vendor/nicolasbize/magicsuggest';
    public $css = [
        'magicsuggest-min.css'
    ];
    public $js = [
        'magicsuggest-min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
