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
$xml_file = "xml/pickupRequest.xml";

/*
/ load xml object
*/
$xml= simplexml_load_file($xml_file);

/*
/ Fedex parameters
*/
$UserCredential_Key 		  = 'DEVELOPER_KEY';
$UserCredential_Password 	= 'DEVELOPER_PASSWORD';
$AccountNumber 				    = 'FEDEX_ACCOUNT_NUMBER';
$MeterNumber 				      = 'FEDEX_METER_NUMBER';

$xml->Body->CreatePickupRequest->WebAuthenticationDetail->UserCredential->Key 		  = $UserCredential_Key;
$xml->Body->CreatePickupRequest->WebAuthenticationDetail->UserCredential->Password 	= $UserCredential_Password;
$xml->Body->CreatePickupRequest->ClientDetail->AccountNumber 						            = $AccountNumber;
$xml->Body->CreatePickupRequest->ClientDetail->MeterNumber 							            = $MeterNumber;
/* 
/ s/w inputs
*/
$CustomerTransactionId 	= 'Company_pickup_2222';
$UseAccountAddress 		  = 'false';

$xml->Body->CreatePickupRequest->TransactionDetail->CustomerTransactionId 			= $CustomerTransactionId;
$xml->Body->CreatePickupRequest->OriginDetail->UseAccountAddress 					      = $UseAccountAddress;

$PersonName 			    = 'Customer name';
$CompanyName 			    = 'Company name';
$PhoneNumber 			    = '9595959595';
$EMailAddress 			  = 'example@domail.com';
$StreetLines 			    = 'Address';
$City 					      = 'City'; //eg. Bangalore
$StateOrProvinceCode 	= 'State'; // eg. KA
$PostalCode 			    = 560001;
$CountryCode 			    = 'Country'; // eg. IN

$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Contact->PersonName 	= $PersonName;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Contact->CompanyName = $CompanyName;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Contact->PhoneNumber = $PhoneNumber;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Contact->EMailAddress = $EMailAddress;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Address->StreetLines = $StreetLines;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Address->City 		= $City;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Address->StateOrProvinceCode = $StateOrProvinceCode;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Address->PostalCode 	= $PostalCode;
$xml->Body->CreatePickupRequest->OriginDetail->PickupLocation->Address->CountryCode = $CountryCode;

date_default_timezone_set('UTC');
$ReadyTimestamp 		  = date('c', mktime(17, 0, 0, date('m'), date('d'), date('Y')));
$CompanyCloseTime 		= date('H:i:s', strtotime('20:00:00'));
$PackageCount 			  = 1; // no. of packages
$Units 					      = 'KG';// eg. KG, GM
$Value 					      = 10;// eg. 32 in KG
$Remarks 				      = 'This is a test API call. Please do not arrange for pick up. Only test pickup request';

$xml->Body->CreatePickupRequest->OriginDetail->ReadyTimestamp 						= $ReadyTimestamp;
$xml->Body->CreatePickupRequest->OriginDetail->CompanyCloseTime 					= $CompanyCloseTime;
$xml->Body->CreatePickupRequest->PackageCount 										        = $PackageCount;
$xml->Body->CreatePickupRequest->TotalWeight->Units 								      = $Units;
$xml->Body->CreatePickupRequest->TotalWeight->Value 								      = $Value;
$xml->Body->CreatePickupRequest->Remarks 											            = $Remarks;

file_put_contents($xml_file, $xml->asXML());
		 
// Read the XML to send to the Web Service 
$xml= simplexml_load_file($xml_file);
$fh = fopen($xml_file, 'r'); 
$xml_data = fread($fh, filesize($xml_file)); 
fclose($fh); 

// replace with live url		
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
	
	$requestStatus = $status = $source = $code = $message = $locMessage = $cusTxnId = $serviceId = $major = $intermediate = $minor = $pickUpConfmNo = $location = '';	
	$response = simplexml_load_string($data, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");	
	$soap = $response->xpath('//SOAP-ENV:Body');
	if(isset($soap[0])) {
		$body = $soap[0];
		
		// required
		$requestStatus 		  = $body->CreatePickupReply->HighestSeverity;
		$code 				      = $body->CreatePickupReply->Notifications->Code;
		$message 			      = $body->CreatePickupReply->Notifications->Message;		
		$cusTxnId 			    = $body->CreatePickupReply->TransactionDetail->CustomerTransactionId;
		$pickUpConfmNo 		  = $body->CreatePickupReply->PickupConfirmationNumber;
		$location 			    = $body->CreatePickupReply->Location;
		$status 			      = $body->CreatePickupReply->Notifications->Severity;
		$source 			      = $body->CreatePickupReply->Notifications->Source;
		$locMessage 		    = $body->CreatePickupReply->Notifications->LocalizedMessage;
		$serviceId 			    = $body->CreatePickupReply->Version->ServiceId;
		$major 				      = $body->CreatePickupReply->Version->Major;
		$intermediate 		  = $body->CreatePickupReply->Version->Intermediate;
		$minor 				      = $body->CreatePickupReply->Version->Minor;
		
		echo '<div style="max-width: 850px;margin: 0 auto;">';
		echo '<table cellpadding="5" cellspacing="0" border="1" style="background: #F9F9F9;border: #666 solid 1px;border-collapse: collapse;font-family: monospace;margin: 0 auto;">';
		echo '<tr><th colspan="2">FedEx Response : CreatePickUpRequest</th></tr>';
		echo '<tr><td>HighestSeverity</td><td>' . $requestStatus . '</td></tr>';
		echo '<tr><td>Code</td><td>' . $code . '</td></tr>';
		echo '<tr><td>Message</td><td>' . $message . '</td></tr>';
		echo '<tr><td>CustomerTransactionId</td><td>' . $cusTxnId . '</td></tr>';
		echo '<tr><td>PickupConfirmationNumber</td><td>' . $pickUpConfmNo . '</td></tr>';
		echo '<tr><td>Location</td><td>' . $location . '</td></tr>';
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
