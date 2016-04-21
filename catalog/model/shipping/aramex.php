<?php
class ModelShippingAramex extends Model {
	function getQuote($address) {
//            $this->getClientInfo();exit;
//            $rateAmount = $this->CalculateRate(); 
//            print_r($rateAmount);exit;
            $this->load->language('shipping/aramex');
            $error = '';
            $quote_data['aramex'] = array(
//                'AccountNumber' => '20016',
//                'AccountPin'  => '331421',
                'code'         => 'aramex.aramex',
                'title'        => 'Aramex Shipping Rate',
                'cost'         => $rateAmount->Value,
//				'tax_class_id' => $this->config->get('flat_tax_class_id'),
                'text'         => $rateAmount->Value.' '.$rateAmount->CurrencyCode
			);
            $method_data = array(
				'code'       => 'aramex',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
            return $method_data;
	}
        function getClientInfo() {
            return array(
                'AccountCountryCode'=> $this->config->get('aramex_countrycode'),
                'AccountEntity'=> $this->config->get('aramex_entity'),
                'AccountNumber'=> $this->config->get('aramex_account'),
                'AccountPin'=> $this->config->get('aramex_pin'),
                'UserName'=> $this->config->get('aramex_username'),
                'Password'=> $this->config->get('aramex_password'),
                'Version'=> $this->config->get('aramex_version')		
            );
        }
        /** Rates Calculation **/
        function CalculateRate() {
            $params = array(
                    'ClientInfo' => array(
                    'AccountCountryCode'	=> 'JO',
                    'AccountEntity'		 	=> 'AMM',
                    'AccountNumber'		 	=> '',
                    'AccountPin'		 	=> '',
                    'UserName'			 	=> 'reem@reem.com',
                    'Password'			 	=> '123456789',
                    'Version'			 	=> '1.0'		
                ),

            'OriginAddress' 	 	=> array(
                'City'					=> 'Amman',
                'CountryCode'				=> 'JO'
            ),

                'DestinationAddress' 	=> array(
                    'City'					=> 'Dubai',
                    'CountryCode'			=> 'AE'
                                                                ),
                'ShipmentDetails'		=> array(
                    'PaymentType'			 => 'P',
                    'ProductGroup'			 => 'EXP',
                    'ProductType'			 => 'PPX',
                    'ActualWeight' 			 => array('Value' => 5, 'Unit' => 'KG'),
                    'ChargeableWeight' 	     => array('Value' => 5, 'Unit' => 'KG'),
                    'NumberOfPieces'		 => 5
                                                                    )
            );

            $soapClient = new SoapClient('catalog/view/theme/cosyone/aramex/aramex-rates-calculator-wsdl.wsdl', array('trace' => 1));
            $results = $soapClient->CalculateRate($params);	
            print_r($results);exit;
            return $results->TotalAmount;
        }
        /** Location Services API **/
        function locationCountriesFetching() {
            $soapClient = new SoapClient('catalog/view/theme/cosyone/aramex/Location-API-WSDL.wsdl');
            $params = array(
		'ClientInfo' => array(
                    'AccountCountryCode'		=> 'JO',
                    'AccountEntity'		 	=> 'AMM',
                    'AccountNumber'		 	=> '20016',
                    'AccountPin'		 	=> '331421',
                    'UserName'			=> 'testingapi@aramex.com',
                    'Password'		 	=> 'R123456789$r',
                    'Version'		 	=> 'v1.0',
                    'Source' 			=> NULL			
                ),
		'Transaction' => array(
                    'Reference1'			=> '001',
                    'Reference2'			=> '002',
                    'Reference3'			=> '003',
                    'Reference4'			=> '004',
                    'Reference5'			=> '005'					 
                ),
            );
            // calling the method and printing results
            try {
                $auth_call = $soapClient->FetchCountries($params);

                echo '<pre>';
                print_r($auth_call);
                die();
            } catch (SoapFault $fault) {
                die('Error : ' . $fault->faultstring);
            }
        }
        /** Shipping Services **/
        function createShipment() {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            $soapClient = new SoapClient('catalog/view/theme/cosyone/aramex/shipping-services-api-wsdl.wsdl');
            $params = array(
                'ClientInfo' => $this->getClientInfo(),
                'Shipments' => array(
                    'Shipment' => array(
                        'Shipper'	=> array(
                            'Reference1' 	=> 'Ref 111111',
                            'Reference2' 	=> 'Ref 222222',
                            'AccountNumber' => '20016',
                            'PartyAddress'	=> array(
                                'Line1'=> 'Mecca St',
                                'Line2'=> '',
                                'Line3'=> '',
                                'City'=> 'Amman',
                                'StateOrProvinceCode'=> '',
                                'PostCode'=> '',
                                'CountryCode'=> 'Jo'
                            ),
                        'Contact'=> array(
                            'Department'=> '',
                            'PersonName'=> 'Michael',
                            'Title'=> '',
                            'CompanyName'=> 'Aramex',
                            'PhoneNumber1'=> '5555555',
                            'PhoneNumber1Ext'=> '125',
                            'PhoneNumber2'=> '',
                            'PhoneNumber2Ext'=> '',
                            'FaxNumber'=> '',
                            'CellPhone'=> '07777777',
                            'EmailAddress'=> 'michael@aramex.com',
                            'Type'=> ''
                        ),
                    ),
                    'Consignee'	=> array(
                        'Reference1'	=> 'Ref 333333',
                        'Reference2'	=> 'Ref 444444',
                        'AccountNumber' => '',
                        'PartyAddress'	=> array(
                                'Line1'					=> '15 ABC St',
                                'Line2'					=> '',
                                'Line3'					=> '',
                                'City'					=> 'Dubai',
                                'StateOrProvinceCode'	=> '',
                                'PostCode'				=> '',
                                'CountryCode'			=> 'AE'
                        ),
										
                        'Contact'		=> array(
                                'Department'			=> '',
                                'PersonName'			=> 'Mazen',
                                'Title'					=> '',
                                'CompanyName'			=> 'Aramex',
                                'PhoneNumber1'			=> '6666666',
                                'PhoneNumber1Ext'		=> '155',
                                'PhoneNumber2'			=> '',
                                'PhoneNumber2Ext'		=> '',
                                'FaxNumber'				=> '',
                                'CellPhone'				=> '',
                                'EmailAddress'			=> 'mazen@aramex.com',
                                'Type'					=> ''
                        ),
                    ),
						
                    'ThirdParty' => array(
                        'Reference1' 	=> '',
                        'Reference2' 	=> '',
                        'AccountNumber' => '',
                        'PartyAddress'	=> array(
                                'Line1'					=> '',
                                'Line2'					=> '',
                                'Line3'					=> '',
                                'City'					=> '',
                                'StateOrProvinceCode'	=> '',
                                'PostCode'				=> '',
                                'CountryCode'			=> ''
                        ),
                        'Contact'		=> array(
                                'Department'			=> '',
                                'PersonName'			=> '',
                                'Title'					=> '',
                                'CompanyName'			=> '',
                                'PhoneNumber1'			=> '',
                                'PhoneNumber1Ext'		=> '',
                                'PhoneNumber2'			=> '',
                                'PhoneNumber2Ext'		=> '',
                                'FaxNumber'				=> '',
                                'CellPhone'				=> '',
                                'EmailAddress'			=> '',
                                'Type'					=> ''							
                        ),
                    ),
						
                    'Reference1' 				=> 'Shpt 0001',
                    'Reference2' 				=> '',
                    'Reference3' 				=> '',
                    'ForeignHAWB'				=> 'ABC 000111',
                    'TransportType'				=> 0,
                    'ShippingDateTime' 			=> time(),
                    'DueDate'					=> time(),
                    'PickupLocation'			=> 'Reception',
                    'PickupGUID'				=> '',
                    'Comments'					=> 'Shpt 0001',
                    'AccountingInstrcutions' 	=> '',
                    'OperationsInstructions'	=> '',

                    'Details' => array(
                        'Dimensions' => array(
                                'Length'				=> 10,
                                'Width'					=> 10,
                                'Height'				=> 10,
                                'Unit'					=> 'cm',

                        ),
										
                    'ActualWeight' => array(
                            'Value'					=> 0.5,
                            'Unit'					=> 'Kg'
                    ),
										
                        'ProductGroup' 			=> 'EXP',
                        'ProductType'			=> 'PDX',
                        'PaymentType'			=> 'P',
                        'PaymentOptions' 		=> '',
                        'Services'				=> '',
                        'NumberOfPieces'		=> 1,
                        'DescriptionOfGoods' 	=> 'Docs',
                        'GoodsOriginCountry' 	=> 'Jo',
										
                    'CashOnDeliveryAmount'=> array(
                            'Value'=> 0,
                            'CurrencyCode'=> ''
                    ),

                    'InsuranceAmount'		=> array(
                            'Value'					=> 0,
                            'CurrencyCode'			=> ''
                    ),

                    'CollectAmount'			=> array(
                            'Value'					=> 0,
                            'CurrencyCode'			=> ''
                    ),

                    'CashAdditionalAmount'	=> array(
                            'Value'					=> 0,
                            'CurrencyCode'			=> ''							
                    ),

                    'CashAdditionalAmountDescription' => '',

                    'CustomsValueAmount' => array(
                            'Value'					=> 0,
                            'CurrencyCode'			=> ''								
                    ),

                    'Items' => array(

                    )
						),
				),
		),
		

                'Transaction' 			=> array(
                    'Reference1'			=> '',
                    'Reference2'			=> '', 
                    'Reference3'			=> '', 
                    'Reference4'			=> '', 
                    'Reference5'			=> '',									
                                                                ),
                'LabelInfo'				=> array(
                    'ReportID' 				=> 9201,
                    'ReportType'			=> 'URL',
                ),
	);
	
	$params['Shipments']['Shipment']['Details']['Items'][] = array(
		'PackageType' 	=> 'Box',
		'Quantity'		=> 1,
		'Weight'		=> array(
				'Value'		=> 0.5,
				'Unit'		=> 'Kg',		
		),
		'Comments'		=> 'Docs',
		'Reference'		=> ''
	);
	
//	print_r($params);
	
	try {
		$auth_call = $soapClient->CreateShipments($params);
		echo '<pre>';
		print_r($auth_call);
		die();
	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
        }
        
}
