<?php
namespace vetal06\multiform\exemple\controllers;


use vetal06\multiform\exemple\model\MainModel;

class SiteController extends \yii\web\Controller{



    public function actionIndex()
    {
        $model = new MainModel();
        return $this->render('multiform', compact('model'));
    }
}