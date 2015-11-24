<!--
/*
/
/ FedEx API INTEGRATION PHP (Non SOAP Request)
/ Developer : Suraj Jain
/ Blog : https://developersurajjain.blogspot.com/
/ Website : http://www.surajjain.com/
/ Email : mail.surajjain@gmail.com
/
*/
-->
<?php         
$xml_file = "xml/ValidatePostalRequest.xml";

/*
/ load xml object
*/
$xml= simplexml_load_file($xml_file);

/*
/ Fedex parameters
*/
$UserCredential_Key 		= 'DEVELOPER_KEY';
$UserCredential_Password 	= 'DEVELOPER_PASSWORD';
$AccountNumber 				= 'FEDEX_ACCOUNT_NUMBER';
$MeterNumber 				= 'FEDEX_METER_NUMBER';

$xml->Body->ValidatePostalRequest->WebAuthenticationDetail->UserCredential->Key 		= $UserCredential_Key;
$xml->Body->ValidatePostalRequest->WebAuthenticationDetail->UserCredential->Password 	= $UserCredential_Password;
$xml->Body->ValidatePostalRequest->ClientDetail->AccountNumber 							= $AccountNumber;
$xml->Body->ValidatePostalRequest->ClientDetail->MeterNumber 							= $MeterNumber;
/* 
/ s/w inputs
*/
$CustomerTransactionId 	= 'ValidatePostalRequest_Basic';
$xml->Body->ValidatePostalRequest->TransactionDetail->CustomerTransactionId 			= $CustomerTransactionId;

$StreetLines 			= 'Address';
$City 					= 'City'; //eg. Bangalore
$StateOrProvinceCode 	= 'State'; // eg. KA
$PostalCode 			= 560001;
$CountryCode 			= 'Country'; // eg. IN

$xml->Body->ValidatePostalRequest->Address->StreetLines 			= $StreetLines;
$xml->Body->ValidatePostalRequest->Address->City 					= $City;
$xml->Body->ValidatePostalRequest->Address->StateOrProvinceCode 	= $StateOrProvinceCode;
$xml->Body->ValidatePostalRequest->Address->PostalCode 				= $PostalCode;
$xml->Body->ValidatePostalRequest->Address->CountryCode 			= $CountryCode;

file_put_contents($xml_file, $xml->asXML());
		 
// Read the XML to send to the Web Service 
$xml= simplexml_load_file($xml_file);
$fh = fopen($xml_file, 'r'); 
$xml_data = fread($fh, filesize($xml_file)); 
fclose($fh); 
		
