<?php

require_once(Yii::getPathOfAlias('vendor') . '/password.php');

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property integer $reputation
 * @property integer $member_type
 * @property integer $status
 * @property string $verification_code
 * @property string $updated
 */
class Users extends CActiveRecord {
    public $retype_password, $oldpassword, $referenceemail,$user_title;
    public $search_val;
    //public $image;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('first_name,email', 'required'),
            array('password', 'required', 'on' => 'registration, changepassword, resetpassword'),
            array('email,first_name', 'required', 'except' => 'socialLogin,stripe_customer'),
            
            //array('retype_password', 'required', 'on'=>'registration'),
            array('id, status', 'numerical', 'integerOnly' => true),
            //array('username', 'length', 'max' => 30, 'min' => 3),
            //array('phone','length','min'=>7,'max'=>10),
//            array('username', 'unique', 'except' => 'socialLogin'),
//            array('username', 'match', 'pattern' => '/^[a-zA-Z0-9_\.]*$/i'),
            array('password', 'length', 'max' => 60, 'min' => 5),
            //array('password', 'match', 'pattern'=>'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}/', 'message'=>'please ensure your password has minimum of 8 characters, including at least 1 uppercase letter, 1 lowercase letter and 1 number'),
            array('first_name, last_name, verification_code', 'length', 'max' => 100),
            array('email', 'length', 'max' => 200),
            array('email', 'email'),
            array('email', 'unique'),

            array('created','length','max' => 50),
//            array('email', 'unique', 'on' => 'registration', 'attributeName' => 'email', 'className' => 'Users'),

            // array('state', 'length', 'max'=>5, 'min' => 2),
            // array('state', 'in', 'range' => array_keys(Common::getUsStates()), 'allowEmpty' => true),
//            array('zip', 'match', 'pattern' => '/^\d{5}([\-]?\d{4})?$/'),
//            array('country', 'length', 'max' => 10),
            array('facebook_image', 'length', 'max' => 300),
//            array('zip','length','max' => 10),
            array('gender','length','max' => 10),
//            array('passport_number','length','max'=>50),
            array('invite_code','length','max' => 50),
//            array('member_type', 'in', 'range' => array(1, 2, 3), 'allowEmpty' => false),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('stripe_customer_id', 'required','on'=>'stripe_customer'),
            //array('image','required'),
            array('id, password, first_name, last_name, email, image, account_status, facebook_image, cover_image, member_type, status, verification_code, created, updated, passwordreset_code,stripe_customer_id,country_code,current_location,gender,device_type,device_token, phone,dob,photo_number,profession,aadhar,photo_id,aadhar_number,office_address,travel_from_to,mode_of_commute', 'safe', 'on' => 'search'),
            
            // array('image', 'file', 'allowEmpty' => false, 'types' => 'png, jpg', 'wrongType'=>'File Type Not Accepted', 'on' => self::SCENARIO_CREATE),
            // array('image', 'file', 'allowEmpty' => true, 'types' => 'png, jpg', 'wrongType'=>'File Type Not Accepted', 'on' => self::SCENARIO_EDIT),
          //  array('image', 'file', 'types' =>'jpg, png, gif,jpeg' )
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user_address'=>array(self::HAS_ONE, 'UserAddress', array('user_id'=>'id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Id',
            //'username' => 'Username',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
//            'state' => 'state',
//            'zip' => 'zip',
            'account_status'=>'Account Status',
            'image' => 'Change Image',
            'member_type' => 'Member Type',
            'role_change_to'=>'Role Change To',
            'status' => 'Status',
            'verification_code' => 'Verification Code',
            'oldpassword' => 'Password',
            'updated' => 'Updated',
            'current_location' => 'Current Location',
            'country_code' => 'Country Code',
            'aadhar'=>'Adhar',
            'photo_id'=>'Photo',
            'photo_number'=>'Passport / Voter Id Number',
//            'passport_number' => 'passport number',
            'gender' => 'Gender',
            'invite_code' => 'Invite Code',
            'invited_by' => 'Invited By',
            'phone' => 'Phone',
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
    // public function customerId($data) {
    //     $customerId=1120+$data->id;
    //     echo $customerId;
       
