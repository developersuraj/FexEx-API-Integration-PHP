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
$xml_file = "xml/createShipmentRequest.xml";

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

$xml->Body->ProcessShipmentRequest->WebAuthenticationDetail->UserCredential->Key 		= $UserCredential_Key;
$xml->Body->ProcessShipmentRequest->WebAuthenticationDetail->UserCredential->Password 	= $UserCredential_Password;
$xml->Body->ProcessShipmentRequest->ClientDetail->AccountNumber 						= $AccountNumber;
$xml->Body->ProcessShipmentRequest->ClientDetail->MeterNumber 							= $MeterNumber;
/* 
/ s/w inputs
*/
$CustomerTransactionId 	= 'Compnay_ship_2222';
$xml->Body->ProcessShipmentRequest->TransactionDetail->CustomerTransactionId 			= $CustomerTransactionId;

// sender details
date_default_timezone_set('UTC');
$ShipTimestamp 			= date('c', mktime(17, 0, 0, date('m'), date('d'), date('Y')));
$DropoffType 			= 'REGULAR_PICKUP';
$ServiceType 			= 'STANDARD_OVERNIGHT';
$PackagingType 			= 'YOUR_PACKAGING';

$xml->Body->ProcessShipmentRequest->RequestedShipment->ShipTimestamp 	= $ShipTimestamp;
$xml->Body->ProcessShipmentRequest->RequestedShipment->DropoffType 		= $DropoffType;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ServiceType 		= $ServiceType;
$xml->Body->ProcessShipmentRequest->RequestedShipment->PackagingType 	= $PackagingType;

$ShipperPersonName 			= 'Sender name';
$ShipperCompanyName 		= 'Sender company name';
$ShipperPhoneNumber 		= '9999999999'; 
$ShipperEMailAddress 		= 'example@domain.com';
$ShipperStreetLines 		= 'Streetlines';
$ShipperCity 				= 'City';// eg. Bangalore
$ShipperStateOrProvinceCode = 'State';// eg. KA
$ShipperPostalCode 			= '560001';
$ShipperCountryCode 		= 'Country';// IN

$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->AccountNumber 					= $AccountNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Contact->PersonName 			= $ShipperPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Contact->CompanyName 			= $ShipperCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Contact->PhoneNumber 			= $ShipperPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Contact->EMailAddress 			= $ShipperEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Address->StreetLines 			= $ShipperStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Address->City 					= $ShipperCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode 	= $ShipperStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Address->PostalCode 			= $ShipperPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Shipper->Address->CountryCode 			= $ShipperCountryCode;

// Recipient (Customer) details
$RecipientPersonName 			= 'Recipient name';
$RecipientCompanyName 			= 'Recipient company name';
$RecipientPhoneNumber 			= '9999999999'; 
$RecipientEMailAddress 			= 'example@domain.com';
$RecipientStreetLines 			= 'Streetlines';
$RecipientCity 					= 'City';// eg. Bangalore
$RecipientStateOrProvinceCode 	= 'State'; // eg. KA
$RecipientPostalCode 			= 560001;
$RecipientCountryCode 			= 'Country';// IN

$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Contact->PersonName 			= $RecipientPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Contact->CompanyName 			= $RecipientCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Contact->PhoneNumber 			= $RecipientPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Contact->EMailAddress 		= $RecipientEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Address->StreetLines 			= $RecipientStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Address->City 				= $RecipientCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode 	= $RecipientStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Address->PostalCode 			= $RecipientPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Recipient->Address->CountryCode 			= $RecipientCountryCode;

// Company (FedexAccountHolder) details
$OriginPersonName 			= 'Origin name';
$OriginCompanyName 			= 'Origin Company name';
$OriginPhoneNumber 			= '9999999999'; 
$OriginEMailAddress 		= 'example@domain.com';
$OriginStreetLines 			= 'Streetlines';
$OriginCity 				= 'City';// eg. Bangalore
$OriginStateOrProvinceCode 	= 'State'; // eg. KA
$OriginPostalCode 			= 560001;
$OriginCountryCode 			= 'Country';// IN

