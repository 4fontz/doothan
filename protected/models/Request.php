<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property string $type
 * @property string $item_details
 * @property string $request_date
 * @property string $image
 * @property string $to_address
 * @property integer $to_city
 * @property integer $to_state
 * @property string $to_pincode
 * @property string $phone
 * @property integer $dropbox_id
 * @property integer $user_id
 * @property string $status
 * @property double $amount
 * @property double $service_charge
 * @property string $created_on
 * @property string $updated_on
 */
class Request extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $first_name;
	public $dropbox_owner;
	public $doothan_name;
	public $cancel_request;
	public $search_val;
	public function tableName()
	{
		return 'request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, item_details, request_date, to_address, to_state, to_pincode, dropbox_id, user_id, status, created_on, updated_on', 'required'),
			array('to_city, dropbox_id, user_id', 'numerical', 'integerOnly'=>true),
			array('amount, service_charge', 'numerical'),
			array('type', 'length', 'max'=>8),
			array('item_details, to_address, to_pincode, phone', 'length', 'max'=>250),
			array('image', 'length', 'max'=>255),
			array('status', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, request_code, type, item_details, request_date, image, to_address, to_city, to_state, to_pincode, phone, dropbox_id, user_id, status, amount,base_amount,product_price, service_charge,weight,gst,weight_unit,coupon_amount,discount,distance, created_on, updated_on,first_name,dropbox_owner,doothan_name', 'safe', 'on'=>'search'),
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
			'dropbox' => array(self::BELONGS_TO, 'Users',    'dropbox_id'),
		    'doothan' => array(self::BELONGS_TO, 'Users',    'doothan_id'),
		    'payment' => array(self::HAS_ONE, 'PaymentStatus',  array('id'=>'booking_id')),
		    'city' => array(self::BELONGS_TO, 'Cities',  'to_city')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
		    'request_code'=>'Request Code',
			'type' => 'Type',
			'item_details' => 'Item Details',
			'request_date' => 'Request Date',
			'image' => 'Image',
			'to_address' => 'To Address',
			'to_city' => 'To City',
			'to_state' => 'To State',
			'to_pincode' => 'To Pincode',
			'phone' => 'Phone',
			'dropbox_id' => 'Dropbox',
			'user_id' => 'User',
			'status' => 'Status',
			'amount' => 'Amount',
		    'total_amount'=>'Total Amount',
			'service_charge' => 'Service Charge',
			'weight' => 'Weight Charge',
			'weight_unit' => 'Weight Unit',
			'gst' => 'GST ( % )',
			'discount' => 'Discount',
			'coupon_amount' => 'Coupon Amount',
		    'cancel_request'=> 'Cancel Request',
		    'base_amount'=> 'Base Amount',
		    'product_price'=> 'Product Price',
			'distance' => 'Total Distance',
			'rate_per_km' => 'Rate per Km',
			'dropbox_fee' => 'Dropbox owner will get',
			'doodhan_fee' => 'Doothan Fee',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
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
		$criteria->compare('request_code',$this->request_code,true);
		
		$criteria->compare('type',$this->type,true);
		$criteria->compare('item_details',$this->item_details,true);
		$criteria->compare('request_date',$this->request_date,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('to_address',$this->to_address,true);
		$criteria->compare('to_city',$this->to_city);
		$criteria->compare('to_state',$this->to_state);
		$criteria->compare('to_pincode',$this->to_pincode,true);
		$criteria->compare('t.phone',$this->phone,true);
		$criteria->compare('dropbox_id',$this->dropbox_id);
		$criteria->compare('doothan_id',$this->doothan_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->with=array('user');
		$criteria->compare('first_name',$this->first_name,true);
		if($this->dropbox_owner!=NULL){
    		$criteria->with=array('dropbox');
    		$criteria->compare('dropbox.first_name',$this->dropbox_owner,true);
		}
		if($this->doothan_name!=NULL){
    		$criteria->with=array('doothan');
    		$criteria->compare('doothan.first_name',$this->doothan_name,true);
		}
		$criteria->compare('t.status',$this->status,false);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('service_charge',$this->service_charge);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);
		//$criteria->order = 'created_on DESC';
		//echo "<pre>";print_r($criteria);die;
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
    		        'defaultOrder'=>'t.id DESC',
    		    ),
    		    'sort'=>array(
    		        'defaultOrder'=>'created_on DESC',
    		    )
    		));
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Request the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function requestorName($data) {
		$id = $data->user_id;
		$userDetails 	= Users::model()->findByPk($id);
		$firstName 	= $userDetails->first_name;
		$lastName 	= $userDetails->last_name;
		$userName 	= $firstName .' '.$lastName;
		if (empty($firstName) || empty($lastName)) {
			$userName = 'Not Available';
		}
		return $userName;
	}

	public function requestorAddress($data) {
		$cityId 	 = $data->to_city;
		$toAddress 	 = $data->to_address;
		$stateName 	 = $data->to_state;
		$pinCode 	 = $data->to_pincode;
		$city 	 	 = Cities::model()->findByAttributes(array('city_id'=>$cityId));
		$cityName    = $city->city_name;
		$userId 	 = $data->user_id;
		$userDetails = UserAddress::model()->findByPk($userId);
		$userAddress = $userDetails->address;
		$userState 	 = $userDetails->state;
		$userCity 	 = $userDetails->city;
		$userCountry = $userDetails->country;
		$userPost 	 = $userDetails->postal_code; 
		if (empty($toAddress)) {
			$address 	= $userAddress.' '.$userCity. ' '.$userState . ' '.$userCountry.' '.$uuserPost;
		} else {
			$address 	= $toAddress .' ' . $cityName.' ' . $stateName . ' '.$pinCode;
		}
		//$address 	= wordwrap($address, 30, "<br />\n",true);
		$address = ( strlen($address) > 20
		    ? CHtml::tag("span", array("title"=>$address), CHtml::encode(substr($address, 0, 20)) . "...")
		    : CHtml::encode($address)
		);
		if($data->status=="Request Placed"){
		  echo '<span style="color:green;font-weight:bold;">'.$address.'</span>';
		}else{
		    echo $address;
		}
	}

	public function dropBoxOwner($data) {
	    $dropBoxId 	= $data->dropbox_id;
		$dropBoxOwner = Users::model()->findByPk($dropBoxId);
		$dropBoxFName  = $dropBoxOwner->first_name;
		$dropBoxLName  = $dropBoxOwner->last_name;
		$dropBoxOwnerName = $dropBoxFName .' '. $dropBoxLName;
		if($dropBoxOwnerName){
		    $htm = $dropBoxOwnerName;
		}else{
		    $htm="Not Available";
		}
		if($data->status=="Request Placed"){
		    return '<span style="color:green;font-weight:bold;">'.$htm.'</span>';
		}else{
		    return $htm;
		}
	}

	public function Order_Phone($data){
	    if($data->status=="Request Placed"){
	        return '<span style="color:green;font-weight:bold;">'.($data->phone=='0')?"":$data->phone.'</span>';
	    }else{
	        return ($data->phone=='0')?"":$data->phone;
	    }
	}
	
	public function StatusText($data){
	    if($data->status=="Request Placed"){
	        return '<span style="color:green;font-weight:bold;">'.$data->status.'</span>';
	    }else{
	        return $data->status;
	    }
	}
	public function Order_Name($data){
	    if($data->status=="Request Placed"){
	        $name = !empty($data->user->first_name)?$data->user->first_name:"Not Available";
	        $name = '<span style="color:green;font-weight:bold;">'.$name.'<span>';
	    }else{
	        $name = !empty($data->user->first_name)?$data->user->first_name:"Not Available";
	    }
	    return $name;
	}
	public function Order_Code($data){
	    if($data->status=="Request Placed"){
	        $code = '<span style="color:green;font-weight:bold;">'.$data->request_code.'<span>';
	    }else{
	        $code = $data->request_code;
	    }
	    return $code;
	}
	public function requestImage($data) {
	    $uploadUrl = Yii::app()->request->baseUrl.'/uploads/request/';
		$image 	   = $uploadUrl.$data->image;
		$noImage   = $uploadUrl.'no-image.jpg';
		if (@getimagesize($image)) {
			$img 	= "<img src='$image' style='width:50px;height:50px;'>";
		} else {
			$img 	= "<img src='$noImage' style='width:50px;height:50px;'>";
		}
		echo $img;
		    
	}
	public function userJoinedDate($data) {
	    $date       = $data->created_on;
	    $date       = Common::getTimezone($date,'d M y - h: i a');
	    if($data->status=="Request Placed"){
	       echo '<span style="color:green;font-weight:bold;">'.$date."</span>";
	    }else{
	        echo $date;
	    }
	}
	public function ItemDetailsText($data){
	    $item_details = ( strlen($data->item_details) > 30
	        ? CHtml::tag("span", array("title"=>$data->item_details), CHtml::encode(substr($data->item_details, 0, 30)) . "...")
	        : CHtml::encode($data->item_details)
	        );
	    if($data->status=="Request Placed"){
	       echo '<span style="color:green;font-weight:bold;">'.$item_details.'</span>';
	    }else{
	        echo $item_details;
	    }
	}
	public function CancelRequest($data){
	    if($data->status=="Request Placed"){
	       return "<a class='btn btn-success btn-xs' href='javascript:void(0);' id='$data->id'style='background-color:#f39c12 !important;border-color:#f39c12 !important;' onClick='Cancel_request(this)'>Cancel Request</a>";      
	    }else if($data->status=="Cancelled"){
	        return "<a class='btn btn-danger btn-xs' href='#' style='cursor:not-allowed;'>Cancelled</a>";      
	    }else if($data->status=="Waiting for payment"){
	        return "<a class='btn btn-danger btn-xs' href='#' style='cursor:not-allowed;background-color: #FF7F50;border-color: #FF7F50;'>Waiting for payment</a>"; 
	    }else{
	        return "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>".$data->status."</a>";
	    }
	}
	public function DoothanFind($data){
	    if($data->doothan_id!=0){
	        $userDetails = Users::model()->findByPk($data->doothan_id);
	        $doothan = $userDetails->first_name." ".$userDetails->last_name;
	        return "<span class=''>".$doothan."</span>";      
	    }else{
	        return "<a class='btn btn-success btn-xs notify_$data->id' href='javascript:void(0);' data-toggle='modal' data-target='#myModal' id='$data->id' style='cursor:pointer;background-color: #FF7F50;border-color: #FF7F50;'  onClick='Find_doothan(this)'>Find Doothan</a>";
	    }
	}

}
