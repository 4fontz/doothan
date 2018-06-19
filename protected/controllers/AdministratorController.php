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
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
class AdministratorController extends Controller {
    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('/dashboard/'));      
        }
        $model = new AdminForm();
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-user')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['AdminForm'])) {
//             Yii::app()->session->clear();
//             Yii::app()->session->destroy();
//             Yii::app()->session->open();
            $model->attributes = $_POST['AdminForm'];
            if($model->validate() && $model->login()){
                $userDetails = Admin::model()->findByPk(Yii::app()->user->getId());
                $message="Admin ".$userDetails->first_name." Logged In ";
                date_default_timezone_set('Asia/Kolkata');
                $date_last = date("Y-m-d H:i:s");
                Common::activityLog("-1", 'LOG IN', $message, $date_last);
                $this->redirect(array('/dashboard'));
            } else {
                Yii::app()->user->setFlash('error', 'Email or Password incorrect');
            }
        }
        $this->layout = false;
        $this->render('//login/login', array('model' => $model));
        
    }
    
    public function actionForgot(){
        $this->layout=false;
        if(isset($_POST['AdminForm']['username_forgot']))
        {
            $getEmail=$_POST['AdminForm']['username_forgot'];
            $record=Admin::model()->findByAttributes(array("email_id"=>$_POST['AdminForm']['username_forgot']));
            if(!$record)
            {
                $this->renderJSON(array('status' => 'false', 'message' => 'Please enter a valid email id'));
            }
            else
            {
                $name=$record->first_name;
                $rand = mt_rand(1000, 9999);
                $record->uniq_id = substr($model->id.$rand, 0, 4);
                $url="<a href='".Yii::app()->request->baseUrl."/administrator/Checkid&uniq_id=".$record->uniq_id."'>Click here</a>";
                //$trans = Yii::app ()->db->beginTransaction ();
                $settings = Settings::model()->find();
                if($record->save())
                {
                    $mail = new SesMailer();
                    $mail->setView('forgotpassword_admin');
                    $name = $record->first_name . ' ' . $record->last_name;
                    $mail->setData(array('email' => $record->email_id,'name' => $record->first_name . ' ' . $model->last_name,'link'=>$url));
                    $mail->setFrom('support@doothan.in',$settings->from_name);
                    $mail->setTo($model->email, $model->first_name);
                    $mail->setSubject('DOOTHAN : Forgot Password');
                    if (!$mail->Send()) {
                        $this->renderJSON(array('status' => 'false', 'message' => 'Unable to send password reset code'));
                    }else{
                        $this->renderJSON(array('status' => 'true', 'message' => 'Password reset code sent successfully'));
                    }
                }
            }
        }
    }
    
    public function actionCheckid($uniq_id)
    {
        //$this->layout = '//layouts/column3';
        $this->pageTitle = "Password Reset";
        $details = Admin::model()->findByAttributes(array('uniq_id'=>$uniq_id));
        if(count($details)>0){
            $model = new UserResetPassword();
            if(isset($_POST['UserResetPassword'])){
                $model->attributes=$_POST['UserResetPassword'];
                $details->password=md5($model->password);
                if($model->validate()){
                    if($details->save(false)){
                        $uniq_id = $details->uniq_id;
                        $details->uniq_id=NULL;
                        if($details->save(false)){
                            Yii::app()->user->setFlash('success','Password successfully updated');
                            $this->redirect('index');
                        }else{
                            Yii::app()->user->setFlash('error','Password cannot update this time.');
                            $this->refresh();
                        }
                    }
                }
            }
            $this->render('password_reset',array(
                'model'=>$model,'uniq_id'=>$uniq_id
            ));
        }else{
            throw new CHttpException(404,'The requested page does not exist.');
        }
        
    }
    
//     public function actionCancelToken($uniq_id){
//         //$this->layout = '//layouts/column3';
//         $this->pageTitle = "Password Reset";
//         $model=new UserResetPassword;
//         Yii::app()->user->setFlash('update','Your password successfully changed.');
//         $this->render('password_reset',array(
//             'model'=>$model,'uniq_id'=>$uniq_id
//         ));
//     }
    
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
