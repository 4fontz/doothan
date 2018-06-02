<?php

class ActivityController extends Controller {

    public $layout = '//layouts/column2';
    public $page_title = 'activity';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
//            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $this->activeLink = 'activity';
        $this->page_title = "Activity";

        $limit = 10;
        $page=isset($_GET['page'])? $_GET['page'] :'1';
        $criteria=new CDbCriteria;
        $criteria->order = 'created_on DESC';
        $criteria->group='created_on';
        $ActivityCount = ActivityLog::model()->findAll($criteria);

        $pages = new CPagination(count($ActivityCount));
            $pages->setPageSize($limit);

            if(!$page) {
                $offset = 0;
            } else {
                $offset = ($page - 1) * $limit;
            }


        $criteria=new CDbCriteria;
        $criteria->order = 'created_on DESC';
        $criteria->group='created_on';
        $criteria->limit=$limit;
        $criteria->offset=$offset;
        $empty_model = new ActivityLog;
        $model = ActivityLog::model()->findAll($criteria);
        $date = array();
        foreach($model as $mod){
            $date_only = explode(' ',$mod->created_on);
            $time=strtotime($date_only[0]);
            $day=date("d",$time);
            $month=date("m",$time);
            $year=date("Y",$time);
            $date[] = $year.'-'.$month;
            $mod->month_year_only=$year.'-'.$month.'-'.$day;
        }
        $empty_model->month_year_only = $date;
        $this->render('index',array('model'=>$model,'pages'=>$pages,'activity_count'=>count($ActivityCount)));
    }

    
}
