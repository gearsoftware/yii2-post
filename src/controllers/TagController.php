<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\controllers;

use gearsoftware\controllers\BaseController;

/**
 * TagController implements the CRUD actions for gearsoftware\post\models\Tag model.
 */
class TagController extends BaseController
{

    public $disabledActions = ['view', 'bulk-activate', 'bulk-deactivate'];

    public function init()
    {
        $this->modelClass = $this->module->tagModelClass;
        $this->modelSearchClass = $this->module->tagModelSearchClass;

        $this->indexView = $this->module->tagIndexView;
        $this->viewView = $this->module->tagViewView;
        $this->createView = $this->module->tagCreateView;
        $this->updateView = $this->module->tagUpdateView;

        parent::init();
    }

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
            case 'update':
                return ['update', 'id' => $model->id];
                break;
            case 'create':
                return ['update', 'id' => $model->id];
                break;
            default:
                return parent::getRedirectPage($action, $model);
        }
    }

}
