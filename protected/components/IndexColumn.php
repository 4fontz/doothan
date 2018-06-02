<?php
/**
 * Created by PhpStorm.
 * User: shyam
 * Date: 8/9/16
 * Time: 8:20 AM
 */

Yii::import('zii.widgets.grid.CGridColumn');

class IndexColumn extends CGridColumn {

    public $sortable = false;

    public function init()
    {
        parent::init();
    }

    protected function renderDataCellContent($row,$data)
    {
        $pagination = $this->grid->dataProvider->getPagination();
        $index = $pagination->pageSize * $pagination->currentPage + $row + 1;
        echo $index;
    }

}
?>