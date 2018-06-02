<?php
if($model->member_type=="requester"){
    $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
        'id' => 'users-grid',
        'dataProvider' => $model->search(),
        'summaryText' => "{start} - {end} of {count}",
        'ajaxUpdate' => true,
        'enableDragDropSorting'=>false,
        //'orderField' => 'id',
        'idField' => 'id',
        'filter' => $model,
        'htmlOptions' => array('class' => 'span12 table-responsive'),
        'itemsCssClass' => 'table',
        'columns' => array(
            array(
                'name'=>'id',
                'header' => 'Id',
                'htmlOptions' => array('style' => 'width: 4%')
            ),
            array(
                'name'=>'first_name',
                'header'=>'Full Name',
                'value'=>array($model,'FullName'),
                'htmlOptions' => array('style' => 'width: 6%'),
            ),
            array(
                'name'=>'email',
                'htmlOptions' => array('style' => 'width: 5%')
            ),
            array(
                'name'=>'phone',
                'htmlOptions' => array('style' => 'width: 5%'),
            ),
            array(
                'header' => 'Role change request',
                'name'=>'role_change_to',
                'type'=>'raw',
                'value'=> array($model,'RoleChangeRequest'),
                'htmlOptions' => array('style' => 'width: 5%')
            ),
            array(
                'name'=>'status',
                'type'=>'raw',
                'value'=> function ($data){
                if($data->status==2)
                    return "<a class='btn btn-success btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/banned', array('id' => $data->id)) . "'>Active</a>";
                    else if($data->status==1)
                        return "<a class='btn btn-warning btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/activate', array('id' => $data->id)) . "'>Inactive</a>";
                        else
                            return "<a class='btn btn-danger btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/deactivate', array('id' => $data->id)) . "'>Banned</a>";
                },
                'htmlOptions' => array('style' => 'width: 4%')
                ),
                array(
                    'header' => 'Account Status',
                    'name'=>'account_status',
                    'type'=>'raw',
                    'value'=> array($model,'CheckAccountStatus'),
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'name' => 'created',
                    'value' => array($model,'userJoinedDate'),
                    // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                
                array(
                    'header' => 'Action',
                    'class' => 'ButtonColumn',
                    'template' => '{update}{delete}{view}{docs}',
                    'htmlOptions' => array('style' => 'width: 10%','class' => "button-column"),
                    'buttons' => array(
                        'update' => array(
                            'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                            'options' => array('class' => "btn btn-primary btn-xs", 'title' => 'Update','style'=>'margin-right:0px'),
                            'url' => function($data) {
                            $url = Yii::app()->createUrl('users/update/' . $data->id);
                            return $url;
                            },
                            'visible'=>'$data->user_address->address!=""',
                            ),
                            'delete' => array(//the name {reply} must be same
                                'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                                'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => 'Delete','style'=>'margin-right:0px','id'=>$data->id),
                                'url' => function($data) {
                                $url = Yii::app()->createUrl('users/customerDelete/' . $data->id);
                                return $url;
                                }
                                ),
                                'view' => array( //the name {reply} must be same
                                    'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                                    'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View'),
                                    'url' => function($data) {
                                    $url = Yii::app()->createUrl('users/customerView?id=' . $data->id);
                                    return $url;
                                    },
                                    'visible'=>'$data->user_address->address!=""',
                                    ),
                                    'docs' => array( //the name {reply} must be same
                                        'label' => '<i class="icon-remove icon-white"></i> Docs', // text label of the button
                                        'options' => array('class'=>"btn btn-success btn-xs ",'title'=>'Docs','style'=>'margin-left: 0px'),
                                        'url' => function($data) {
                                        $url = Yii::app()->createUrl('users/customerDocs?id=' . $data->id);
                                        return $url;
                                        },
                                        'visible'=>'$data->role_change_to_flag!=0'
                                            ),
                                        )
                    ),
                    ),
                    ));
}else{
    $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
        'id' => 'users-grid',
        'dataProvider' => $model->search(),
        'summaryText' => "{start} - {end} of {count}",
        'ajaxUpdate' => true,
        'enableDragDropSorting'=>false,
        //'orderField' => 'id',
        'idField' => 'id',
        'filter' => $model,
        'htmlOptions' => array('class' => 'span12 table-responsive'),
        'itemsCssClass' => 'table',
        'columns' => array(
            array(
                'name'=>'id',
                'header' => 'Id',
                'htmlOptions' => array('style' => 'width: 2%')
            ),
            array(
                'name'=>'first_name',
                'header'=>'Full Name',
                'value'=>array($model,'FullName'),
                'htmlOptions' => array('style' => 'width: 6%'),
            ),
            array(
                'name'=>'email',
                'htmlOptions' => array('style' => 'width: 4%')
            ),
            array(
                'name'=>'phone',
                'htmlOptions' => array('style' => 'width: 5%'),
            ),
            array(
                'header' => 'Role change request',
                'name'=>'role_change_to',
                'type'=>'raw',
                'value'=> array($model,'RoleChangeRequest'),
                'htmlOptions' => array('style' => 'width: 8%'),
            ),
            array(
                'name'=>'status',
                'type'=>'raw',
                'value'=> function ($data){
                if($data->status==2)
                    return "<a class='btn btn-success btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/banned', array('id' => $data->id)) . "'>Active</a>";
                    else if($data->status==1)
                        return "<a class='btn btn-warning btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/activate', array('id' => $data->id)) . "'>Inactive</a>";
                        else
                            return "<a class='btn btn-danger btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/deactivate', array('id' => $data->id)) . "'>Banned</a>";
                },
                'htmlOptions' => array('style' => 'width: 4%')
                ),
                array(
                    'header' => 'Account Status',
                    'name'=>'account_status',
                    'type'=>'raw',
                    'value'=> array($model,'CheckAccountStatus'),
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'name' => 'created',
                    'value' => array($model,'userJoinedDate'),
                    // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'header' => 'Action',
                    'class' => 'ButtonColumn',
                    'template' => '{update}{delete}{view}{docs}',
                    'htmlOptions' => array('style' => 'width: 10%','class' => "button-column"),
                    'buttons' => array(
                        'update' => array(
                            'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                            'options' => array('class' => "btn btn-primary btn-xs", 'title' => 'Update','style'=>'margin-right: 0px'),
                            'url' => function($data) {
                            $url = Yii::app()->createUrl('users/update/' . $data->id);
                            return $url;
                            },
                            'visible'=>'$data->user_address->address!=""',
                            ),
                            'delete' => array(//the name {reply} must be same
                                'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                                'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => 'Delete'),
                                'url' => function($data) {
                                $url = Yii::app()->createUrl('users/customerDelete/' . $data->id);
                                return $url;
                                },
                                'visible'=>'$data->user_address->address!=""',
                                ),
                                'view' => array( //the name {reply} must be same
                                    'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                                    'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View','style'=>'margin-right: 4px'),
                                    'url' => function($data) {
                                    $url = Yii::app()->createUrl('users/customerView?id=' . $data->id);
                                    return $url;
                                    },
                                    'visible'=>'$data->user_address->address!=""',
                                    
                                    ),
                                    'docs' => array( //the name {reply} must be same
                                        'label' => '<i class="icon-remove icon-white"></i> Docs', // text label of the button
                                        'options' => array('class'=>"btn btn-success btn-xs ",'title'=>'Docs','style'=>'margin-left: 0px'),
                                        'url' => function($data) {
                                        $url = Yii::app()->createUrl('users/customerDocs?id=' . $data->id);
                                        return $url;
                                        }
                                        ),
                                        )
                    ),
                    ),
                    ));
}
?>