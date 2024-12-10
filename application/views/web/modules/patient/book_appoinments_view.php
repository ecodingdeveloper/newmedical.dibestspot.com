<?php
function Suffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return 'st';
        case 2:  return 'nd';
        case 3:  return 'rd';
      }
    }
    return 'th';
  }
  /** @var array $language */
?>
<h3 class="card-title text-center text-primary mb-4"><?php 
/** @var string $schedule_date */
echo date('l - d M Y',strtotime(str_replace('/', '-', $schedule_date)));?></h3>
 <div class="row">
 	<?php if(!empty($schedule)){ $i=1; $token=1; foreach ($schedule as $rows) { 

 		$time_zone = $rows['time_zone'];                         
        $current_timezone = $this->session->userdata('time_zone');
        

 		?>
<div class="col-lg-12">
	<h3 class="h3 text-center book-btn2 mt-3 px-5 py-1 mx-3 rounded"><?php echo $i;?><sup><?php echo Suffix($i);?></sup> <?php
	/** @var array $language */
	 echo $language['lg_session']; ?> </h3>
	<div class="text-center mt-3">
		<h4 class="h4 mb-2"><?php echo $language['lg_start_time']; ?> </h4>
		 <span class="h4 btn btn-outline-primary"><b> <?php echo date('h:i A',strtotime(converToTz($rows['start_time'],$current_timezone,$time_zone)));?></b></span>
	</div>
	<div class="token-slot mt-2 border">
	<?php
		$start = strtotime($rows['start_time']);
		$end = strtotime($rows['end_time']);
		$datas = array();

		if ($rows['slot'] >= 5) {
			for ($j = $start; $j <= $end; $j = $j + $rows['slot'] * 60) {
				$datas[] = date('H:i:s', $j);   
			}   
		} else {
			for ($j = $start; $j <= $end; $j = $j + 60 * 60) {
				$datas[] = date('H:i:s', $j);   
			}       
		}

		// To introduce ran dom free slots
		$total_slots = count($datas);  // Total number of slots
		$free_slot_count = 5;  // Number of free slots you want to add (you can adjust this value)

		// Generate random free slots
		$free_slots = array_rand($datas, min($free_slot_count, $total_slots));  // Randomly pick slots

		// If more than 1 free slot is picked, make sure it returns an array
		if (is_array($free_slots)) {
			$free_slots = array_flip($free_slots);  // Convert the array into a key-based array
		} else {
			$free_slots = array($free_slots => true);  // If only one slot is picked
		}

		for ($k = 0; $k < $rows['token']; $k++) {
			$l = $k + 1;
			$start_time = converToTz($schedule_date . ' ' . $datas[$k], $current_timezone, $time_zone);

			// Check if the current slot is free or booked
			if (date('Y-m-d H:i:s') < $schedule_date . ' ' . $datas[$k]) {
				$booked_session = get_booked_session($i, $token, $start_time, $rows['user_id']);

				if ($booked_session >= 1) {
					// If booked, display a booked slot kxckmd
					echo '<div class="form-check-inline visits mr-0">
							<label class="visit-btns" style="background: #f6f5f5;">
							  <input disabled="" type="radio" class="form-check-input">
							  <span class="visit-rsn" data-toggle="tooltip" title="' . date('h:i A', strtotime($start_time)) . '">' . $token . '</span>
							</label>
						  </div>';
				} else {
					// If free (or if we inserted a free slot), display a free slot
					if (isset($free_slots[$k])) {
						// Free slot
						echo '<div class="form-check-inline visits mr-0">
								<label class="visit-btns free-slot" style="background: #f6f5f5;">
								  <input type="radio" class="form-check-input" data-date="' . date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date))) . '" 
								         data-timezone="' . $rows['time_zone'] . '" 
								         data-start-time="' . $datas[$k] . '" 
								         data-end-time="' . $datas[$l] . '" 
								         data-session="' . $i . '" 
								         name="token" value="' . $token . '">
								  <span class="visit-rsn" data-toggle="tooltip" title="' . date('h:i A', strtotime($datas[$k])) . '">Free</span>
								</label>
							  </div>';
					} else {
						// Regular (bookable) slot
						echo '<div class="form-check-inline visits mr-0">
								<label class="visit-btns" style="background: #f6f5f5;">
								  <input type="radio" class="form-check-input" data-date="' . date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date))) . '" 
								         data-timezone="' . $rows['time_zone'] . '" 
								         data-start-time="' . $datas[$k] . '" 
								         data-end-time="' . $datas[$l] . '" 
								         data-session="' . $i . '" 
								         name="token" value="' . $token . '">
								  <span class="visit-rsn" data-toggle="tooltip" title="' . date('h:i A', strtotime($datas[$k])) . '">' . $token . '</span>
								</label>
							  </div>';
					}
				}
			}
			$token++;
		}
	?>
	<hr>
</div>
</div>

<?php $i++; } } else { echo '<div class="col-md-12">
	                            <div class="text-center mt-4">
								 <h4 class="h4 mb-2">' . $language['lg_no_tokens_found'] . ' </h4>
							    </div>
							    </div>'; } ?>
</div>

