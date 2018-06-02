<?php

/**
 * This is the model class for table "fee".
 *
 * The followings are the available columns in table 'fee':
 * @property integer $id
 * @property integer $user_id
 * @property string $request_id
 * @property string $mode
 * @property integer $cheque_no
 * @property string $description
 * @property integer $amount
 * @property string $created_at
 */
class Fee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, request_id, amount, cheque_no', 'required'),
			array('user_id, cheque_no, amount', 'numerical', 'integerOnly'=>true),
			array('request_id', 'length', 'max'=>150),
			array('mode', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, request_id, mode, cheque_no, description, amount, created_at', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'request_id' => 'Request',
			'mode' => 'Mode',
			'cheque_no' => 'Cheque No',
			'description' => 'Description',
			'amount' => 'Amount',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('request_id',$this->request_id,true);
		$criteria->compare('mode',$this->mode,true);
		$criteria->compare('cheque_no',$this->cheque_no);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('created_at',$this->created_at,true);

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
	 * @return Fee the static model class
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
	public function RequestData($data){
	    $request = Request::model()->findByPk($data->request_id);
	    echo $request->request_code." (".$data->request_id.")";
	}
	public function Mode($data){
	    if($data->mode==1){
	        echo "Cheque";
	    }else{
	        echo "Cash";
	    }
	}
}
