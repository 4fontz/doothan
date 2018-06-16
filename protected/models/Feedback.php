<?php

/**
 * This is the model class for table "feedback".
 *
 * The followings are the available columns in table 'feedback':
 * @property integer $id
 * @property integer $user_id
 * @property string $feedback
 * @property string $created_at
 */
class Feedback extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $phone_number;
    public $comments;
    public $type;
    public $search_val;
	public function tableName()
	{
		return 'feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, type, feedback, phone_number, status, created_at', 'safe', 'on'=>'search'),
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
		    'user_info'=>array(self::BELONGS_TO, 'Users', array('user_id'=>'id')),
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
		    'phone_number' => 'Phone Number',
		    'comments'=>'Comment',
		    'type'=>'Type',
			'feedback' => 'Feedback',
		    'status'=>'Status',
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
		if($this->status=="Closed"){
		    $this->status = "Y";
		}else if($this->status=="Open"){
		    $this->status = "N";
		}
		$criteria->with=array('user_info');
		$criteria->compare('t.id',$this->id);
		$criteria->compare('user_info.id',$this->user_id,true,'OR');
		$criteria->compare('user_info.first_name',$this->user_id,true,'OR');
		$criteria->compare('user_info.last_name',$this->user_id,true,'OR');
		$criteria->compare('user_info.phone',$this->phone_number,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('feedback',$this->feedback,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('created_at',$this->created_at,true);
		if($this->search_val!=0 && $this->search_val!=''){
    		return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    		    'pagination' => array(
    		        'pageSize' => ($this->search_val==-1)?1000:$this->search_val,
    		    ),
    		));
		}else{
		    return new CActiveDataProvider($this, array(
		        'criteria'=>$criteria,
		    ));
		}
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
	    return CHtml::link($full_name, array('users/customerView?id='.$data->user_id));
	}
	
	public function PhoneNumber($data){
	    $phone = ($data->user_info)?$data->user_info->phone:'';
	    return $phone;
	}
	public function FeedbackText($data){
	    $feedback = ( strlen($data->feedback) > 50
	        ? CHtml::tag("span", array("title"=>$data->feedback), CHtml::encode(substr($data->feedback, 0, 50)) . "...")
	        : CHtml::encode($data->feedback)
	        );
	    echo $feedback;
	}
	public function ViewMoreContent($data){
	    echo '<a href="javascript:void(0)" id="'.$data->id.'" data-toggle="modal" data-target="#myModalContent" class="btn btn-info btn-xs" onClick="show_more_content(this)">Show Feedback</a>';
	}
	public function UpdateComment($data){
	    $comments_details = ( strlen($data->comments) > 30
	        ? CHtml::tag("span", array("title"=>$data->comments), CHtml::encode(substr($data->comments, 0, 30)) . "...")
	        : CHtml::encode($data->comments)
	        );
	    if($comments_details==NULL){
	        $comments = 'Click here to update the comment';
	    }else{
	        $comments = $comments_details;
	    }
	    echo '<a href="javascript:void(0)" id="'.$data->id.'" data-toggle="modal" data-target="#myModal" onClick="show_replay_form(this)">'.$comments.'</a>';
	}
	public function UpdateReplay($data){
	    $comments_details = ( strlen($data->replay) > 30
	        ? CHtml::tag("span", array("title"=>$data->replay), CHtml::encode(substr($data->replay, 0, 30)) . "...")
	        : CHtml::encode($data->replay)
	        );
	    if($comments_details==NULL){
	        $comments = 'Click here to update the replay';
	    }else{
	        $comments = $comments_details;
	    }
	    echo '<a href="javascript:void(0)" id="'.$data->id.'" data-toggle="modal" data-target="#myModal" onClick="show_replay_form(this)">'.$comments.'</a>';
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Feedback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