    // }
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        //$criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('account_status', $this->account_status, true);
//        $criteria->compare('state', $this->state, true);

//        $criteria->compare('zip', $this->zip, true);
        $criteria->compare('member_type', $this->member_type, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('verification_code', $this->verification_code, true);
        $criteria->compare('current_location', $this->current_location, true);
        $criteria->compare('updated', $this->updated, true);
        //$criteria->compare('created', $joinDate, true);
       // $criteria->compare('gender',$this->gender);
        

        $criteria->limit = $pageSize;
        if($this->search_val!=0 && $this->search_val!=''){
            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination' => array(
                    'pageSize' => ($this->search_val==-1)?1000:$this->search_val,
                ),
                'sort'=>array(
                    'defaultOrder'=>'created DESC',
                ),
            ));
        }else{
            if($pageSize==-1){
                $pageCount=array('pageSize'=>$this->count(),);
                $criteria->limit = $this->count();
            }else{
                $pageCount=array('pageSize'=>$pageSize,);
                $criteria->limit = $pageSize;
            }
            
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination'=>$pageCount,
                'sort'=>array(
                    'defaultOrder'=>'created DESC',
                ),
            ));
        }
    }
    
    public function public_search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $pageSize = Yii::app()->user->getState('pageSize', 5);
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        //$criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('account_status', $this->account_status, true);
        //        $criteria->compare('state', $this->state, true);
        
        //        $criteria->compare('zip', $this->zip, true);
        $criteria->compare('member_type', 'doothan', true,'OR');
        $criteria->compare('member_type', 'dropbox', true,'OR');
        $criteria->compare('status', $this->status);
        $criteria->compare('verification_code', $this->verification_code, true);
        $criteria->compare('current_location', $this->current_location, true);
        $criteria->compare('updated', $this->updated, true);
        //$criteria->compare('created', $joinDate, true);
        // $criteria->compare('gender',$this->gender);
        
        
        $criteria->limit = $pageSize;
        if($pageSize==-1){
            $pageCount=array('pageSize'=>$this->count(),);
            $criteria->limit = $this->count();
        }else{
            $pageCount=array('pageSize'=>$pageSize,);
            $criteria->limit = $pageSize;
        }
        //echo "<pre>";print_r($criteria);die;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>$pageCount,
            'sort'=>array(
                'defaultOrder'=>'created DESC',
            ),
        ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Before save for converting password to bcrypt
     * @return [type] [description]
     */
    protected function beforeSave() {
        if ($this->scenario == 'registration' || $this->scenario == 'changepassword' || $this->scenario == 'resetpassword') {
            if ($this->password) {
                $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            }
        }

        if ($this->isNewRecord) {
            $this->created = new CDbExpression('NOW()');
        }

        return parent::beforeSave();
    }

    public function userJoinedDate($data) {
        $date       = $data->created;
        $date       = Common::getTimezone($date,'d M y - h: i a');
        echo $date;
    }
    public function validateUserCredentials($username, $password, $memberType) {
        $userDetails = Yii::app()->db->createCommand()
                ->select('*')
                ->from('users t1')
                //->where('(t1.username = :username OR t1.email = :username) AND status = 2', array(':username' => $username))
                ->where('(t1.email = :username) AND status = 2', array(':username' => $username))
                ->queryRow();

        if (password_verify($password, $userDetails['password'])) {
            return $userDetails;
        } else {
            return false;
        }
    }


    public function check_status($data) {
        if ($data->status == 2) {
           $link = "<a class='btn btn-success btn-xs' href='" . Yii::app()->createAbsoluteUrl('vendor/banned', array('id' => $data->id)) . "'>active</a>";
        } elseif ($data->status == 1)  {
            $link = "<a class='btn btn-warning btn-xs' href='" . Yii::app()->createAbsoluteUrl('vendor/activate', array('id' => $data->id)) . "'>inactive</a>";
        }
        else{
            $link = "<a class='btn btn-danger btn-xs' href='" . Yii::app()->createAbsoluteUrl('vendor/deactivate', array('id' => $data->id)) . "'>banned</a>";
       
        }
        echo $link;
    }
    public function RoleChangeRequest($data){
        if($data->role_change_to=="0"){
            $html = "";
        }else{
             if( strpos($data->role_change_to, ',') !== false ) {
              $exploded_result = explode(',',$data->role_change_to);
                $text_array = array();
                for($i=0;$i<count($exploded_result);$i++){
                    if($exploded_result[$i]==0){
                        $text_array[] = "Requester";
                    }else if($exploded_result[$i]==1){
                        $text_array[] = "Doothan"; 
                    }else if($exploded_result[$i]==2){
                        $text_array[] = "Dropbox"; 
                    }
                }
                $role = implode('->',$text_array);
            }
            $html = $role;
        }
        return $html;
    }
    public function CheckAccountStatus($data){
        if($data->member_type=="requester"){
            if($data->role_change_to_flag!=0){
                $acount_status = $data->account_status;
                $user_id = $data->id;
                $docs_pending = ($data->account_status=="DOCUMENTS_PENDING")?"selected":"";
                $verification_pending = ($data->account_status=="CALL_VERIFICATION_PENDING")?"selected":"";
                $approved = ($data->account_status=="APPROVED")?"selected":"";
                $rejected = ($data->account_status=="REJECTED")?"selected":"";
                $html = "";
                $html.="<select id=$user_id class=account_act_drop ref=$user_id>";
                $html.="<option value='DOCUMENTS_PENDING' $docs_pending>Documents Pending</option>";
                $html.="<option value=CALL_VERIFICATION_PENDING $verification_pending>Call Verification Pending</option>";
                $html.="<option value='APPROVED' $approved>Approved</option>";
                $html.="<option value='REJECTED' $rejected>Rejected</option>";
                $html.="</select>";
            }else{
                $html = "Active";
            }
        }else{
            $acount_status = $data->account_status;
            $user_id = $data->id;
            $docs_pending = ($data->account_status=="DOCUMENTS_PENDING")?"selected":"";
            $verification_pending = ($data->account_status=="CALL_VERIFICATION_PENDING")?"selected":"";
            $approved = ($data->account_status=="APPROVED")?"selected":"";
            $rejected = ($data->account_status=="REJECTED")?"selected":"";
            $html = "";
            $html.="<select id=$user_id class=account_act_drop ref=$user_id>";
            $html.="<option value='DOCUMENTS_PENDING' $docs_pending>Documents Pending</option>";
            $html.="<option value=CALL_VERIFICATION_PENDING $verification_pending>Call Verification Pending</option>";
            $html.="<option value='APPROVED' $approved>Approved</option>";
            $html.="<option value='REJECTED' $rejected>Rejected</option>";
        }
        return $html;
        
    }
    public static function getUserAddress($data) {
        
        $customer_id    = $data;
        $addressDetails = UserAddress::model()->findAllByAttributes(array(
            'user_id' => $customer_id, 'is_default' => 1,
        ));
        
        $addressDetails = Yii::app()->db->createCommand()
        ->select('*')
        ->from('user_address t1')
        ->join('users t2', 't1.user_id = t2.id')
        ->where('t1.user_id = ' . $customer_id);
        $addressDetails = $addressDetails->queryAll();
        return $addressDetails;
        
    }
    public function LinkData(){
        return "<a href='#'>Delete</a>";
    }
    public function userAddress($data){
         $customer_id    = $data['id'];
        $addressDetails = UserAddress::model()->findAllByAttributes(array(
            'user_id' => $customer_id, 'is_default' => 1,
        ));
        if($addressDetails){
            $userAddress    = $addressDetails[0]->state . ' ' . $addressDetails[0]->city . ' ' . $addressDetails[0]->postal_code . ' ' . $addressDetails[0]->address_label;
            $userAddress    = wordwrap($userAddress, 18, "<br>", true);
       }else{
        $userAddress='';
       }
        
        echo $userAddress;
    }
    
    public function AdharShow($data){
        if($data->aadhar){
            if(Yii::app()->params['adharImageBucketUrl'].$model->aadhar){
               // $aadhar_url=Yii::app()->params['adharImageBucketUrl'].$data->aadhar;
                $aadhar_url = Yii::app()->baseUrl."/images/success-tick.png";
                $link = '<img src='.$aadhar_url.' alt="" style="width: 20px;height: 20px;"/>';
            }
        }else{
            //$aadhar_url=Yii::app()->request->baseUrl.'/images/no-img.png';
            $aadhar_url = Yii::app()->baseUrl."/images/close.png";
            $link = '<img src='.$aadhar_url.' alt="" style="width: 20px;height: 20px;"/>';
        }
        return $link;
    }
    public function PhotoShow($data){
        if($data->photo_id){
            if(Yii::app()->params['photoImageBucketUrl'].$model->photo_id){
                //$photo_url=Yii::app()->params['photoImageBucketUrl'].$data->photo_id;
                $photo_url = Yii::app()->baseUrl."/images/success-tick.png";
                $link = '<img src='.$photo_url.' alt="" style="width: 20px;height: 20px;"/>';;
            }
        }else{
            //$photo_url=Yii::app()->request->baseUrl.'/images/no-img.png';
            $photo_url = Yii::app()->baseUrl."/images/close.png";
            $link = '<img src='.$photo_url.' alt="" style="width: 20px;height: 20px;"/>';
        }
        return $link;
    }
    public function FullName($data){
        return $data->first_name." ".$data->last_name;
    }
    
        

}
