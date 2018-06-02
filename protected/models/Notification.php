<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property string $user_id
 * @property string $device_type
 * @property string $message
 * @property integer $queue_status
 * @property integer $status
 * @property string $error_log
 * @property string $started_on
 */
class Notification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $user_type;
	public function tableName()
	{
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, device_type, message, queue_status, status, error_log, started_on', 'required'),
			array('user_id,queue_status, status', 'numerical', 'integerOnly'=>true),
			//array('user_id', 'length', 'max'=>500),
			array('device_type', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, device_type, message, queue_status, status, error_log, started_on', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		    'user' => array(self::BELONGS_TO, 'Users',    'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'device_type' => 'Device Type',
			'message' => 'Message',
			'queue_status' => 'Queue Status',
			'status' => 'Status',
			'error_log' => 'Error Log',
			'started_on' => 'Started On',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('queue_status',$this->queue_status);
		$criteria->compare('status',$this->status);
		$criteria->compare('error_log',$this->error_log,true);
		$criteria->compare('started_on',$this->started_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'sort'=>array(
		        'defaultOrder'=>'id DESC',
		    ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function MessageContent($data){
	    if($data->message!=''){
	        //echo '<a href="javascript:void(0)" id="'.$data->message.'" data-toggle="modal" data-target="#myModal" class="btn btn-info btn-xs" onClick="show_more(this)">Show Message</a>';
	        echo $address = ( strlen($data->message) > 20
	            ? CHtml::tag("span", array("title"=>$data->message), CHtml::encode(substr($data->message, 0, 20)) . "...")
	            : CHtml::encode($data->message)
	            );
	    }
	}
	public function StartedOn($data){
	    $date       = $data->started_on;
	    $date       = Common::getTimezone($date,'d M y - h: i a');
	    echo $date;
	}
	public function ErrorLog($data){
	    if($data->error_log!=''){
	        if($data->error_log=="NotRegistered"){
	            echo "Device id not found";
	        }else if($data->error_log=="success"){
	            echo "Success";
	        }else{
	            echo "Failed";
	        }
	    }else{
	        echo "";
	    }
	}
	public function UserData($data){
	    if($data->user_id){
	        $userDataContent = Users::model()->findByPk($data->user_id);
	        if($userDataContent){
	            echo $userDataContent->first_name." ".$userDataContent->last_name;
	        }else{
	            echo "";
	        }
	    }
	}
}
