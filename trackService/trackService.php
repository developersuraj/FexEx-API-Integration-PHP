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
         
$xml_file = "xml/trackService.xml";

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

$xml->Body->TrackRequest->WebAuthenticationDetail->UserCredential->Key 			= $UserCredential_Key;
$xml->Body->TrackRequest->WebAuthenticationDetail->UserCredential->Password 	= $UserCredential_Password;
$xml->Body->TrackRequest->ClientDetail->AccountNumber 							= $AccountNumber;
$xml->Body->TrackRequest->ClientDetail->MeterNumber 							= $MeterNumber;
/* 
/ s/w inputs
*/
$CustomerTransactionId 	= 'Ground Track By Number';
$xml->Body->TrackRequest->TransactionDetail->CustomerTransactionId 	= $CustomerTransactionId;

$CarrierCode 			= 'FDXG';
$PackageIdentifierValue = '123456789';
$ProcessingOptions 		= 'INCLUDE_DETAILED_SCANS';

$xml->Body->TrackRequest->SelectionDetails->CarrierCode 			= $CarrierCode;
$xml->Body->TrackRequest->SelectionDetails->PackageIdentifier->Value = $PackageIdentifierValue;
$xml->Body->TrackRequest->ProcessingOptions 						= $ProcessingOptions;

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
	$TrackSeverity = $TrackSource = $TrackCode  = $TrackMessage = $TrackLocalizedMessage = $TrackingNumber = '';
	$Options = $Eligibility = array();
	$response = simplexml_load_string($data, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");	
	$soap = $response->xpath('//SOAP-ENV:Body');
	if(isset($soap[0])) {
		$body = $soap[0];
		
		// required
		$requestStatus 			= $body->TrackReply->HighestSeverity;
		$code 					= $body->TrackReply->Notifications->Code;
		$message 				= $body->TrackReply->Notifications->Message;		
		$cusTxnId 				= $body->TrackReply->TransactionDetail->CustomerTransactionId;
		$location 				= $body->TrackReply->Location;
		
		$TrackSeverity 			= $body->TrackReply->CompletedTrackDetails->TrackDetails->Notification->Severity;
		$TrackSource 			= $body->TrackReply->CompletedTrackDetails->TrackDetails->Notification->Source;
		$TrackCode 				= $body->TrackReply->CompletedTrackDetails->TrackDetails->Notification->Code;
		$TrackMessage 			= $body->TrackReply->CompletedTrackDetails->TrackDetails->Notification->Message;
		$TrackLocalizedMessage 	= $body->TrackReply->CompletedTrackDetails->TrackDetails->Notification->LocalizedMessage;
		$TrackingNumber 		= $body->TrackReply->CompletedTrackDetails->TrackDetails->TrackingNumber;
		
		foreach($body->TrackReply->CompletedTrackDetails->TrackDetails->DeliveryOptionEligibilityDetails as $optVal) {
			
			array_push($Options, $optVal->Option);
			array_push($Eligibility, $optVal->Eligibility);
		
		}
		
		// not required
		$status 			= $body->TrackReply->Notifications->Severity;
		$source 			= $body->TrackReply->Notifications->Source;
		$locMessage 		= $body->TrackReply->Notifications->LocalizedMessage;
		
		$serviceId 			= $body->TrackReply->Version->ServiceId;
		$major 				= $body->TrackReply->Version->Major;
		$intermediate 		= $body->TrackReply->Version->Intermediate;
		$minor 				= $body->TrackReply->Version->Minor;
		
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
		echo '<tr><th colspan="2">FedEx Response : TrackRequest</th></tr>';

		echo '<tr><td>HighestSeverity</td><td>' . $requestStatus . '</td></tr>';
		echo '<tr><td>Code</td><td>' . $code . '</td></tr>';
		echo '<tr><td>Message</td><td>' . $message . '</td></tr>';
		echo '<tr><td>CustomerTransactionId</td><td>' . $cusTxnId . '</td></tr>';
		echo '<tr><td>TrackSeverity</td><td>' . $TrackSeverity . '</td></tr>';
		echo '<tr><td>TrackSource</td><td>' . $TrackSource . '</td></tr>';
		echo '<tr><td>TrackCode</td><td>' . $TrackCode . '</td></tr>';
		echo '<tr><td>TrackMessage</td><td>' . $TrackMessage . '</td></tr>';
		echo '<tr><td>TrackLocalizedMessage</td><td>' . $TrackLocalizedMessage . '</td></tr>';
		echo '<tr><td>TrackingNumber</td><td>' . $TrackingNumber . '</td></tr>';
				
		echo '<tr><td>Severity</td><td>' . $status . '</td></tr>';
		echo '<tr><td>Source</td><td>' . $source . '</td></tr>';
		echo '<tr><td>LocalizedMessage</td><td>' . $locMessage . '</td></tr>';
		echo '<tr><td>ServiceId</td><td>' . $serviceId . '</td></tr>';
		echo '<tr><td>Major</td><td>' . $major . '</td></tr>';
		echo '<tr><td>Intermediate</td><td>' . $intermediate . '</td></tr>';
		echo '<tr><td>Minor</td><td>' . $minor . '</td></tr>';
		echo '</table>';
		echo '<p>&nbsp</p>';
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="3">Delivery Option Eligibility Details</th></tr>';
		echo '<tr><th>Option</th><th colspan="2">Eligibility</th></tr>';
		if(!empty($Options)) {
			foreach($Options as $cKey => $cVal) {
				echo '<tr>
						<td>' . $cVal . '</td>
						<td>' . (isset($Options[$cKey]) ? $Options[$cKey] : '') . '</td>
						<td>' . (isset($Eligibility[$cKey]) ? $Eligibility[$cKey] : '') . '</td>
					</tr>';
			}
		}
		echo '</table>';
		echo '</div>';
	}
}
curl_close($ch); 
?>
