
<?php 

// echo '<pre>';
// print_r($this->data['features']);
// foreach ($this->data['features'] as $plan) {
//   echo  $plan->feature_text . '<br>'; // Adjust to display other columns as needed
// }
$totalAmountGTC = 0; 
$percentageGTC = 0; 

foreach ($this->data['gtc'] as $gtc) {
    
    if (strpos($gtc->role_0, '$') === 0) {
        
        $amount = floatval(trim($gtc->role_0, '$'));
        $totalAmountGTC += $amount; 
    }
    elseif (strpos($gtc->role_0, '%') !== false) {
    
        $percentageGTC = floatval(trim($gtc->role_0, '%')) / 100;
    }
    
}

if ($percentageGTC > 0) {
    $totalAmountGTC += $totalAmountGTC * $percentageGTC; 
}

$totalAmountDoctor = 0; 
$percentageDoctor = 0; 

foreach ($this->data['doctor'] as $doctor) {
    
    if (strpos($doctor->role_1, '$') === 0) {
      // echo strpos($doctor->role_1, '$');
        // echo $doctor->role_1; 
        // echo ltrim($doctor->role_1, '$');
$amount = floatval(str_replace(',', '', ltrim($doctor->role_1, '$')));
        // echo $amount;
        $totalAmountDoctor += $amount; 
    }
    elseif (strpos($doctor->role_1, '%') !== false) {
    
        $percentageDoctor = floatval(trim($doctor->role_1, '%')) / 100;
    }
    
}

if ($percentageDoctor > 0) {
    $totalAmountDoctor += $totalAmountDoctor * $percentageDoctor; 
}

$totalAmountPatient = 0; 
$percentagtPatient = 0; 

foreach ($this->data['patient'] as $patient) {
    
    if (strpos($patient->role_2, '$') === 0) {
      // echo strpos($doctor->role_1, '$');
        // echo $doctor->role_1; 
        // echo ltrim($doctor->role_1, '$');
$amount = floatval(str_replace(',', '', ltrim($patient->role_2, '$')));
        // echo $amount;
        $totalAmountPatient += $amount; 
    }
    elseif (strpos($patient->role_2, '%') !== false) {
    
        $percentagtPatient = floatval(trim($patient->role_2, '%')) / 100;
    }
    
}

if ($percentagtPatient > 0) {
    $totalAmountPatient += $totalAmountPatient * $percentagtPatient; 
}

$totalAmountClinic = 0; 
$percentagtClinic = 0; 

foreach ($this->data['clinic'] as $clinic) {
    
    if (strpos($clinic->role_6, '$') === 0) {
      // echo strpos($doctor->role_1, '$');
        // echo $doctor->role_1; 
        // echo ltrim($doctor->role_1, '$');
$amount = floatval(str_replace(',', '', ltrim($clinic->role_6, '$')));
        // echo $amount;
        $totalAmountClinic += $amount; 
    }
    elseif (strpos($clinic->role_6, '%') !== false) {
    
        $percentagtClinic = floatval(trim($clinic->role_6, '%')) / 100;
    }
    
}

if ($percentagtClinic > 0) {
    $totalAmountClinic += $totalAmountClinic * $percentagtClinic; 
}

$totalAmountPharmacy = 0; 
$percentagtPharmacy = 0; 

foreach ($this->data['pharmacy'] as $pharmacy) {
    
    if (strpos($pharmacy->role_5, '$') === 0) {
      // echo strpos($doctor->role_1, '$');
        // echo $doctor->role_1; 
        // echo ltrim($doctor->role_1, '$');
$amount = floatval(str_replace(',', '', ltrim($pharmacy->role_5, '$')));
        // echo $amount;
        $totalAmountPharmacy += $amount; 
    }
    elseif (strpos($pharmacy->role_5, '%') !== false) {
    
        $percentagtPharmacy = floatval(trim($pharmacy->role_5, '%')) / 100;
    }
    
}

if ($percentagtPharmacy > 0) {
    $totalAmountPharmacy += $totalAmountPharmacy * $percentagtPharmacy;
}

$totalAmountLab = 0; 
$percentagtLab = 0; 

foreach ($this->data['lab'] as $lab) {
    
    if (strpos($lab->role_4, '$') === 0) {
      // echo strpos($doctor->role_1, '$');
        // echo $doctor->role_1; 
        // echo ltrim($doctor->role_1, '$');
$amount = floatval(str_replace(',', '', ltrim($lab->role_4, '$')));
        // echo $amount;
        $totalAmountLab += $amount; 
    }
    elseif (strpos($lab->role_4, '%') !== false) {
    
        $percentagtLab = floatval(trim($lab->role_4, '%')) / 100;
    }
    
}

if ($percentagtLab > 0) {
    $totalAmountLab += $totalAmountLab * $percentagtLab;
}

