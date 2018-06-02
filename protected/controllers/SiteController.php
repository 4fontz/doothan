<?php
require_once(Yii::getPathOfAlias('vendor').'/facebook/facebook.php');
/**  class SiteController */
class SiteController extends Controller
{

    /**
     * This is the action to handle external exceptions.
     */
    public $layout = '//layouts/column2';
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        $this->render('//layouts/error',array('error'=>$error));
        /*$this->renderJSON(array(
            'message' => Yii::app()->errorHandler->error['message']
        ), Yii::app()->errorHandler->error['code']);*/
    }

    
     
}