$url = "https://wsbeta.fedex.com:443/web-services"; 
$page = "/xml"; 
$headers = array( 
	"POST ".$page." HTTP/1.0", 
	"Referrer: COMPANY NAME",
	"Host: wsbeta.fedex.com",
	"Port: 443",
	"Accept: image/gif, image/jpeg, image/pjpeg, text/plain, text/html, */*",
	"Content-type: text/xml;charset=\"utf-8\"", 
	"Content-length: ".strlen($xml_data)
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// Apply the XML to our curl call 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); 

$data = curl_exec($ch); 

if(curl_errno($ch)) { 
	print "Error: " . curl_error($ch); 
} else { 
	
	$requestStatus = $status = $source = $code = $message = $locMessage = $cusTxnId = $serviceId = $major = $intermediate = $minor = '';	
	$CleanedPostalCode = $LocationId = $LocationNumber = $CountryCode = $StateOrProvinceCode = $PostalCode = $ServiceArea = $AirportId = $RestrictedShipmentSpecialServices = '';
	
	$response = simplexml_load_string($data, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");	
	$soap = $response->xpath('//SOAP-ENV:Body');
	if(isset($soap[0])) {
		$body = $soap[0];
		
		// required
		$requestStatus 			= $body->ValidatePostalReply->HighestSeverity;
		$code 					= $body->ValidatePostalReply->Notifications->Code;
		$message 				= $body->ValidatePostalReply->Notifications->Message;		
		$cusTxnId 				= $body->ValidatePostalReply->TransactionDetail->CustomerTransactionId;
		$location 				= $body->ValidatePostalReply->Location;
		$CleanedPostalCode 		= $body->ValidatePostalReply->PostalDetail->CleanedPostalCode;
		$LocationId 			= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->LocationId;
		$LocationNumber 		= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->LocationNumber;
		$CountryCode 			= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->CountryCode;
		$StateOrProvinceCode 	= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->StateOrProvinceCode;
		$PostalCode 			= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->PostalCode;
		$ServiceArea 			= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->ServiceArea;
		$AirportId 				= $body->ValidatePostalReply->PostalDetail->LocationDescriptions->AirportId;
		$RestrictedShipmentSpecialServices = $body->ValidatePostalReply->PostalDetail->LocationDescriptions->RestrictedShipmentSpecialServices;
		
		
		// not required
		$status 			= $body->ValidatePostalReply->Notifications->Severity;
		$source 			= $body->ValidatePostalReply->Notifications->Source;
		$locMessage 		= $body->ValidatePostalReply->Notifications->LocalizedMessage;
		
		$serviceId 			= $body->ValidatePostalReply->Version->ServiceId;
		$major 				= $body->ValidatePostalReply->Version->Major;
		$intermediate 		= $body->ValidatePostalReply->Version->Intermediate;
		$minor 				= $body->ValidatePostalReply->Version->Minor;
		
		/*
		/ OUTPUT
		*/
		echo '<style type="text/css">
				
				.table{background: #F9F9F9;border: #D2D2D2 solid 1px;border-collapse: collapse;font-size: 12px;word-break: break-all;overflow: auto;width: 100%;font-family: monospace;}
			 	.table th{background: #FFFFFF;border: #D2D2D2 solid 1px;font-size: 13px;}
				.table td{border-bottom: #D2D2D2 solid 1px;}
			 	.table tr td:first-child { color:#0054EC; }
			 </style>';
		echo '<div style="max-width: 850px;margin: 0 auto;">';		
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="2">FedEx Response : ValidatePostalRequest</th></tr>';

		echo '<tr><td>HighestSeverity</td><td>' . $requestStatus . '</td></tr>';
		echo '<tr><td>Code</td><td>' . $code . '</td></tr>';
		echo '<tr><td>Message</td><td>' . $message . '</td></tr>';
		echo '<tr><td>CustomerTransactionId</td><td>' . $cusTxnId . '</td></tr>';
		echo '<tr><td>CleanedPostalCode</td><td>' . $CleanedPostalCode . '</td></tr>';
		echo '<tr><td>LocationId</td><td>' . $LocationId . '</td></tr>';
		echo '<tr><td>LocationNumber</td><td>' . $LocationNumber . '</td></tr>';
		echo '<tr><td>CountryCode</td><td>' . $CountryCode . '</td></tr>';
		echo '<tr><td>StateOrProvinceCode</td><td>' . $StateOrProvinceCode . '</td></tr>';
		echo '<tr><td>PostalCode</td><td>' . $PostalCode . '</td></tr>';
		echo '<tr><td>ServiceArea</td><td>' . $ServiceArea . '</td></tr>';
		echo '<tr><td>AirportId</td><td>' . $AirportId . '</td></tr>';
		echo '<tr><td>RestrictedShipmentSpecialServices</td><td>' . $RestrictedShipmentSpecialServices . '</td></tr>';
		
		echo '<tr><td>Severity</td><td>' . $status . '</td></tr>';
		echo '<tr><td>Source</td><td>' . $source . '</td></tr>';
		echo '<tr><td>LocalizedMessage</td><td>' . $locMessage . '</td></tr>';
		echo '<tr><td>ServiceId</td><td>' . $serviceId . '</td></tr>';
		echo '<tr><td>Major</td><td>' . $major . '</td></tr>';
		echo '<tr><td>Intermediate</td><td>' . $intermediate . '</td></tr>';
		echo '<tr><td>Minor</td><td>' . $minor . '</td></tr>';
		echo '</table>';
		echo '</div>';
	}
}
curl_close($ch); 
?>
