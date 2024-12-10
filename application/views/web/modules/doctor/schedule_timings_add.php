<div class="modal-header">
            <h5 class="modal-title"><?php 
            /** @var array $language */
            echo $language['lg_add_time_slots']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="schedule_form" name="schedule_form" method="post" autocomplete="off">
              <input type="hidden" value="<?php 
              /** @var string $slot */
              echo $slot;?>" name="slot" id="slot" >
              <input type="hidden" name="day_name" id="day_name" value="<?php
                /** @var string $day_name */
               echo $day_name;?>">
              <input type="hidden" name="id_value" id="id_value" value="<?php 
              /** @var string $append_html */
              echo $append_html;?>">
               <input type="hidden" id="slot_count" value="1">
              <div class="hours-info">
                <div class="row form-row hours-cont">
                <div class="col-12 col-md-11">
                <h4 class="h4 text-center breadcrumb-bar px-2 py-1 mx-3 rounded text-white">1<sup>st</sup> <?php 
                 
                echo $language['lg_session']; ?> </h4> 
                <input type="hidden" name="sessions[]" id="sessions_1" value="1">
                  <div class="row form-row">
                    <div class="col-12 col-md-4">
                      <div class="form-group">
                        <label><?php echo $language['lg_start_time']; ?><span class="text-danger">*</span></label>
                        <select class="form-control start_time" name="start_time[1]" onchange="get_end_time(1)" id="start_time_1">
                          <option value=""><?php echo $language['lg_select']; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="form-group">
                        <label><?php echo $language['lg_end_time']; ?><span class="text-danger">*</span></label>
                        <select class="form-control end_time" name="end_time[1]" onchange="get_time_slot(2),get_tokens(1)" id="end_time_1">
                          <option value=""><?php echo $language['lg_select']; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-4"> 
                    <div class="form-group"> 
                    <label><?php echo $language['lg_no_of_tokens']; ?></label> 
                    <input type="text" class="form-control" id="token_1" name="token[1]" readonly=""> 
                    </div> 
                    </div>
                  </div>
                </div>
                
                </div>
              </div>


              <div class="default-opti mb-3">
                <p><?php echo $language['lg_set_as_default']; ?></p>
               <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php 
                    /** @var array $already_day_id */
                    /** @var array $day_id */
                    if (in_array("1", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='1') echo 'checked';?> name="day_id[]" value="1">Sun
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("2", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='2') echo 'checked';?> name="day_id[]" value="2">Mon
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("3", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='3') echo 'checked';?> name="day_id[]" value="3">Tue
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("4", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='4') echo 'checked';?> name="day_id[]" value="4">Wed
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("5", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='5') echo 'checked';?> name="day_id[]" value="5">Thu
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("6", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='6') echo 'checked';?> name="day_id[]" value="6">Fri
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" <?php if (in_array("7", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='7') echo 'checked';?> name="day_id[]" value="7">Sat
                  </label>
                </div>
              </div>
              <div class="add-more mb-3">
                <a href="javascript:void(0);" onclick="add_hours()" class="add-hours"><i class="fa fa-plus-circle"></i> <?php echo $language['lg_add_more']; ?></a>
              </div>
              <div class="submit-section text-center">
                <button type="submit" id="submit_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save']; ?></button>
              </div>
            </form>
          </div>