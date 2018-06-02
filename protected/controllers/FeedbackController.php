<?php

class FeedbackController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	//public $page_title = 'Feedback management';

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
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','index','create','callback','StatusChange','Updatereplayform','Updatereplay','PageLoad'),
				'users'=>array('@'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Feedback;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Feedback']))
		{
			$model->attributes=$_POST['Feedback'];
			$model->created_at = date('Y-m-d H:i:s');
			if ($model->validate()){
			 if($model->save())
			    Yii::app()->user->setFlash('success', 'Feedback added succesfully');
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Feedback']))
		{
			$model->attributes=$_POST['Feedback'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    $this->page_title = "Feedback management";
	    $model=new Feedback('search');
	    $model->unsetAttributes();  // clear any default values
	    if (isset($_GET['Feedback']))
	        $model->attributes = $_GET['Feedback'];
	        $model->type='0';  
	        if(isset($_POST['flag'])&&$_POST['flag']=="flag"){
	            $model->search_val=$_POST['pageCount'];
	            $this->layout=false;
	            $this->render('feedback_custom', array(
	                'model' => $model,
	            ));
	        }else{
    	        $this->render('admin', array(
    	            'model' => $model,
    	        ));
	        }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Feedback the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Feedback::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Feedback $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='feedback-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionCallback(){
	    $this->page_title = "Callback management";
	    $model=new Feedback('search');
	    $model->unsetAttributes();  // clear any default values
	    if (isset($_GET['Feedback']))
	        $model->attributes = $_GET['Feedback'];
	        $model->type='1';  
	        if(isset($_POST['flag'])&&$_POST['flag']=="flag"){
	            $model->search_val=$_POST['pageCount'];
	            $this->layout=false;
	            $this->render('callback_custom', array(
	            'model' => $model,
	           ));
	        }else{
	        $this->render('callback', array(
	            'model' => $model,
	        ));
	        }
	}
	public function actionStatusChange($id){
	    $model = $this->loadModel($id);
	    if($model->status=="Y"){
	        $model->status="N";
	        $status_text = "Callback request has been re-opened, Executive will contact soon..!"; 
	    }else{
	        $model->status = "Y";
	        $status_text = "Callback request has been closed";
	    }
	    if ($model->save(false)) {
	        Yii::app()->user->setFlash('success', $status_text);
	        $this->redirect(array('feedback/Callback'));
	    } else {
	        $msg    = $model->getErrors(); 
	        foreach ($msg as $message) {
	            Yii::app()->user->setFlash('error', $message[0]);
	            break;
	        }
	        $this->redirect(array('feedback/Callback'));
	    }
	}
	public function actionUpdatereplayform(){
	    $this->layout=false;
	    $type = $_POST['type'];
	    if($_POST['id']){
	       $model = $this->loadModel($_POST['id']);

	       $this->render('replay_form',array('model'=>$model,'type'=>$type));   
	    }
	}
	public function actionUpdatereplay(){ 
	    if($_POST){
	        $model = Feedback::model()->findByPk($_POST['Feedback']['id']);
	        if($_POST['Feedback']['type']=="1"){
	           $model->comments = $_POST['Feedback']['comments'];
	           $model->status = "Y";
	        }else{
	            $model->replay = $_POST['Feedback']['replay'];
	        }
	        if($model->save(false)){
	            echo "1";
	        }else{
	        	
	            echo "0";
	            var_dump($model->getErrors());
	        }
	    }
	}
}
