<div class="head" style="display: flex; justify-content:center;">
  <img src="<?php echo base_url();?>assets/img/images/small_logo.png" alt="" style="width:50px;">
  <h1 style="margin:0 !important;">MyWarmEmbrace.com</h1>
</div>
<div class="blue_container"  style="background-image: url('<?php echo base_url();?>assets/img/images/bg2.jpg'); background-position: center;background-size: cover; background-attachment:fixed; position:relative; ">

  <div class="training" style="position:relative; z-index:11;  text-align:center; padding:40px 0; text-align:center;">
  <h2 style="color:white;" >Congrats! Your Training Wil Be Starting Soon!</h2>
  <p style="color:#ffd700;    text-decoration: underline;" >Please Read The Indtructions Below To Attend</p>
  </div>

<div class="second_container" style="display: flex; justify-content:center; padding-bottom:50px;">
  <div class="image_container" style="z-index: 11; width:40%;">
  <img src="<?php echo base_url();?>assets/img/images/bg1.webp" alt=""></div>
  <div class="content_container" style="z-index: 11; padding: 10px 27px; background: white; width:40%; border-radius:10px; text-align:center;">
    <p>This Is Your Event Ticket For Your</p>
    <h1 style="color:#e67a19;" >Colombia Surrogacy Management Strategy</h1>
    <P>Dont't forget to make your calender.</P>
    <p>You'll be <span style="color: #e74c49;text-decoration: underline;">automatically redirected</span> to the training when the timer reaches 00:00:00</p>
    <div class="begin_container" style="text-align: center;"><span style="color:#217bb6;text-decoration: underline;">Class Begins:</span>
    <h4 id="session-time">Today @ 10:00 AM</h4></div>
    <div class="time-a">
														<div class="a com" style="color: black !important;">
																<p id="hours">00</p>
																<h4 style="padding: 10px 0; font-size: 17px;">HOURS</h4>
														</div>
														<div class="b com"style="color: black !important;">
																<p id="minutes">00</p>
																<h4 style="padding: 10px 0; font-size: 17px;">MINS</h4>
														</div>
														<div class="c com"style="color: black !important;">
																<p id="seconds">00</p>
																<h4 style="padding: 10px 0; font-size: 17px;">SECS</h4>
														</div>
												</div>
  </div>
  </div>
  <p style="text-align: center;
    color: antiquewhite;
    margin: 0 !important;
    padding: 20px 0;" >© 2024 MyWarmEmbrace.com All Rights Reserved. , , . Contact Us. Terms of Service . Privacy Policy</p>
</div>
<script> const redirectUrl = '<?php echo base_url();?>mywarmembrace/ip-live-webinar'; // Replace with your desired URL
console.log(redirectUrl);
function updateCountdown() {
    const now = new Date();
    let nextSessionTime = new Date(now);

    // Calculate the next session time, which is the next 5-minute mark
    nextSessionTime.setMinutes(Math.ceil(now.getMinutes() / 5) * 5);
    nextSessionTime.setSeconds(0);
    nextSessionTime.setMilliseconds(0);

    // If the next session time is the same as now (e.g., exactly on a 5-minute mark), add 5 minutes
    if (nextSessionTime <= now) {
        nextSessionTime.setMinutes(nextSessionTime.getMinutes() + 5);
    }

    // Calculate time difference
    const timeDifference = nextSessionTime - now;
    console.log(now);
    console.log(timeDifference);
    const hours = Math.floor(timeDifference / (1000 * 60 * 60));
    const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

    // Update countdown display
    document.getElementById('hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');

    // Update next session time display
    const displayTime = nextSessionTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    document.getElementById('session-time').textContent = `Next session at ${displayTime}`;

    // Check if the countdown has reached zero
    if (timeDifference <= 2000) {
      console.log("here");
        clearInterval(countdownInterval); // Stop the timer
        window.location.href = redirectUrl; // Redirect to the specified URL
    }
}

const countdownInterval = setInterval(updateCountdown, 1000); // Update every second
updateCountdown(); // Initial call to display time immediately
</script>
    </script>

