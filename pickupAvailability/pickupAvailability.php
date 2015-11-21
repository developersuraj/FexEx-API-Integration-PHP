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
         
$xml_file = "xml/pickupAvailability.xml";

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

$xml->Body->PickupAvailabilityRequest->WebAuthenticationDetail->UserCredential->Key 		= $UserCredential_Key;
$xml->Body->PickupAvailabilityRequest->WebAuthenticationDetail->UserCredential->Password 	= $UserCredential_Password;
$xml->Body->PickupAvailabilityRequest->ClientDetail->AccountNumber 							= $AccountNumber;
$xml->Body->PickupAvailabilityRequest->ClientDetail->MeterNumber 							= $MeterNumber;
/* 
/ s/w inputs
*/
$CustomerTransactionId 	= 'Compnay_pickup_2222';
$xml->Body->PickupAvailabilityRequest->TransactionDetail->CustomerTransactionId 			= $CustomerTransactionId;

$StreetLines 			= 'Streetlines';
$City 					= 'City';// eg. Bangalore
$StateOrProvinceCode 	= 'State'; // eg. KA
$PostalCode 			= 560001;
$CountryCode 			= 'Country';// IN

$xml->Body->PickupAvailabilityRequest->PickupAddress->StreetLines 			= $StreetLines;
$xml->Body->PickupAvailabilityRequest->PickupAddress->City 					= $City;
$xml->Body->PickupAvailabilityRequest->PickupAddress->StateOrProvinceCode 	= $StateOrProvinceCode;
$xml->Body->PickupAvailabilityRequest->PickupAddress->PostalCode 			= $PostalCode;
$xml->Body->PickupAvailabilityRequest->PickupAddress->CountryCode 			= $CountryCode;

$PickupRequestType 		= 'FUTURE_DAY'; // SAME_DAY, FUTURE_DAY
$DispatchDate 			= date('Y-m-d', strtotime('+1 Day' . date('Y-m-d')));
$PackageReadyTime 		= date('H:i:s', strtotime('17:00:00'));
$CustomerCloseTime 		= date('H:i:s', strtotime('20:00:00'));
$Carriers 				= 'FDXE';// eg. FDXE, FDXG

$xml->Body->PickupAvailabilityRequest->PickupRequestType 						= $PickupRequestType;
$xml->Body->PickupAvailabilityRequest->DispatchDate 							= $DispatchDate;
$xml->Body->PickupAvailabilityRequest->PackageReadyTime							= $PackageReadyTime;
$xml->Body->PickupAvailabilityRequest->CustomerCloseTime						= $CustomerCloseTime;
$xml->Body->PickupAvailabilityRequest->Carriers									= $Carriers;

$DimensionsLength 		= 12;
$DimensionsWidth 		= 12;
$DimensionsHeight 		= 12;
$DimensionsUnits 		= 'IN';
$Units 					= 'KG';// eg. KG, GM
$Value 					= 10.1;// eg. 32 in KG

