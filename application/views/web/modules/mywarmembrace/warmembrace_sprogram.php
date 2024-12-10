<style>
        .error-message {
            color: red;
            display: none;
        }
    </style>
<div class="second" style="background-image: url('<?php echo base_url(); ?>assets/img/images/secondbg.jpg');">
        <div class="form-container1">
            <p>En El Video Gratis te Cuenta TODO!</p>
            <h1>¿Cómo alguna madres<br> solteras con dificultades <br> financieras están <br> construyendo un hogar <br>seguro, saludable y limpio <br> para sus hijos como</h1>
            <p>voluntarias para el<br> programa de SUBROGACION</p>
            <form id="myForm" action="" method="post">
            <i class="user1-icon">👤</i>
            <input type="text" id="fullName" name="fullname" placeholder="Enter Your Full Name" required>
            <i class="email1-icon">📧</i>
            <input type="email" id="email" name="email" placeholder="Ingresa e-mail" required>
           <!-- <i class="star1-icon">⭐</i>
            <i class="starr1-icon">⭐</i>-->
            <p class="error-message" id="error-message">Please fill out all required fields.</p>
            <button id="register_btn" type="submit">Watch The Video Now</button>
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
    
    document.getElementById('myForm').addEventListener('submit', handleFormSubmission);
     function handleFormSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    var fullName = document.getElementById("fullName").value;
    var email = document.getElementById("email").value;

    //console.log("adsfds");
    $.ajax({
        url: '<?php echo base_url(); ?>mywarmembrace/handle_form_submission',
        type: 'POST',
        data: {
            fullName: fullName,
            email: email
        },
        dataType: 'json',
        beforeSend: function(){
        $('#register_btn').attr('disabled',true);
        $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
    },
        success: function(response) {
            if (response.status === 'success') {
                // Open YouTube video in a new tab
               alert("Registeration Successful");
               window.location.href = '<?php echo base_url(); ?>mywarmembrace/sprogram-video';
               // window.open('https://www.youtube.com/watch?v=b52IqYNKt-0', '_blank');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function closeWidget() {
    // Select the popup element
    var popup = document.getElementById('myPopup');
    
    // Hide the popup
    popup.style.display = 'none';
}
</script>