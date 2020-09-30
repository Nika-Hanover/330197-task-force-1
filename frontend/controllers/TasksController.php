<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use frontend\models\src\TasksSearch;
use frontend\models\categories;

class TasksController extends Controller
{   
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = true;
        return true;
    }

    public function actionIndex()
    {
        $form_data = array();

    if (Yii::$app->request->getIsPost()) {
        $form_data = Yii::$app->request->post();
    }
    
        $category = Categories::find()->select(['category', 'id'])->from('categories')->all();
        $data['categories'] = (ArrayHelper::map($category, 'id', 'category'));

        $data['addition'] = ['no_executers' => 'Без откликов',
                            'no_address' => 'Удаленная работа'];
        
        $data['period'] = ['day' => 'За день',
                            'week' => 'За неделю',
                            'month' => 'За месяц'];
        
        $search = new TasksSearch('task');
        $parsed_data = $search->parse_data($form_data);
        $rows = $search->search($parsed_data);
        $data['tasks'] = $search->create_array($rows);

        return $this->render('/site/tasks', $data);
    }

}