$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Contact->PersonName 				= $OriginPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Contact->CompanyName 			= $OriginCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Contact->PhoneNumber 			= $OriginPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Contact->EMailAddress 			= $OriginEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Address->StreetLines 			= $OriginStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Address->City 					= $OriginCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Address->StateOrProvinceCode 	= $OriginStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Address->PostalCode 				= $OriginPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->Origin->Address->CountryCode 			= $OriginCountryCode;


$ShippingChargesPaymentType = 'THIRD_PARTY'; // SENDER, THIRD_PARTY

$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->PaymentType 		= $ShippingChargesPaymentType;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->AccountNumber 			= $AccountNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Contact->PersonName 	= $OriginPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Contact->CompanyName 	= $OriginCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Contact->PhoneNumber 	= $OriginPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Contact->EMailAddress 	= $OriginEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Address->StreetLines 	= $OriginStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Address->City 			= $OriginCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Address->StateOrProvinceCode = $OriginStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Address->PostalCode 	= $OriginPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->ShippingChargesPayment->Payor->ResponsibleParty->Address->CountryCode 	= $OriginCountryCode;

$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->AccountNumber 			= $AccountNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Contact->PersonName 	= $OriginPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Contact->CompanyName 	= $OriginCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Contact->PhoneNumber 	= $OriginPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Contact->EMailAddress 	= $OriginEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Address->StreetLines 	= $OriginStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Address->City 			= $OriginCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Address->StateOrProvinceCode = $OriginStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Address->PostalCode 	= $OriginPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->ImporterOfRecord->Address->CountryCode 	= $OriginCountryCode;

$DutiesPaymentType = 'THIRD_PARTY'; // SENDER, THIRD_PARTY

$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->PaymentType = $DutiesPaymentType;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->AccountNumber = $AccountNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Contact->PersonName = $OriginPersonName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Contact->CompanyName = $OriginCompanyName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Contact->PhoneNumber = $OriginPhoneNumber;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Contact->EMailAddress = $OriginEMailAddress;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Address->StreetLines = $OriginStreetLines;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Address->City = $OriginCity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Address->StateOrProvinceCode = $OriginStateOrProvinceCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Address->PostalCode = $OriginPostalCode;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DutiesPayment->Payor->ResponsibleParty->Address->CountryCode = $OriginCountryCode;

$DocumentContent 			= 'NON_DOCUMENTS';
$CustomsValueCurrency 		= 'INR';
$CustomsValueAmount 		= 100;
$PackageCount 				= 1; // no. of packages
$Units 						= 'KG';// eg. KG, GM (Overall)
$Value 						= 10;// eg. 32 in KG (Overall)
$CustomerReferencesValue	= '12023942'; // pass your order id here

$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->DocumentContent 					= $DocumentContent;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->CustomsValue->Currency			= $CustomsValueCurrency;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->CustomsValue->Amount				= $CustomsValueAmount;
$xml->Body->ProcessShipmentRequest->RequestedShipment->PackageCount 											= $PackageCount;
$xml->Body->ProcessShipmentRequest->RequestedShipment->RequestedPackageLineItems->Weight->Units 				= $Units;
$xml->Body->ProcessShipmentRequest->RequestedShipment->RequestedPackageLineItems->Weight->Value 				= $Value;
$xml->Body->ProcessShipmentRequest->RequestedShipment->RequestedPackageLineItems->CustomerReferences->Value 	= $CustomerReferencesValue;


// Product info & invoice
$CommercialInvoicePurpose 		= 'SOLD';
$CommoditiesName 				= 'Product Name';
$NumberOfPieces 				= 1; // no. of pieces
$Description 					= 'About product.';
$CountryOfManufacture 			= 'IN';
$CommercialWeightUnits 			= 'KG'; 
$CommercialWeightValue 			= 1; // each product weight
$CommercialQuantity 			= 1; // total quantity
$QuantityUnits 					= 'EA'; // each product weight
$CommercialUnitPriceCurrency 	= 'INR'; // currency type
$CommercialUnitPriceAmount 		= 100; // each product price

