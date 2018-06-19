<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $phone
 * @property string $email_id
 * @property string $status
 * @property string $created_on
 */
class Admin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name, address, phone, email_id, gender, username, password, uniq_id, status, created_on', 'required'),
			array('first_name, last_name, email_id, gender, profile_image, username, password, uniq_id', 'length', 'max'=>150),
			array('phone', 'length', 'max'=>50),
		    array('phone','unique'),
		    array('email_id','unique'),
		    array('username','unique'),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, first_name, last_name, address, phone, email_id, gender, profile_image, username, password, status, created_on', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'address' => 'Address',
			'phone' => 'Phone',
			'email_id' => 'Email',
		    'username' => 'Username',
		    'Password' => 'Password',
		    'gender' =>'Gender',
		    'profile_image'=>'Profile Image',
			'status' => 'Status',
		    'uniq_id'=>'Uniq_id',
			'created_on' => 'Created On',
		    'flag'=>'Flag'
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email_id',$this->email_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_on',$this->created_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'sort'=>array(
		        'defaultOrder'=>'created_on DESC',
		    )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function CreatedDate($data) {
	    $date       = $data->created_on;
	    $date       = Common::getTimezone($date,'d M y - h: i a');
	    echo $date;
	}
	
	public function FullName($data){
	    $first_name = $data->first_name;
	    $last_name = $data->last_name;
	    $full_name = $first_name." ".$last_name;
	    return $full_name;
	}
}
