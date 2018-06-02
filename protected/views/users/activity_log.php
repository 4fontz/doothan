<section class="content-header">
  <?php
    $this->breadcrumbs = array(
        'Activity'
    );?>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <ul class="timeline">
                <?php $predate=''; if($model){ foreach($model as $mod){ ?>
            <li class="time-label">
             <?php if($predate!==$mod->month_year_only){ ?>
                  <span class="bg-red">
                    <?php   $date  = $mod->created_on;
                        echo $date  = Common::getTimezone($date,'d M y');
                 ?>
                  </span>
            </li>
            <?php } ?>
            <li>
              <?php if($mod->type=='REGISTER'){ ?>
                <i class="fa fa-user bg-aqua"></i>
                 <div class="timeline-item">
                <?php   $activity_date  = $mod->created_on;
                         $time  = Common::getTimezone($activity_date,'h: i a');
                 ?>
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $time; ?></span>
                <h4 class="timeline-header"><a href="#">New User Registration</a></h4>
                <div class="timeline-body">
                 <?php echo $mod->message; ?>
                </div> 
              </div>
              <?php }else if($mod->type=='LOG IN'){ ?>
                <i class="fa fa-unlock-alt bg-yellow"></i>
                 <div class="timeline-item">
                <?php   $activity_date  = $mod->created_on;
                         $time  = Common::getTimezone($activity_date,'h: i a');
                 ?>
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $time; ?></span>
                <div class="timeline-body"><?php echo $mod->message; ?></div>
                
              </div>
              <?php }else if($mod->type=='LOG OUT'){ ?>
                <i class="fa fa-unlock-alt bg-red"></i>
                 <div class="timeline-item">
                <?php   $activity_date  = $mod->created_on;
                         $time  = Common::getTimezone($activity_date,'h: i a');
                 ?>
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $time; ?></span>
                <div class="timeline-body"><?php echo $mod->message; ?></div>
                
              </div>
              <?php }else{?> 
                <i class="fa fa-envelope bg-blue"></i>
                 <div class="timeline-item">
                <?php   $activity_date  = $mod->created_on;
                         $time  = Common::getTimezone($activity_date,'h: i a');
                 ?>
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo $time; ?></span>
                <h3 class="timeline-header"><a href="#">Support Team</a> Verification mail sent Successfully</h3>
                <div class="timeline-body">
                 <?php echo $mod->message; echo $mod->type; ?>
                </div>
              </div>
              <?php }?>
            </li>
            <?php 
              $predate=$mod->month_year_only;
              } 
            } ?>
          <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li> 
          </ul>
          <div class="pull-right">
          <?php 
      if($pages){
        $this->widget('CLinkPager', array(
                'currentPage' => $pages->getCurrentPage(),
                'itemCount' => $activity_count,
                'pageSize' => $pages->getPageSize(),
                'maxButtonCount' => 5,
                'header'=>'',
                'selectedPageCssClass' => 'active',
                'htmlOptions'=>array('class'=>'pagination'),
            ));
      }
      ?>
    </div>
        </div>
      </div>
      <div class="row" style="margin-top: 10px;">
      </div>
    </section>