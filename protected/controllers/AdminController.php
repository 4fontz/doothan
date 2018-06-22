<?php

class AdminController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','adminDelete','adminView'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionadminView($id)
	{
	    $model=$this->loadModel($id);
	    $this->page_title ='View Admin: '.$model->first_name." ".$model->last_name;
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
	    if(Yii::app()->user->getId()==1){
    		$model=new Admin;
    		$this->page_title ='Create Admin';
    		// Uncomment the following line if AJAX validation is needed
    		// $this->performAjaxValidation($model);
    		if(isset($_POST['Admin']))
    		{
    		    date_default_timezone_set('Asia/Kolkata');
    			$model->attributes=$_POST['Admin'];
    			$first_name = $_POST['Admin']['first_name'];
    			$last_name = $_POST['Admin']['last_name'];
    			$model->password = md5($_POST['Admin']['password']);
    			$model->created_on = date('y-m-d:h:i:s');
    			$model->status = "Y";
    			if($model->save()){
    			    Yii::app()->user->setFlash('success', "Admin ".$first_name.' '.$last_name." created succesfully");
    			    $message = "New admin ".$first_name.' '.$last_name." created ";
    			    Common::activityLog($model->id, 'ADMIN', $message, date('Y-m-d H:i:s'));
    			    $this->redirect(array('admin'));
    			}else{
    			    Yii::app()->user->setFlash('error', "Error while creating user");
    			    //$this->redirect(array('admin/create'));
    			}
    		}
    
    		$this->render('create',array(
    			'model'=>$model,
    		));
	    }else{
	        throw new CHttpException(404,'You are not authorised to perform this action');
	    }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
	    if(Yii::app()->user->getId()==1){
    		$model=$this->loadModel($id);
    		$this->page_title ='Update '.$model->first_name." ".$model->last_name;
    		if(isset($_POST['Admin']))
    		{
    		    date_default_timezone_set('Asia/Kolkata');
    			$model->attributes=$_POST['Admin'];
    			$first_name = $_POST['Admin']['first_name'];
    			$last_name = $_POST['Admin']['last_name'];
    			if($model->save(false)){
    			    Yii::app()->user->setFlash('success', "Admin ".$first_name.' '.$last_name." succesfully updated");
    			    $message = "Admin ".$first_name.' '.$last_name." profile updated ";
    			    Common::activityLog($model->id, 'ADMIN', $message, date('Y-m-d H:i:s'));
    			}else{
    			    Yii::app()->user->setFlash('error', "Error while updating user");
    			}
    			$this->redirect(array('admin'));
    		}
    
    		$this->render('update',array(
    			'model'=>$model,
    		));
	    }else{
	        throw new CHttpException(404,'You are not authorised to perform this action');
	    }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionadminDelete($id)
	{
	    if(Yii::app()->user->getId()==1){
    		$model = $this->loadModel($id);
    		$this->loadModel($id)->delete();
    		Yii::app()->user->setFlash('success', "Admin has been deleted successfully.");
    		$message = "Admin ".$model->first_name.' '.$model->last_name." deleted";
    		Common::activityLog($model->id, 'ADMIN', $message, date('Y-m-d H:i:s'));
    		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    		$this->redirect(array('admin'));
	    }else{
	        throw new CHttpException(404,'You are not authorised to perform this action');
	    }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    if(Yii::app()->user->getId()==1){
    	    $model = new Admin('search');
    	    $this->page_title   = 'Admin Management';
    	    $model->unsetAttributes();  // clear any default values
    	    if (isset($_GET['Admin']))
    	        $model->unsetAttributes();  // clear any default values
    	        $model->attributes = $_GET['Admin'];
    	        $this->render('admin', array(
    	            'model' => $model,
	        ));
    	}else{
    	    throw new CHttpException(404,'You are not authorised to perform this action');
    	}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Admin('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admin']))
			$model->attributes=$_GET['Admin'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Admin the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Admin::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admin $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
