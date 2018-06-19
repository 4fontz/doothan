<?php

/**
 * This is the model class for table "payment_status".
 *
 * The followings are the available columns in table 'payment_status':
 * @property integer $id
 * @property string $firstname
 * @property integer $booking_id
 * @property string $transaction_id
 * @property integer $amount
 * @property string $productinfo
 * @property string $mode
 * @property string $bankcode
 * @property string $bank_ref_num
 * @property string $status
 * @property string $created_on
 */
class PaymentStatus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $request_code_data;
    public $search_val;
	public function tableName()
	{
		return 'payment_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firstname, booking_id, transaction_id, amount, productinfo, mode, bankcode, bank_ref_num, status, created_on', 'required'),
			array('booking_id', 'numerical', 'integerOnly'=>true),
			array('firstname, mode, bankcode, bank_ref_num', 'length', 'max'=>150),
			array('transaction_id', 'length', 'max'=>255),
			array('productinfo', 'length', 'max'=>300),
			array('status', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firstname, booking_id, transaction_id, amount, productinfo, mode, bankcode, bank_ref_num, status, created_on, request_code_data', 'safe', 'on'=>'search'),
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
		    'request_data' => array(self::BELONGS_TO, 'Request',    'booking_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'firstname' => 'Firstname',
			'booking_id' => 'Booking',
			'transaction_id' => 'Transaction',
			'amount' => 'Amount',
			'productinfo' => 'Productinfo',
			'mode' => 'Mode',
			'bankcode' => 'Bankcode',
			'bank_ref_num' => 'Bank Ref Num',
			'status' => 'Status',
			'created_on' => 'Created On',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('firstname',$this->firstname,true);
		//if($this->request_code!=NULL){
    		$criteria->with=array('request_data');
    		$criteria->compare('request_code',$this->request_code_data,true);
		//}
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('t.amount',$this->amount);
		$criteria->compare('productinfo',$this->productinfo,true);
		$criteria->compare('mode',$this->mode,true);
		$criteria->compare('bankcode',$this->bankcode,true);
		$criteria->compare('bank_ref_num',$this->bank_ref_num,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_on',$this->created_on,true);
		if($this->search_val!=0 && $this->search_val!=''){
		    return new CActiveDataProvider($this, array(
		        'criteria'=>$criteria,
		        'pagination' => array(
		            'pageSize' => ($this->search_val==-1)?1000:$this->search_val,
		        ),
		        'sort'=>array(
		            'defaultOrder'=>'created_on DESC',
		        )
		    ));
		}else{
    		return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    			'sort'=>array(
    			 'defaultOrder'=>'t.created_on DESC',
    			 ),
    		    'sort'=>array(
    		        'defaultOrder'=>'created_on DESC',
    		    )
    		));
		}
	}

	public function RequestCode($data){
	    if($data->booking_id){
	        $requestDetails = Request::model()->findByPk($data->booking_id);
	        $code =  $requestDetails->request_code;
	        return CHtml::link($code, array('request/requestview/'.$data->booking_id));
	    }
	}
	
	public function ProductInfo($data){
	    $item_details = ( strlen($data->productinfo) > 30
	        ? CHtml::tag("span", array("title"=>$data->productinfo), CHtml::encode(substr($data->productinfo, 0, 30)) . "...")
	        : CHtml::encode($data->productinfo)
	        );
	    echo $item_details;
	}
	public function userJoinedDate($data) {
	    $date       = $data->created_on;
	    $date       = Common::getTimezone($date,'d M y - h: i a');
	    echo $date;
	}
	
	public function FullName($data){
	    $requestDetails = Request::model()->findByPk($data->booking_id);
	    if($requestDetails){
	        $userDetails = Users::model()->findByPk($requestDetails->user_id);
	        $first_name = $userDetails->first_name;
	        $last_name = ($userDetails->last_name)?$userDetails->last_name:'';
    	    $full_name = $first_name." ".$last_name;
    	    return CHtml::link($full_name, array('users/customerView?id='.$userDetails->id));
	    }
	    
	}
	public function AmountValue($data){
	    return '<i class="fa fa-inr" aria-hidden="true"></i> '.number_format((float)$data->amount, 2, '.', '');
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
