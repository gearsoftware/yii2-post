<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use gearsoftware\post\assets\MagicSuggestAsset;

class MagicSuggest extends InputWidget
{

    /**
     * @var string placeholder text
     */
    public $placeholder = null;

    /**
     * @var string no_results_text text
     */
    public $noResultsText = null;

    /**
     * @var array items array to render select options
     */
    public $items = [];

    /**
     * @var array options for plugin
     * @see http://nicolasbize.com/magicsuggest/doc.html
     */
    public $clientOptions = [
        'hideTrigger' => true,
        'toggleOnClick' => true,
    ];

    /**
     * @var array event handlers for plugin
     * @see http://nicolasbize.com/magicsuggest/doc.html
     */
    public $clientEvents = [];

    /**
     * @var string name of js variable
     */
    protected $var;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->var = str_replace('-', '_', $this->options['id']);

        //$this->clientOptions['placeholder_text_single'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select an option');
        //$this->clientOptions['placeholder_text_multiple'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select some options');
        //$this->clientOptions['no_results_text'] = \Yii::t($this->translateCategory, $this->noResultsText ? $this->noResultsText : 'No results match');
        //if (empty($this->clientEvents)) {
        //    $this->clientEvents['selectionchange'] = "function(){alert(JSON.stringify(this.getSelection()));}";
        //}

        $this->clientOptions['data'] = $this->items;
	    //$this->clientOptions['allowFreeEntries'] = false;
	    //$this->clientOptions['selectFirst'] = true;
	    $this->clientOptions['useTabKey'] = true;

	    $this->registerTranslation();
	    $this->registerStyle();
        $this->registerScript();
        $this->registerEvents();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
    }

    /**
     * Registers script
     */
    public function registerScript()
    {
        MagicSuggestAsset::register($this->getView());
        $clientOptions = Json::encode($this->clientOptions);
        $this->getView()->registerJs("var {$this->var} = $('#{$this->options['id']}').magicSuggest({$clientOptions});");
    }

    /**
     * Registers MagicSuggest event handlers
     */
    public function registerEvents()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $handle = new JsExpression($handle);
                $js[] = "$({$this->var}).on('{$event}', {$handle});";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }


	public function registerTranslation(){
		$this->clientOptions['noSuggestionText'] = \Yii::t('core', 'No suggestions');
		$this->clientOptions['placeholder'] = \Yii::t('core', 'Type or click here');

		$maxEntryRender = \Yii::t('core', 'Please reduce your entry by');
		$characterSingle = \Yii::t('core', 'character');
		$characterPlural = \Yii::t('core', 'characters');
		$maxSelectionRenderer = \Yii::t('core', 'You cannot choose more than');

		$js = <<<JS
        $.fn.magicSuggest.defaults = $.extend({}, $.fn.magicSuggest.defaults, {
        maxEntryRenderer: function(v) {
            return '{$maxEntryRender} ' + v + (v > 1 ? ' {$characterPlural}':' {$characterSingle}');
        },
        maxSelectionRenderer: function(v) {
            return '{$maxSelectionRenderer} ' + v + ' tag' + (v > 1 ? 's':'');
        },
    });
JS;
		$this->getView()->registerJs($js);
	}

	public function registerStyle(){
		$css = <<<CSS
.ms-ctn.form-control{
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    padding: 4px 12px 2px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.ms-ctn.form-control.ms-no-trigger.ms-ctn-focus{
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px rgba(102,175,233,.6);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    border-color: #66afe9;
}
.ms-ctn .ms-sel-ctn {
    margin-left: -6px;
    margin-top: -2px;
}
.ms-ctn .ms-sel-item {
    background-color: #42a5f5;
    color: #fff;
    font-size: 14px;
    cursor: default;
    border: 1px solid transparent;
    border-radius: 0;
}
.ms-res-ctn .ms-res-item {
    line-height: 25px;
    text-align: left;
    padding: 2px 5px;
    color: #474747;
    cursor: pointer;
}
.ms-res-ctn .ms-res-item-active {
    background-color: #42a5f5;
    color: white;
}
.ms-ctn .dropdown-menu {
    border-color: #42a5f5;
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
CSS;
		$this->getView()->registerCss($css);
	}

}
