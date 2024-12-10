<style>
        .error-message {
            color: red;
            display: none;
        }
    </style>
<div class="first" style="background-image: url('<?php echo base_url(); ?>assets/img/images/seondbg.jpg');">
        <div class="form-container">
            <p>FREE ON-DEMAND VIDEO REVELS</p>
            <h1>HOW TO BUILD YOUR <br> FAMILY WITH SURROGACY</h1>
            <p>Without Having To Spend A Lot Of Money</p>
            <form id="myForm">
            <i class="user-icon">👤</i>
            <input type="text" id="fullName" placeholder="Enter Your Full Name" required>
            <i class="email-icon">📧</i>
            <input type="email" id="email" placeholder="Enter Your Email" required>
           <!-- <i class="star-icon">⭐</i>
            <i class="starr-icon">⭐</i>-->
             <p class="error-message" id="error-message">Please fill out all required fields.</p>
            <button type="submit">Watch The Video Now</button>
            </form>
        </div>
    </div>
		<div class="popup" id="myPopup">
    <a href="javascript:void(0);" class="close-icon" onclick="closeWidget()">x</a>
    <a href="">
      <img src="<?php echo base_url();?>assets/img/images/popup.jpg" alt="">
    </a>
  </div>
  <script>
     document.getElementById('myForm').addEventListener('submit', function(event) {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            const errorMessage = document.getElementById('error-message');

            if (!fullName || !email) {
                console.log("fdd");
                // Show the error message and prevent form submission
                errorMessage.style.display = 'block';
                event.preventDefault(); // Stop form from submitting
            } else {
                // Hide the error message if all fields are filled
                errorMessage.style.display = 'none';
                // Redirect to the URL
                console.log("dss");
                $.ajax({
        url: '<?php echo base_url(); ?>mywarmembrace/handle_form_submission',
        type: 'POST',
        data: {
            fullName: fullName,
            email: email
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Open YouTube video in a new tab
               alert("Registeration Successful");
               window.open('https://mywarmembrace.net/IP-video', '_blank');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
               // window.open('https://mywarmembrace.net/IP-video', '_blank');
            }
        });
function closeWidget() {
    // Select the popup element
    var popup = document.getElementById('myPopup');
    
    // Hide the popup
    popup.style.display = 'none';
}
</script>