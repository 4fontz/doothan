<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property integer $settings_id
 * @property string $from_mail
 * @property string $from_name
 * @property double $gst
 * @property integer $minimum_km
 * @property double $minimum_distance_doothan_dropbox
 * @property string $doothan_avail_time
 * @property integer $default_weight_charge
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
			array('minimum_km, default_weight_charge', 'numerical', 'integerOnly'=>true),
			array('gst, minimum_distance_doothan_dropbox, default_distance_charge', 'numerical'),
			array('from_mail', 'length', 'max'=>100),
			array('from_name, doothan_avail_time', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('settings_id, from_mail, from_name, gst, minimum_km, minimum_distance_doothan_dropbox, doothan_avail_time, default_weight_charge, default_distance_charge', 'safe', 'on'=>'search'),
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
			'from_mail' => 'From Mail',
			'from_name' => 'From Name',
			'gst' => 'Gst',
			'minimum_km' => 'Minimum kilometer from pickup location and doothan',
			'minimum_distance_doothan_dropbox' => 'Minimum distance between doothan & dropbox',
			'doothan_avail_time' => 'Doothan Available Time',
			'default_weight_charge' => 'Default Weight Charge',
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
		$criteria->compare('from_mail',$this->from_mail,true);
		$criteria->compare('from_name',$this->from_name,true);
		$criteria->compare('gst',$this->gst);
		$criteria->compare('minimum_km',$this->minimum_km);
		$criteria->compare('minimum_distance_doothan_dropbox',$this->minimum_distance_doothan_dropbox);
		$criteria->compare('doothan_avail_time',$this->doothan_avail_time,true);
		$criteria->compare('default_weight_charge',$this->default_weight_charge);
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
