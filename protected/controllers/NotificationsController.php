<?php

class NotificationsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public $layout = '//layouts/column2';
    public $page_title = 'Notification Management';
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
				'actions'=>array('admin','delete','custom_notifications','Createcustom','LoadRolesBasedUsers','SubmitPush','Custom_render'),
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
		$model=new Notifications;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notifications']))
		{
			$model->attributes=$_POST['Notifications'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['Notifications']))
		{
			$model->attributes=$_POST['Notifications'];
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
	    $model = new Notifications('search');
	    $this->page_title   = 'Notification Management';
	    $model->unsetAttributes();  // clear any default values
	    if (isset($_GET['Notifications']))
	        $model->attributes = $_GET['Notifications'];
	        $this->render('admin', array(
	            'model' => $model,
	        ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Notifications('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notifications']))
			$model->attributes=$_GET['Notifications'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Notifications the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Notifications::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Notifications $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='notifications-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionCustom_notifications(){
	    $model = new Notification('search');
	    $this->page_title   = 'Custom Notification';
	    $model->unsetAttributes();  // clear any default values
	    if (isset($_GET['Notification']))
	        $model->attributes = $_GET['Notification'];
	        $this->render('custom_notification', array(
	            'model' => $model,
	        ));
	    
	}
	public function actionCustom_render(){
	    $this->layout=false;
	    $model = new Notification('search');
	    $this->page_title   = 'Custom Notification';
	    $model->unsetAttributes();  // clear any default values
	    if (isset($_GET['Notification']))
	        $model->attributes = $_GET['Notification'];
	        $this->render('custom_notification_partial', array(
	            'model' => $model,
	        ));
	}
	public function actionCreatecustom(){
	    $this->layout=false;
	    $model=new Notification;
	    if(isset($_POST['Notification']))
	    {
	        $model->attributes=$_POST['Notification'];
	        if($model->save())
	            $this->redirect(array('Custom_notifications'));
	    }
	    
	    $this->render('create_custom',array(
	        'model'=>$model,
	    ));
	}
	public function actionLoadRolesBasedUsers(){
	    if($_POST['value']){
	        $userdetails = Users::model()->findAllByAttributes(array('member_type'=>$_POST['value']));
	        if(count($userdetails)>0){?>
	        	<label style='float:left' for='Notification_user_id' class='required'>User <span class='required'>*</span></label>
	            <select id="Notification_user_id" class="form-control select2" multiple="multiple" data-placeholder="Select user" name="Notification[user_id][]">
	            <?php foreach($userdetails as $user_data){?>
	            	<option value="<?php echo $user_data->id?>"><?php echo $user_data->first_name;?></option>
	            <?php }?>
	            </select>
	        <?php }else{
	            echo "<label style='float:left' for='Notification_user_id' class='required'>User <span class='required'>*</span></label><select class='form-control select2' multiple='multiple' data-placeholder='Select a User'></select>";
	        }
	    }
	}
	
	public function actionSubmitPush(){
	    if(isset($_POST['Notification'])){
	        $list = $_POST['Notification']['user_id'];
	        $result_arrayss = array();
	        $result_arrayss = array();
	        $imploded_arrays = '';
	        $count_value = count($list);
	        foreach($list as $users){
	            $user = Users::model()->findByPk($users);
	            $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$users),array('order'=>'id DESC'));
	            $device_token = $device_token_data[0]->device_id;
	            $result_msg['data']['order_id']="";
	            $result_msg['data']['order_code']="";
	            $result_msg['data']['product_name']="";
	            $result_msg['data']['doothan']="";
	            $result_msg['data']['dropbox']=$_POST['Notification']['message'];
	            $result_msg['data']['user_address']="";
	            $result_msg['data']['user_id']=$user['id'];
	            $result_msg['data']['email_id']=$user['email'];
	            $result_msg['data']['type']="5";
	            $result_msg['data']['service_fee']="";
	            $result_msg['data']['doothan_service_fee']="";
	            $result_msg['data']['role']="";
	            $result=Common::sendPushNotification($device_token, $result_msg);
	            $result_array = json_decode($result);
	            $result_array = json_decode( json_encode($result_array), true);
	            $resultanct_array = ($result_array['results'][0]['error'])?$result_array['results'][0]['error']:'success';
	            $result_array = json_decode(json_encode($result_array), true);
	            $resultanct_array = ($result_array['results'][0]['error'])?$result_array['results'][0]['error']:'success';
	            
	            if($resultanct_array=="NotRegistered"){
	                $result_arrayss[] = $user['email'];
	            }
	            $notification = New Notification;
	            $notification->user_id = $users;
	            $notification->device_type = "android";
	            $notification->message = $_POST['Notification']['message'];
	            $notification->queue_status = "Completed";
	            $notification->status = $resultanct_array;
	            $notification->error_log = $resultanct_array;
	            $notification->started_on = date('Y-m-d H:i:s');
	            $notification->save(false);
	        }
	        if(!empty($result_arrayss)){
	            $imploded_arrays = implode(',',$result_arrayss);
	        }
	       $this->renderJSON(array('status' => 'true','unregistred'=>$imploded_arrays,'count_value'=>$count_value,'message'=>json_decode($result)));
	    }
	}
}
