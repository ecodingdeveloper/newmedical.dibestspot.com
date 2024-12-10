<html>
<head>
    <title>Secure Acceptance - Payment Form Example</title>
</head>
<body>
<form id="payment_form" action="<?php echo base_url(); ?>book_appoinments/payment_confirmation" method="post">
    <input type="hidden" name="access_key" value="e51d8cea805a3672a578122c3e8a0a84">
    <input type="hidden" name="profile_id" value="F5B823B3-3E28-4DC3-B377-6BB0E2BEC8A0">
    <input type="hidden" name="transaction_uuid" value="<?php echo uniqid() ?>">
    <input type="hidden" name="bill_to" value="11">
    <input type="hidden" name="signed_field_names" value="access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code,bill_to">
    <input type="hidden" name="unsigned_field_names">
    <input type="hidden" name="signed_date_time" value="<?php echo gmdate("Y-m-d\TH:i:s\Z"); ?>">
    <input type="hidden" name="locale" value="en">
    <fieldset>
        <legend>Payment Details</legend>
        <div id="paymentDetailsSection" class="section">
            <span>transaction_type:</span><input type="text" name="transaction_type" size="25"><br/>
            <span>reference_number:</span><input type="text" name="reference_number" size="25"><br/>
            <span>amount:</span><input type="text" name="amount" size="25"><br/>
            <span>currency:</span><input type="text" name="currency" size="25"><br/>
            <span>bill_to_forename:</span><input type="text" name="bill_to_forename"><br/>
            <span>bill_to_surname:</span><input type="text" name="bill_to_surname"><br/>
            <span>bill_to_email:</span><input type="text" name="bill_to_email"><br/>
            <span>bill_to_phone:</span><input type="text" name="bill_to_phone"><br/>
            <span>bill_to_address_line1:</span><input type="text" name="bill_to_address_line1"><br/>
            <span>bill_to_address_city:</span><input type="text" name="bill_to_address_city"><br/>
            <span>bill_to_address_state:</span><input type="text" name="bill_to_address_state"><br/>
            <span>bill_to_address_country:</span><input type="text" name="bill_to_address_country"><br/>
            <span>bill_to_address_postal_code:</span><input type="text" name="bill_to_address_postal_code"><br/>
        </div>
    </fieldset>
    <input type="submit" id="submit" name="submit" value="Submit"/>
</form>
</body>
</html>