// // Print the total amount
// echo "Total Amount: $" . number_format($totalAmount, 2);


// // Print the total amount
// // echo "Total Amount: $" . number_format($totalAmount, 2);


$merged = [];


$featuresCount = count($this->data['features']);
$gtcCount = count($this->data['doctor']);


for ($i = 0; $i < min($featuresCount, $gtcCount); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['doctor'][$i]->role_1;

    $merged[$featureText] = $role;
}

$merged1 = [];


$featuresCount1 = count($this->data['features']);
$gtcCount1 = count($this->data['clinic']);


for ($i = 0; $i < min($featuresCount1, $gtcCount1); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['clinic'][$i]->role_6;
    $merged1[$featureText] = $role;
}

$merged2 = [];


$featuresCount2 = count($this->data['features']);
$gtcCount2 = count($this->data['gtc']);


for ($i = 0; $i < min($featuresCount2, $gtcCount2); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['gtc'][$i]->role_0;

    $merged2[$featureText] = $role;
}

$merged3 = [];


$featuresCount3 = count($this->data['features']);
$gtcCount3 = count($this->data['patient']);


for ($i = 0; $i < min($featuresCount3, $gtcCount3); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['patient'][$i]->role_2;

    $merged3[$featureText] = $role;
}

$merged4 = [];


$featuresCount4 = count($this->data['features']);
$gtcCount4 = count($this->data['pharmacy']);


for ($i = 0; $i < min($featuresCount4, $gtcCount4); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['pharmacy'][$i]->role_5;

    $merged4[$featureText] = $role;
}

$merged5 = [];


$featuresCount5 = count($this->data['features']);


$gtcCount5 = count($this->data['lab']);


for ($i = 0; $i < min($featuresCount5, $gtcCount5); $i++) {

    $featureText = $this->data['features'][$i]->feature_text;
    $role = $this->data['lab'][$i]->role_4;

    $merged5[$featureText] = $role;
}


// print_r($this->data['gtc']);
// print_r($this->data['doctor']);
// print_r($this->data['patient']);
// print_r($this->data['lab']);
// print_r($this->data['pharmacy']);
// print_r($this->data['clinic']);


// echo '</pre>';
?>

<style>
.card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 84%;
    text-align: center;
    padding: 10px;
    margin:auto;
}

.card-heading {
    font-size: 24px;
    margin-bottom: 15px;
}

.card-image {
    width: 100%;
    border-radius: 5px;
    margin-bottom: 15px;
}

.card-list {
    list-style: none;
    padding: 0;
}

/* .card-item {
    background-color: #007bff;
    color: white;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
} */

/* .card-item:hover {
    background-color: #0056b3;
} */
.card-container{
  display:flex;
  justify-content: space-evenly;
  padding-bottom:80px;
}
.head-card{
  text-align:center;
  padding:60px;
}

ul.card-list li {
    display: flex;
    justify-content: space-between;
}
.spann{
  width: 25px;
}
span {
    font-size: 12px;
    font-weight: 500;
    text-align: left;
}

.card:hover {
    box-shadow: 5px -5px 15px #15558d;
    cursor: pointer;
}

.monthlycharge{
    font-weight: bold;
    justify-content: center !important;
    margin-top: 20px;
    background-color: aqua;
    border-radius: 10px;
    padding: 10px 0;
}
/* .anqr:hover .list{
    color: #09dca4;
} */
.anqr:hover{
    color: black;
}
</style>
<div class="main-container">
<h1 class="head-card">Subscription Plans</h1>

<!-- <div class="plan-selector">
        <label for="planSelect">Choose Plan: </label>
        <select id="planSelect">
            <option value="monthly" selected>Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div> -->
    <?php 
                               $get_doctor_plan = $this->db->select('plan')->from('plan')->where('profile', 'doctor') ->get()->result_array();
                                $get_patient_plan = $this->db->select('plan')->from('plan')->where('profile', 'patient')->get()->result_array();
                                $get_gtc_plan = $this->db->select('plan')->from('plan')->where('profile', 'gtc')->get()->result_array();
                                $get_pharmacy_plan = $this->db->select('plan')->from('plan')->where('profile', 'pharmacy')->get()->result_array();
                                $get_lab_plan = $this->db->select('plan')->from('plan')->where('profile', 'lab')->get()->result_array();
                                $get_clinic_plan = $this->db->select('plan')->from('plan')->where('profile', 'clinic')->get()->result_array();

                                // echo '<pre>';
                                // print_r($get_doctor_plan[0]['plan']);
                                // print_r($get_patient_plan[0]['plan']);
                                // print_r($get_gtc_plan[0]['plan']);
                                // print_r($get_pharmacy_plan[0]['plan']);
                                // print_r($get_lab_plan[0]['plan']);
                                // print_r($get_clinic_plan[0]['plan']);

                                // echo '</pre>';
                                // die("dsfad");
                                ?>

