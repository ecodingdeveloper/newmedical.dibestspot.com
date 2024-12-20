<?php include 'security.php' ?>

<html>
<head>
    <title>Secure Acceptance - Payment Form Example</title>
    <link rel="stylesheet" type="text/css" href="payment.css"/>
</head>
<body>
<form id="payment_confirmation" action="https://testsecureacceptance.cybersource.com/pay" method="post"/>
<?php
    foreach($_REQUEST as $name => $value) {
        $params[$name] = $value;
    }
?>
<fieldset id="confirmation">
    <legend>Review Payment Details</legend>
    <div>
        <?php
            foreach($params as $name => $value) {
                echo "<div>";
                echo "<span class=\"fieldName\">" . $name . "</span><span class=\"fieldValue\">" . $value . "</span>";
                echo "</div>\n";
            }
        ?>
    </div>
</fieldset>
    <?php echo "<pre>";   print_r($params);  echo "</pre>"; 
        foreach($params as $name => $value) { 
            echo "<input type=\"text\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }
        echo "<input type=\"text\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
    ?>
<input type="submit" id="submit" value="Confirm"/>
</form>
</body>
</html>
