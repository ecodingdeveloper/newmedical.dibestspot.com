<?php

define ('HMAC_SHA256', 'sha256');

$cybersource_option = !empty(settings("cybersource_option")) ? settings("cybersource_option") : "";
if ($cybersource_option == '1') {
	$secret_key = !empty(settings("sandbox_cyb_secret_key")) ? settings("sandbox_cyb_secret_key") : "";
}
if ($cybersource_option == '2') {
	$secret_key = !empty(settings("live_cyb_secret_key")) ? settings("live_cyb_secret_key") : "";
}

define ('SECRET_KEY', $secret_key);

function sign ($params) {
  return signData(buildDataToSign($params), SECRET_KEY);
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}

?>
