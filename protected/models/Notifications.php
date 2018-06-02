<?php

/**
 * This is the model class for table "notifications".
 *
 * The followings are the available columns in table 'notifications':
 * @property integer $id
 * @property integer $request_id
 * @property string $notification
 * @property string $created_at
 */
class Notifications extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('notification, created_at', 'required'),
			array('request_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, notification, created_at', 'safe', 'on'=>'search'),
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
		        'user_info'=>array(self::BELONGS_TO, 'Users', array('doothan_id'=>'id')),
		    );
		
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'notification' => 'Notification',
			'created_at' => 'Created At',
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
		$criteria->compare('notification',$this->notification,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function userJoinedDate($data) {
	    $date       = $data->created_at;
	    $date       = Common::getTimezone($date,'d M y - h: i a');
	    echo $date;
	}
	public function FullName($data){
	    $first_name = $data->user_info->first_name;
	    $last_name = ($data->user_info->last_name)?$data->user_info->last_name:'';
	    $full_name = $first_name." ".$last_name;
	    return CHtml::link($full_name, array('users/customerView?id='.$data->doothan_id));
	}
	
	public function Notification($data){
	    $feedback = ( strlen($data->notification) > 50
	        ? CHtml::tag("span", array("title"=>$data->notification), CHtml::encode(substr($data->notification, 0, 50)) . "...")
	        : CHtml::encode($data->notification)
	        );
	    echo $feedback;
	}
	public function ViewMore($data){
	    echo '<a href="javascript:void(0)" id="'.$data->notification.'" data-toggle="modal" data-target="#myModal" class="btn btn-info btn-xs" onClick="show_more(this)">Show More</a>';
	}
}