<div class="card-container">
<a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_doctor_plan[0]['plan'] === 'monthly') ? $totalAmountDoctor : $totalAmountDoctor * 12; ?>">


<div class="card">
    <h2 class="card-heading">Basic (Doctors)</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
       <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0' >
       <tbody>";

foreach ($merged as $key => $value) {
   echo "<tr>
           <td>$key</td>
           <td>$value</td>
         </tr>";
}

echo "</tbody></table>";
       
       ?>
    <li class="monthlycharge">
    <?php 
        if ($get_doctor_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountDoctor;
        } else {
            echo 'Annual Charges - $' . ($totalAmountDoctor * 12);
        }
    ?>
</li>

    </ul>
</div></a>

<a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_clinic_plan[0]['plan'] === 'monthly') ? $totalAmountClinic : $totalAmountClinic * 12; ?>">
<div class="card">
    <h2 class="card-heading">Enterprise (Clinics)</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
    <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0'>
        <tbody>";

foreach ($merged1 as $key => $value) {
    echo "<tr>
            <td>$key</td>
            <td>$value</td>
          </tr>";
}

echo "</tbody></table>";

       
       ?>
       <li class="monthlycharge">
    <?php 
        if ($get_clinic_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountClinic;
        } else {
            echo 'Annual Charges - $' . ($totalAmountClinic * 12);
        }
    ?>
</li>
    </ul>
</div></a>

<a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_gtc_plan[0]['plan'] === 'monthly') ? $totalAmountGTC : $totalAmountGTC * 12; ?>">
<div class="card">
    <h2 class="card-heading">DIBEST Medical: GTC</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
    <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0'>
       <tbody>";

foreach ($merged2 as $key => $value) {
   echo "<tr>
           <td>$key</td>
           <td>$value</td>
         </tr>";
}

echo "</tbody></table>";
       
       ?>
      <li class="monthlycharge">
    <?php 
        if ($get_gtc_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountGTC;
        } else {
            echo 'Annual Charges - $' . ($totalAmountGTC * 12);
        }
    ?>
</li>
    </ul>
</div></a>
  </div>
  <div class="card-container">
    
  <a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_patient_plan[0]['plan'] === 'monthly') ? $totalAmountPatient : $totalAmountPatient * 12; ?>">
<div class="card">
    <h2 class="card-heading">Patient</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
    <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0'>
       <tbody>";

foreach ($merged3 as $key => $value) {
   echo "<tr>
           <td>$key</td>
           <td>$value</td>
         </tr>";
}

echo "</tbody></table>";
       
       ?>
        <li class="monthlycharge">
    <?php 
        if ($get_patient_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountPatient;
        } else {
            echo 'Annual Charges - $' . ($totalAmountPatient * 12);
        }
    ?>
</li>
    </ul>
</div></a>

<a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_pharmacy_plan[0]['plan'] === 'monthly') ? $totalAmountPharmacy : $totalAmountPharmacy * 12; ?>">
<div class="card">
    <h2 class="card-heading">Pharmacy</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
    <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0'>
       <tbody>";

foreach ($merged4 as $key => $value) {
   echo "<tr>
           <td>$key</td>
           <td>$value</td>
         </tr>";
}

echo "</tbody></table>";
       
       ?>
        <li class="monthlycharge">
    <?php 
        if ($get_pharmacy_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountPharmacy;
        } else {
            echo 'Annual Charges - $' . ($totalAmountPharmacy * 12);
        }
    ?>
</li>
    </ul>
</div></a>

<a class='anqr' id="doctor-link" href="<?php echo base_url(); ?>amount?totalAmount=<?php echo ($get_lab_plan[0]['plan'] === 'monthly') ? $totalAmountLab : $totalAmountLab * 12; ?>">
<div class="card">
    <h2 class="card-heading">Lab</h2>
    <!-- <img src="<?php echo base_url();?>uploads/package_image/card.jpg" alt="Card Image" class="card-image"> -->
    <h2 class="card-heading">Plan Features</h2>
    <ul class="card-list">
    <?php
       
       echo "<table border='1' cellpadding='5' cellspacing='0'>
       <tbody>";

foreach ($merged5 as $key => $value) {
   echo "<tr>
           <td>$key</td>
           <td>$value</td>
         </tr>";
}

echo "</tbody></table>";
       
       ?>
        <li class="monthlycharge">
    <?php 
        if ($get_lab_plan[0]['plan'] === 'monthly') {
            echo 'Monthly Charges - $' . $totalAmountLab;
        } else {
            echo 'Annual Charges - $' . ($totalAmountLab * 12);
        }
    ?>
</li>
    </ul>
</div>
  </a>
</div> 
</div> 
<script>

    

</script>

