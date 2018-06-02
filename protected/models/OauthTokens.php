<?php

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * This is the model class for table "oauth_tokens".
 *
 * The followings are the available columns in table 'oauth_tokens':
 * @property integer $id
 * @property integer $user_id
 * @property string $client_id
 * @property string $facebook_token
 * @property string $access_token
 * @property string $created
 * @property integer $expires
 *
 * The followings are the available model relations:
 * @property OauthClients $client
 * @property Users $user
 */
class OauthTokens extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'oauth_tokens';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, client_id, access_token, expires', 'required'),
            array('id, user_id, expires', 'numerical', 'integerOnly' => true),
            array('client_id', 'length', 'max' => 32),
            array('access_token', 'length', 'max' => 60),
            array('facebook_token', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, client_id, facebook_token, access_token, created, expires,device_type,device_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'OauthClients', 'client_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'client_id' => 'Client',
            'facebook_token' => 'Facebook Token',
            'access_token' => 'Access Token',
            'created' => 'Created',
            'expires' => 'Expires',
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('client_id', $this->client_id, true);
        $criteria->compare('facebook_token', $this->facebook_token, true);
        $criteria->compare('access_token', $this->access_token, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('expires', $this->expires);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OauthTokens the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function createTokenForUser($params) {
    //public function createTokenForUser($userId, $clientId, $expires)
        $token = Uuid::uuid5(Uuid::uuid4(), $params['userId'])->toString();
        $model = new self;
        $model->user_id = $params['userId'];
        $model->client_id = $params['clientId'];
        $model->access_token = $token;
        $model->expires = $params['expires'];
        $model->facebook_token = isset($params['facebookToken']) ? $params['facebookToken'] : '';
        $model->device_id = isset($params['device_id']) ? $params['device_id'] : '';
        $model->device_type = isset($params['device_type']) ? $params['device_type'] : '';
        $first_flag = isset($params['first_flag']) ? $params['first_flag'] : '0';
        if ($model->save()) {
            $userdetails = Users::model()->findByPk($params['userId']);
            /*if($first_flag!=0){
                if($userdetails->account_status=='APPROVED'){
                    $login_status = "true";
                    $message = "rejected";
                }else{
                    $login_status = "false";
                    $string = strtolower(str_replace("_", " ", $userdetails->account_status));
                    $message = ucfirst($string);
                }
            }else{
                
                $message = "sign_up";
            }
            return array(
                'user_id' => $model->user_id,
                'access_token' => $token,
                'expires' => $params['expires'],
                'login_status'=> $login_status,
                'message'=>$message
            );*/
            $login_status = "true";
            return array(
                'user_id' => $model->user_id,
                'access_token' => $token,
                'expires' => $params['expires'],
                'login_state'=> $userdetails->login_state,
                'login_status'=> $login_status,
            );
        }else{
            $login_status = "false";
            return array(
                'access_token' => $token,
                'login_status'=> $login_status,
            );
        }
        
    }

    public static function getTokenInfo($accessToken) {
        if ($tokenDetails = self::model()->findByAttributes(array('access_token' => $accessToken))) {
            if ($tokenDetails['expires'] > time()) {
                return $tokenDetails;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
