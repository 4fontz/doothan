<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property integer $settings_id
 * @property string $admin_email
 * @property string $email_no_reply
 * @property string $support_email
 * @property string $facebook_app_id
 * @property string $facebook_app_secret
 * @property string $aws_key
 * @property string $aws_secret
 * @property string $aws_region
 * @property string $profile_image_bucket
 * @property string $profile_image_bucket_url
 * @property string $adhar_image_bucket
 * @property string $adhar_image_bucket_url
 * @property string $photo_image_bucket
 * @property string $photo_image_bucket_url
 * @property string $request_image_bucket
 * @property string $request_image_bucket_url
 * @property string $upload_path
 * @property string $from_mail
 * @property string $from_name
 * @property string $google_api_key
 * @property string $nexmo_key
 * @property string $nexmo_secret
 * @property string $nexmo_sender_id
 * @property string $_SALT
 * @property double $gst
 * @property string $doothan_avail_time
 * @property integer $minimum_km
 * @property integer $default_weight_limit
 * @property integer $default_distance_limit
 * @property integer $default_weight_limit_charge
 * @property integer $default_weight_charge
 * @property double $default_distance_limit_charge
 * @property double $default_distance_charge
 */
class Settings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('doothan_avail_time', 'required'),
			array('minimum_km, default_weight_limit, default_distance_limit, default_weight_limit_charge, default_weight_charge', 'numerical', 'integerOnly'=>true),
			array('gst, default_distance_limit_charge, default_distance_charge', 'numerical'),
			array('admin_email, email_no_reply', 'length', 'max'=>255),
			array('support_email, aws_region, from_name, google_api_key, nexmo_key, nexmo_secret, nexmo_sender_id, _SALT, doothan_avail_time', 'length', 'max'=>150),
			array('facebook_app_id, facebook_app_secret, aws_key, aws_secret, profile_image_bucket, profile_image_bucket_url, adhar_image_bucket, adhar_image_bucket_url, photo_image_bucket, photo_image_bucket_url, request_image_bucket, request_image_bucket_url, upload_path', 'length', 'max'=>250),
			array('from_mail', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('settings_id, admin_email, email_no_reply, support_email, facebook_app_id, facebook_app_secret, aws_key, aws_secret, aws_region, profile_image_bucket, profile_image_bucket_url, adhar_image_bucket, adhar_image_bucket_url, photo_image_bucket, photo_image_bucket_url, request_image_bucket, request_image_bucket_url, upload_path, from_mail, from_name, google_api_key, nexmo_key, nexmo_secret, nexmo_sender_id, _SALT, gst, doothan_avail_time, minimum_km, default_weight_limit, default_distance_limit, default_weight_limit_charge, default_weight_charge, default_distance_limit_charge, default_distance_charge', 'safe', 'on'=>'search'),
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
			'settings_id' => 'Settings',
			'admin_email' => 'Admin Email',
			'email_no_reply' => 'Email No Reply',
			'support_email' => 'Support Email',
			'facebook_app_id' => 'Facebook App',
			'facebook_app_secret' => 'Facebook App Secret',
			'aws_key' => 'Aws Key',
			'aws_secret' => 'Aws Secret',
			'aws_region' => 'Aws Region',
			'profile_image_bucket' => 'Profile Image Bucket',
			'profile_image_bucket_url' => 'Profile Image Bucket Url',
			'adhar_image_bucket' => 'Adhar Image Bucket',
			'adhar_image_bucket_url' => 'Adhar Image Bucket Url',
			'photo_image_bucket' => 'Photo Image Bucket',
			'photo_image_bucket_url' => 'Photo Image Bucket Url',
			'request_image_bucket' => 'Request Image Bucket',
			'request_image_bucket_url' => 'Request Image Bucket Url',
			'upload_path' => 'Upload Path',
			'from_mail' => 'From Mail',
			'from_name' => 'From Name',
			'google_api_key' => 'Google Api Key',
			'nexmo_key' => 'Nexmo Key',
			'nexmo_secret' => 'Nexmo Secret',
			'nexmo_sender_id' => 'Nexmo Sender',
			'_SALT' => 'Salt',
			'gst' => 'Gst',
			'doothan_avail_time' => 'Doothan Avail Time',
			'minimum_km' => 'Minimum Km',
			'default_weight_limit' => 'Default Weight Limit',
			'default_distance_limit' => 'Default Distance Limit',
			'default_weight_limit_charge' => 'Default Weight Limit Charge',
			'default_weight_charge' => 'Default Weight Charge',
			'default_distance_limit_charge' => 'Default Distance Limit Charge',
			'default_distance_charge' => 'Default Distance Charge',
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

		$criteria->compare('settings_id',$this->settings_id);
		$criteria->compare('admin_email',$this->admin_email,true);
		$criteria->compare('email_no_reply',$this->email_no_reply,true);
		$criteria->compare('support_email',$this->support_email,true);
		$criteria->compare('facebook_app_id',$this->facebook_app_id,true);
		$criteria->compare('facebook_app_secret',$this->facebook_app_secret,true);
		$criteria->compare('aws_key',$this->aws_key,true);
		$criteria->compare('aws_secret',$this->aws_secret,true);
		$criteria->compare('aws_region',$this->aws_region,true);
		$criteria->compare('profile_image_bucket',$this->profile_image_bucket,true);
		$criteria->compare('profile_image_bucket_url',$this->profile_image_bucket_url,true);
		$criteria->compare('adhar_image_bucket',$this->adhar_image_bucket,true);
		$criteria->compare('adhar_image_bucket_url',$this->adhar_image_bucket_url,true);
		$criteria->compare('photo_image_bucket',$this->photo_image_bucket,true);
		$criteria->compare('photo_image_bucket_url',$this->photo_image_bucket_url,true);
		$criteria->compare('request_image_bucket',$this->request_image_bucket,true);
		$criteria->compare('request_image_bucket_url',$this->request_image_bucket_url,true);
		$criteria->compare('upload_path',$this->upload_path,true);
		$criteria->compare('from_mail',$this->from_mail,true);
		$criteria->compare('from_name',$this->from_name,true);
		$criteria->compare('google_api_key',$this->google_api_key,true);
		$criteria->compare('nexmo_key',$this->nexmo_key,true);
		$criteria->compare('nexmo_secret',$this->nexmo_secret,true);
		$criteria->compare('nexmo_sender_id',$this->nexmo_sender_id,true);
		$criteria->compare('_SALT',$this->_SALT,true);
		$criteria->compare('gst',$this->gst);
		$criteria->compare('doothan_avail_time',$this->doothan_avail_time,true);
		$criteria->compare('minimum_km',$this->minimum_km);
		$criteria->compare('default_weight_limit',$this->default_weight_limit);
		$criteria->compare('default_distance_limit',$this->default_distance_limit);
		$criteria->compare('default_weight_limit_charge',$this->default_weight_limit_charge);
		$criteria->compare('default_weight_charge',$this->default_weight_charge);
		$criteria->compare('default_distance_limit_charge',$this->default_distance_limit_charge);
		$criteria->compare('default_distance_charge',$this->default_distance_charge);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