$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->CommercialInvoice->Purpose		= $CommercialInvoicePurpose;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->CommercialInvoice->CustomerReferences->Value	= $CustomerReferencesValue;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->Name				= $CommoditiesName;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->NumberOfPieces		= $NumberOfPieces;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->Description			= $Description;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->CountryOfManufacture= $CountryOfManufacture;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->Weight->Units		= $CommercialWeightUnits;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->Weight->Value		= $CommercialWeightValue;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->Quantity			= $CommercialQuantity;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->QuantityUnits		= $QuantityUnits;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->UnitPrice->Currency	= $CommercialUnitPriceCurrency;
$xml->Body->ProcessShipmentRequest->RequestedShipment->CustomsClearanceDetail->Commodities->UnitPrice->Amount	= $CommercialUnitPriceAmount;


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
$data = str_replace('v11:', '', $data);
if(curl_errno($ch)) { 
	print "Error: " . curl_error($ch); 
} else { 
	
	$requestStatus = $status = $source = $code = $message = $locMessage = $cusTxnId = $serviceId = $major = $intermediate = $minor = '';	
	
	$UsDomestic = $CarrierCode = $ServiceTypeDescription = $PackagingDescription = $UrsaPrefixCode = $UrsaSuffixCode = $OriginLocationId = $OriginLocationNumber = $OriginServiceArea = $DestinationLocationId = $DestinationLocationNumber = $DestinationServiceArea = $DestinationLocationStateOrProvinceCode = $IneligibleForMoneyBackGuarantee = $AstraPlannedServiceLevel = $AstraDescription = $PostalCode = $StateOrProvinceCode = $CountryCode = $AirportId = $ServiceCode = $SequenceNumber = $TrackingIdType = $FormId = $TrackingNumber = $GroupNumber = '';
	
	$OperationalNumber = $OperationalContent = array();
	
	$BinaryBarcodesType = $BinaryBarcodesValue = $StringBarcodesType = $StringBarcodesValue = $LabelType = $ShippingDocumentDisposition = $Resolution = $CopiesToPrint = $DocumentPartSequenceNumber = $DocumentImage = $SignatureOption = '';
	
	$response = simplexml_load_string($data, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");	
	$soap = $response->xpath('//soapenv:Body');
	if(isset($soap[0])) {
		$body = $soap[0];
		
		// required
		$requestStatus 		= $body->ProcessShipmentReply->HighestSeverity;
		$code 				= $body->ProcessShipmentReply->Notifications->Code;
		$message 			= $body->ProcessShipmentReply->Notifications->Message;		
		$cusTxnId 			= $body->ProcessShipmentReply->TransactionDetail->CustomerTransactionId;
		
		// not required
		$status 			= $body->ProcessShipmentReply->Notifications->Severity;
		$source 			= $body->ProcessShipmentReply->Notifications->Source;
		$locMessage 		= $body->ProcessShipmentReply->Notifications->LocalizedMessage;
		
		$serviceId 			= $body->ProcessShipmentReply->Version->ServiceId;
		$major 				= $body->ProcessShipmentReply->Version->Major;
		$intermediate 		= $body->ProcessShipmentReply->Version->Intermediate;
		$minor 				= $body->ProcessShipmentReply->Version->Minor;
						
		//other required
		$UsDomestic 				= $body->ProcessShipmentReply->CompletedShipmentDetail->UsDomestic;
		$CarrierCode 				= $body->ProcessShipmentReply->CompletedShipmentDetail->CarrierCode;
		$ServiceTypeDescription 	= $body->ProcessShipmentReply->CompletedShipmentDetail->ServiceTypeDescription;
		$PackagingDescription 		= $body->ProcessShipmentReply->CompletedShipmentDetail->PackagingDescription;
		
		$UrsaPrefixCode 			= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->UrsaPrefixCode;
		$UrsaSuffixCode 			= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->UrsaSuffixCode;
		$OriginLocationId 			= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->OriginLocationId;
		$OriginLocationNumber 		= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->OriginLocationNumber;
		$OriginServiceArea 			= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->OriginServiceArea;
		$DestinationLocationId 		= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->DestinationLocationId;
		$DestinationLocationNumber 	= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->DestinationLocationNumber;
		$DestinationServiceArea 	= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->DestinationServiceArea;
		$DestinationLocationStateOrProvinceCode	= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->DestinationLocationStateOrProvinceCode;
		$IneligibleForMoneyBackGuarantee = $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->IneligibleForMoneyBackGuarantee;
		$AstraPlannedServiceLevel 	= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->AstraPlannedServiceLevel;
		$AstraDescription 			= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->AstraDescription;
		$PostalCode 				= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->PostalCode;
		$StateOrProvinceCode 		= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->StateOrProvinceCode;
		$CountryCode 				= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->CountryCode;
		$AirportId 					= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->AirportId;
		$ServiceCode 				= $body->ProcessShipmentReply->CompletedShipmentDetail->OperationalDetail->ServiceCode;
		
		$SequenceNumber 			= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->SequenceNumber;
		$TrackingIdType 			= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingIdType;
		$FormId 					= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->FormId;
		$TrackingNumber 			= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber;
		$GroupNumber 				= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->GroupNumber;
		
		foreach($body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->OperationalInstructions as $optVal) {
			
			array_push($OperationalNumber, $optVal->Number);
			array_push($OperationalContent, $optVal->Content);
		
		}
						
		$BinaryBarcodesType		= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->BinaryBarcodes->Type;
		$BinaryBarcodesValue	= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->BinaryBarcodes->Value;
		$StringBarcodesType		= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->StringBarcodes->Type;
		$StringBarcodesValue	= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->StringBarcodes->Value;
		
		$LabelType				= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->Type;
		$ShippingDocumentDisposition = $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->ShippingDocumentDisposition;
		$Resolution				= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->Resolution;
		$CopiesToPrint			= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->CopiesToPrint;
		$DocumentPartSequenceNumber	= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->DocumentPartSequenceNumber;
		$DocumentImage			= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image;
		$output_file = 'fedex-invoices/' . time() . '-FedExInvoice.pdf';
		$ifp = fopen($output_file, "wb"); 
		fwrite($ifp, base64_decode($DocumentImage)); 
		fclose($ifp);
		$SignatureOption		= $body->ProcessShipmentReply->CompletedShipmentDetail->CompletedPackageDetails->SignatureOption;
		
		/*
		/ OUTPUT
		*/
		echo '<style type="text/css">
				.table1{background: #FFFFFF;border: #666 solid 1px;border-collapse: collapse;font-family: monospace;}
			 	.table1 th, .table1 td{ width:25%; vertical-align:top; }
				.table{background: #F9F9F9;border: #666 solid 0px;border-collapse: collapse;font-size: 11px;word-break: break-all;overflow: auto;width: 100%;}
			 	.table th{background: #A2A2A2;color: white;font-size: 14px;}
				.table td{border-bottom: #D2D2D2 solid 1px;}
			 	.table tr td:first-child { color:#0054EC; }
				.invBtn {background: #E75D14;text-decoration: none;color: white;padding: 2px 8px;}
			 </style>';
		echo '<div style="max-width: 1360px;margin: 0 auto;">';	
		echo '<table cellpadding="5" cellspacing="0" border="1" class="table1">';
		echo '<tr><th colspan="4">FedEx Response : ProcessShipmentRequest</th></tr>';
		echo '<tr>';
		
		echo '<td>';
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="2">Part 1 - General</th></tr>';
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
		echo '<tr><th colspan="2">Part 2 - Shipment Detail</th></tr>';
		echo '<tr><td>UsDomestic</td><td>' . $UsDomestic . '</td></tr>';
		echo '<tr><td>CarrierCode</td><td>' . $CarrierCode . '</td></tr>';
		echo '<tr><td>ServiceTypeDescription</td><td>' . $ServiceTypeDescription . '</td></tr>';
		echo '<tr><td>PackagingDescription</td><td>' . $PackagingDescription . '</td></tr>';
		echo '<tr><td>SequenceNumber</td><td>' . $SequenceNumber . '</td></tr>';
		echo '<tr><td>TrackingIdType</td><td>' . $TrackingIdType . '</td></tr>';
		echo '<tr><td>FormId</td><td>' . $FormId . '</td></tr>';
		echo '<tr><td>TrackingNumber</td><td>' . $TrackingNumber . '</td></tr>';
		echo '<tr><td>GroupNumber</td><td>' . $GroupNumber . '</td></tr>';
		echo '</table>';
		echo '</td>';
		
		echo '<td>';
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="2">Part 3 - Operational Detail</th></tr>';
		echo '<tr><td>UrsaPrefixCode</td><td>' . $UrsaPrefixCode . '</td></tr>';
		echo '<tr><td>UrsaSuffixCode</td><td>' . $UrsaSuffixCode . '</td></tr>';
		echo '<tr><td>OriginLocationId</td><td>' . $OriginLocationId . '</td></tr>';
		echo '<tr><td>OriginLocationNumber</td><td>' . $OriginLocationNumber . '</td></tr>';
		echo '<tr><td>OriginServiceArea</td><td>' . $OriginServiceArea . '</td></tr>';
		echo '<tr><td>DestinationLocationId</td><td>' . $DestinationLocationId . '</td></tr>';
		echo '<tr><td>DestinationLocationNumber</td><td>' . $DestinationLocationNumber . '</td></tr>';
		echo '<tr><td>DestinationServiceArea</td><td>' . $DestinationServiceArea . '</td></tr>';
		echo '<tr><td>DestinationLocationStateOrProvinceCode</td><td>' . $DestinationLocationStateOrProvinceCode . '</td></tr>';
		echo '<tr><td>IneligibleForMoneyBackGuarantee</td><td>' . $IneligibleForMoneyBackGuarantee . '</td></tr>';
		echo '<tr><td>AstraPlannedServiceLevel</td><td>' . $AstraPlannedServiceLevel . '</td></tr>';
		echo '<tr><td>AstraDescription</td><td>' . $AstraDescription . '</td></tr>';
		echo '<tr><td>PostalCode</td><td>' . $PostalCode . '</td></tr>';
		echo '<tr><td>StateOrProvinceCode</td><td>' . $StateOrProvinceCode . '</td></tr>';
		echo '<tr><td>CountryCode</td><td>' . $CountryCode . '</td></tr>';
		echo '<tr><td>AirportId</td><td>' . $AirportId . '</td></tr>';
		echo '<tr><td>ServiceCode</td><td>' . $ServiceCode . '</td></tr>';
		echo '</table>';
		echo '</td>';
				
		echo '<td>';
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="2">Part 4 - Package Detail</th></tr>';
		echo '<tr><td>BinaryBarcodesType</td><td>' . $BinaryBarcodesType . '</td></tr>';
		echo '<tr><td>BinaryBarcodesValue</td><td>' . $BinaryBarcodesValue . '</td></tr>';
		echo '<tr><td>StringBarcodesType</td><td>' . $StringBarcodesType . '</td></tr>';
		echo '<tr><td>StringBarcodesValue</td><td>' . $StringBarcodesValue . '</td></tr>';
		echo '<tr><td>LabelType</td><td>' . $LabelType . '</td></tr>';
		echo '<tr><td>ShippingDocumentDisposition</td><td>' . $ShippingDocumentDisposition . '</td></tr>';
		echo '<tr><td>Resolution</td><td>' . $Resolution . '</td></tr>';
		echo '<tr><td>CopiesToPrint</td><td>' . $CopiesToPrint . '</td></tr>';
		echo '<tr><td>DocumentPartSequenceNumber</td><td>' . $DocumentPartSequenceNumber . '</td></tr>';
		echo '<tr><td>SignatureOption</td><td>' . $SignatureOption . '</td></tr>';
		echo '<tr><td>DocumentImage</td><td><a href="'.$output_file .'" target="_blank" class="invBtn">View Invoice</a></td></tr>';
		
		echo '</table>';
		echo '</td>';
		 		
		echo '<td>';
		echo '<table cellpadding="5" cellspacing="0" border="0" class="table">';
		echo '<tr><th colspan="2">OperationalInstructions</th></tr>';
		echo '<tr><th>Number</th><th>Content</th></tr>';
		if(!empty($OperationalNumber)) {
			foreach($OperationalNumber as $cKey => $cVal) {
				echo '<tr>
						<td>' . (isset($OperationalNumber[$cKey]) ? $OperationalNumber[$cKey] : '') . '</td>
						<td>' . (isset($OperationalContent[$cKey]) ? $OperationalContent[$cKey] : '') . '</td>
					</tr>';
			}
		}
		echo '</table>';
		echo '</td>';
		
		echo '</tr>';
		echo '</table>';
		echo '</div>';
	}
}
curl_close($ch); 
?>
