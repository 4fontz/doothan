<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteController
 *
 * @author
 */
class AdministratorController extends Controller {

//     public $defaultAction = 'login';
    //put your code here
    public function actionIndex() {
        $model = new AdminForm('admin');
        
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('/dashboard/index'));      
        }
        
        if (isset($_POST['AdminForm'])) {
            Yii::app()->session->clear();
            Yii::app()->session->destroy();
            Yii::app()->session->open();
            $model->attributes = $_POST['AdminForm'];
            if ($model->validate() && $model->login()) { 
                //$userDetails = Users::model()->findByPk(Yii::app()->user->getId());
                $message="Admin Logged In ";
                date_default_timezone_set('Asia/Kolkata');
                $date_last = date("Y-m-d H:i:s");
                Common::activityLog("-1", 'LOG IN', $message, $date_last);
                $this->redirect(array('/dashboard/index'));
            } else {
                Yii::app()->user->setFlash('error', 'Email or Password incorrect');
            }
        }
        $this->layout = false;
        $this->render('//login/login', array('model' => $model, 'adminLogin' => true));
    }
    public function actionSuccess() {
        $this->layout = false;
        $this->render('//login/success');
    }
    public function actionFailure() {
        $this->layout = false;
        $this->render('//login/failure');
    }
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(array('administrator/index'));
    }
    

}
