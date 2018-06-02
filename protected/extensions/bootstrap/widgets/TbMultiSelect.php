<?php

/**
 * This is simple asset component for davidstutz/bootstrap-multiselect
 *
 * @license http://opensource.org/licenses/MIT MIT
 * @since          1.0
 * @author         Vladimir Yazykov <neizerth@gmail.com>
 */
class TbMultiSelect extends CInputWidget {

    public $data;
    public $pluginOptions;

    public function run() {
        $this->renderField();
        $this->registerClientScript();
    }

    public function renderField() {
        $this->htmlOptions['multiple'] = 'multiple';
        if ($this->hasModel()) {
            echo CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
        } else {
            echo CHtml::dropDownList($this->name, $this->value, $this->data, $this->htmlOptions);
        }
    }

    public function registerClientScript() {
        $baseDir = Yii::getPathOfAlias('application.extensions.bootstrap.assets');
        $assets = Yii::app()->getAssetManager()->publish($baseDir);
        $cs = Yii::app()->clientScript;
        list($name, $id) = $this->resolveNameID();
        $cs->registerScriptFile($assets . '/js/bootstrap-multiselect.js');
        $cs->registerCssFile($assets . '/css/bootstrap-multiselect.css');
        $cs->registerScript('multiselect-' . uniqid(), "$('#{$id}').multiselect(" . CJSON::encode($this->pluginOptions) . ")");
    }

}

?>