$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Dimensions->Length 		= $DimensionsLength;
$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Dimensions->Width 		= $DimensionsWidth;
$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Dimensions->Height 		= $DimensionsHeight;
$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Dimensions->Units 		= $DimensionsUnits;
$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Weight->Units 			= $Units;
$xml->Body->PickupAvailabilityRequest->ShipmentAttributes->Weight->Value 			= $Value;

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
	$carrier = $scheduleDay = $available = $pickupdate = $cutofftime = $accesstime = $resAvailable = $countryRel = array();
	
	$response = simplexml_load_string($data, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");	
	$soap = $response->xpath('//SOAP-ENV:Body');
	if(isset($soap[0])) {
		$body = $soap[0];
		
		// required
		$requestStatus 		= $body->PickupAvailabilityReply->HighestSeverity;
		$code 				= $body->PickupAvailabilityReply->Notifications->Code;
		$message 			= $body->PickupAvailabilityReply->Notifications->Message;		
		$cusTxnId 			= $body->PickupAvailabilityReply->TransactionDetail->CustomerTransactionId;
		$location 			= $body->PickupAvailabilityReply->Location;
		
		foreach($body->PickupAvailabilityReply->Options as $optVal) {
			
			array_push($carrier, $optVal->Carrier);
			array_push($scheduleDay, $optVal->ScheduleDay);
			array_push($available, $optVal->Available);
			array_push($pickupdate, $optVal->PickupDate);
			array_push($cutofftime, $optVal->CutOffTime);
			array_push($accesstime, $optVal->AccessTime);
			array_push($resAvailable, $optVal->ResidentialAvailable);
			array_push($countryRel, $optVal->CountryRelationship);
		
		}
		
		// not required
		$status 			= $body->PickupAvailabilityReply->Notifications->Severity;
		$source 			= $body->PickupAvailabilityReply->Notifications->Source;
		$locMessage 		= $body->PickupAvailabilityReply->Notifications->LocalizedMessage;
		
		$serviceId 			= $body->PickupAvailabilityReply->Version->ServiceId;
		$major 				= $body->PickupAvailabilityReply->Version->Major;
		$intermediate 		= $body->PickupAvailabilityReply->Version->Intermediate;
		$minor 				= $body->PickupAvailabilityReply->Version->Minor;
		
		echo '<div style="max-width: 850px;margin: 0 auto;">';		
		echo '<table cellpadding="5" cellspacing="0" border="1" style="background: #F9F9F9;border: #666 solid 1px;border-collapse: collapse;font-family: monospace;margin: 0 auto;">';
		echo '<tr><th colspan="2">FedEx Response : pickUpAvailability</th></tr>';
		echo '<tr><td>HighestSeverity</td><td>' . $requestStatus . '</td></tr>';
		echo '<tr><td>Code</td><td>' . $code . '</td></tr>';
		echo '<tr><td>Message</td><td>' . $message . '</td></tr>';
		echo '<tr><td>CustomerTransactionId</td><td>' . $cusTxnId . '</td></tr>';
		
		echo '<tr><td>Severity</td><td>' . $status . '</td></tr>';
		echo '<tr><td>Source</td><td>' . $source . '</td></tr>';
		echo '<tr><td>LocalizedMessage</td><td>' . $locMessage . '</td></tr>';
		echo '<tr><td>ServiceId</td><td>' . $serviceId . '</td></tr>';
		echo '<tr><td>Major</td><td>' . $major . '</td></tr>';
		echo '<tr><td>Intermediate</td><td>' . $intermediate . '</td></tr>';
		echo '<tr><td>Minor</td><td>' . $minor . '</td></tr>';
		echo '</table>';
		echo '<p>&nbsp</p>';
		echo '<table cellpadding="5" cellspacing="0" border="1" style="background: #F9F9F9;border: #666 solid 1px;border-collapse: collapse;font-family: monospace;margin: 0 auto;">';
		echo '<tr><th colspan="8">Available Pickups</th></tr>';
		echo '<tr><th>Carrier</th><th>ScheduleDay</th><th>Available</th><th>PickupDate</th><th>CutOffTime</th><th>AccessTime</th><th>ResidentialAvailable</th><th>CountryRelationship</th></tr>';
		if(!empty($carrier)) {
			foreach($carrier as $cKey => $cVal) {
				echo '<tr>
						<td>' . $cVal . '</td>
						<td>' . (isset($scheduleDay[$cKey]) ? $scheduleDay[$cKey] : '') . '</td>
						<td>' . (isset($available[$cKey]) ? $available[$cKey] : '') . '</td>
						<td>' . (isset($pickupdate[$cKey]) ? $pickupdate[$cKey] : '') . '</td>
						<td>' . (isset($cutofftime[$cKey]) ? $cutofftime[$cKey] : '') . '</td>
						<td>' . (isset($accesstime[$cKey]) ? $accesstime[$cKey] : '') . '</td>
						<td>' . (isset($resAvailable[$cKey]) ? $resAvailable[$cKey] : '') . '</td>
						<td>' . (isset($countryRel[$cKey]) ? $countryRel[$cKey] : '') . '</td>
					</tr>';
			}
		}
		echo '</table>';
		echo '</div>';
	}
}
curl_close($ch); 
?>
