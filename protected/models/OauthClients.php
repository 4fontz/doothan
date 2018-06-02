<?php

/**
 * This is the model class for table "oauth_clients".
 *
 * The followings are the available columns in table 'oauth_clients':
 * @property string $client_id
 * @property string $client_secret
 * @property string $created
 *
 * The followings are the available model relations:
 * @property OauthTokens[] $oauthTokens
 */
class OauthClients extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'oauth_clients';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('client_id, client_secret, created', 'required'),
            array('client_id, client_secret', 'length', 'max'=>32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('client_id, client_secret, created', 'safe', 'on'=>'search'),
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
            'oauthTokens' => array(self::HAS_MANY, 'OauthTokens', 'client_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'client_id' => 'Client',
            'client_secret' => 'Client Secret',
            'created' => 'Created',
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

        $criteria->compare('client_id',$this->client_id,true);
        $criteria->compare('client_secret',$this->client_secret,true);
        $criteria->compare('created',$this->created,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OauthClients the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function validateClient($clientId, $clientSecret)
    {
        $clientDetails = self::model()->findByAttributes(array(
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ));

        if ($clientDetails) {
            return $clientDetails;
        } else {
            return false;
        }
    }
}