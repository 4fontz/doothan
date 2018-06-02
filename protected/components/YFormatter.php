<?php
class YFormatter extends CFormatter {
   public function format($value,$type)
    {
        $params=null;
        if(is_array($type)) {
            $params=$type;
            $type=$type['type'];
        }
        $method='format'.$type;
        if(method_exists($this,$method)) {
            if($params===null) {
                return $this->$method($value);
            } else {
                return $this->$method($value,$params);
            }
        } else {
            throw new CException(Yii::t('yii','Unknown type "{type}".',array('{type}'=>$type)));
        }
    }
 
    /** Added '$params' */
    public function formatShortText($value,$params=array()) {
        if(isset($params['length'])) {
            $len=$params['length'];
        } else {
            $len=$this->shortTextLimit;
        }
        if(strlen($value)>$len) {
            $retval=CHtml::tag('span',array('title'=>$value),CHtml::encode(mb_substr($value,0,$len-3,Yii::app()->charset).'...'));
        } else {
            $retval=CHtml::encode($value);
        }
        return $retval;
    }
}