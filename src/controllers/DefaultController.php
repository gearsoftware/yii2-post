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
use gearsoftware\models\User;
use gearsoftware\post\models\Post;
use gearsoftware\timeline\controllers\TimelineTrait;
use Yii;

/**
 * PostController implements the CRUD actions for Post model.
 */
class DefaultController extends BaseController
{
	use TimelineTrait;

    public function init()
    {
        $this->modelClass = $this->module->postModelClass;
        $this->modelSearchClass = $this->module->postModelSearchClass;

        $this->indexView = $this->module->indexView;
        $this->viewView = $this->module->viewView;
        $this->createView = $this->module->createView;
        $this->updateView = $this->module->updateView;

        parent::init();
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		/* @var $model \gearsoftware\db\ActiveRecord */
		$model = new $this->modelClass;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('info', Yii::t('core', 'Your item has been created.'));

			$this->model_class = Post::class;
			$this->model_class_id = $model->id;
			$this->model_created_at = $model->created_at;
			$this->saveTimeline();

			return $this->redirect($this->getRedirectPage('create', $model));
		}

		return $this->renderIsAjax($this->createView, compact('model'));
	}

    protected function getRedirectPage($action, $model = null)
    {
        if (!User::hasPermission('editPosts') && $action == 'create') {
            return ['view', 'id' => $model->id];
        }

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
