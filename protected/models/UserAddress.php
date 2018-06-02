<?php

/**
 * This is the model class for table "user_address".
 *
 * The followings are the available columns in table 'user_address':
 * @property integer $id
 * @property string $created
 * @property string $modified
 * @property string $block
 * @property string $room
 * @property string $street_name
 * @property string $property_name
 * @property integer $postal_code
 * @property string $address_label
 * @property string $place_id
 * @property string $geo_location
 * @property integer $user_id
 */
class UserAddress extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array(' address, state, country, postal_code, city', 'required'),
            array('address, state, postal_code, city','required', 'on' => 'admin_profile_update'),
            array('postal_code,user_id,status', 'numerical', 'integerOnly' => true),
            array('created', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created, address, state, country, postal_code, city, address_label, place_id, geo_location, user_id,status', 'safe', 'on' => 'search'),

        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user_info'=>array(self::BELONGS_TO, 'Users', array('user_id'=>'id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created' => 'Created',
            'modified' => 'Modified',
            'city' => 'city',
            'property_name' => 'property name',
            'postal_code' => 'postal code',
            'address_label' => 'address label',
            'address'=>'Address',
            'state'=>'State',
            'country'=>'Country',
            'place_id' => 'place',
            'geo_location' => 'geo location',
            'user_id' => 'user',
            'status' => 'status',
            'is_default' => 'is default'
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('postal_code', $this->postal_code);
        $criteria->compare('address_label', $this->address_label, true);
        $criteria->compare('place_id', $this->place_id, true);
        $criteria->compare('geo_location', $this->geo_location, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('is_default',$this->is_default);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserAddress the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getUserAddressDetails() {

        $userId     = $this->user_id;
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        $criteria = new CDbCriteria;
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->select = 't.*';
        $criteria->join ='LEFT JOIN users ON  users.id = t.user_id';
      //  $criteria->join .= ' LEFT JOIN bookings ON bookings.id = user_promo_code.booking_id';
        $criteria->condition = "t.user_id = $userId";
        $criteria->addCondition('t.status = 1', 'AND');
        
        //$queries    =  PromoCode::model()->findAll($criteria);
        if($pageSize==-1){
            $pageCount=array('pageSize'=>$this->count(),);
            $criteria->limit = $this->count();
        }else{
            $pageCount=array('pageSize'=>$pageSize,);
            $criteria->limit = $pageSize;
        }
        
        $dataProvider    = new CActiveDataProvider('UserAddress', array(
            'criteria' => $criteria,
            'pagination'=>$pageCount,
        ));
        return $dataProvider;
    }

    public function addressView($data) {

        $address    = $data->state . ' ' . $data->city . ' ' . $data->postal_code . ' ' . $data->address_label;
        $address    = wordwrap($address, 18, "<br>", true);
        echo $address;

    }

    public function editButton($data) {

        $url = Yii::app()->createUrl('users/edituseraddress/' . $data->id);
        echo "<a href='$url' class='btn btn-primary btn-xs' style='margin-right:5px;'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
        
      //  echo $data->room . ' ' . $data->block . ' ' . $data->street_name . ' ' . $data->property_name;
       
    }

    public function deleteButton($data) {

        

        $url = Yii::app()->createUrl('therapist/deletetherapistaddress/' . $data->id);
        echo "<a href='$url' class='btn btn-danger btn-xs delete' style='margin-right:5px;'><i class='fa fa-trash-o' aria-hidden='true'></i></a>";
        //$url = Yii::app()->createUrl('users/usraddressdelete/' . $data->id);
       // echo "<a href='$url' . $data->id);' class='btn btn-danger btn-xs delete'><i class='fa fa-trash-o' aria-hidden='true'></a>";
      //  echo $data->room . ' ' . $data->block . ' ' . $data->street_name . ' ' . $data->property_name;
        
  }


}
