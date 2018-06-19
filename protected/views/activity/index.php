
    <section class="content-header">
      <?php
        $this->breadcrumbs = array(
            'Activity'
        );?>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- row -->
      <div class="row">
        <div class="col-md-12">
          <!-- The time line -->
          <ul class="timeline">
                <?php $predate=''; if($model){ foreach($model as $mod){ 
       				$activity_date  = $mod->created_on;
                    $dt = new DateTime($activity_date);
                    $date = $dt->format('Y-M-d');
                    $time = $dt->format('h:i A');?>
            <li class="time-label">
             <?php if($predate!==$mod->month_year_only){ ?>
                  <span class="bg-red">
                    <?php  echo $date; ?>
                  </span>
            </li>
            <?php } ?>
            <li>
              <?php if($mod->type=='REGISTER'){ ?>
                <i class="fa fa-user bg-aqua"></i>
                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $date." ".$time; ?></span>
                <h4 class="timeline-header"><a href="#">New User Registration</a></h4>
                <div class="timeline-body">
                 <?php echo $mod->message; ?>
                </div> 
              </div>
              <?php }else if($mod->type=='LOG IN'){ ?>
                <i class="fa fa-unlock-alt bg-yellow"></i>
                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $date." ".$time; ?></span>
                <div class="timeline-body"><?php echo $mod->message; ?></div>
                
              </div>
              <?php }else if($mod->type=='LOG OUT'){ ?>
                <i class="fa fa-unlock-alt bg-red"></i>
                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $date." ".$time; ?></span>
                <div class="timeline-body"><?php echo $mod->message; ?></div>
                
              </div>
              <?php }else if($mod->type=='Notification'){?>
                <i class="fa fa-bell" aria-hidden="true"></i>
                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $date." ".$time; ?></span>
                <div class="timeline-body"><?php echo $mod->message; ?></div>
                
              </div>
              <?php }else{?> 
                <i class="fa fa-envelope bg-blue"></i>
                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $date." ".$time; ?></span>
                <h3 class="timeline-header"><a href="#">Support Team</a></h3>
                <div class="timeline-body">
                 <?php echo $mod->message; ?>
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
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row" style="margin-top: 10px;">
        
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content-->