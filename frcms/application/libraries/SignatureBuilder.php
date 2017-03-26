<?php

/**
 * Copyright (c) 2011-2013 Qualcomm Austria Research Center GmbH. All rights Reserved. Nothing in these materials is an offer to sell any of the components or devices referenced herein. Qualcomm is a trademark of QUALCOMM Incorporated, registered in the United States and other countries.Vuforia is a trademark of QUALCOMM Incorporated. Trademarks of QUALCOMM Incorporated are used with permission.
 * Vuforia SDK is a product of Qualcomm Austria Research Center GmbH. Vuforia Cloud Recognition Service is provided by Qualcomm Technologies, Inc..
 *
 * This Vuforia (TM) sample code provided in source code form (the "Sample Code") is made available to view for reference purposes only. 
 * If you would like to use the Sample Code in your web application, you must first download the Vuforia Software Development Kit and agree to the terms and conditions of the License Agreement for the Vuforia Software Development Kit, which may be found at https://developer.vuforia.com/legal/license. 
 * Any use of the Sample Code is subject in all respects to all of the terms and conditions of the License Agreement for the Vuforia Software Development Kit and the Vuforia Cloud Recognition Service Agreement. 
 * If you do not agree to all the terms and conditions of the License Agreement for the Vuforia Software Development Kit and the Vuforia Cloud Recognition Service Agreement, then you must not retain or in any manner use any of the Sample Code.
 * 
 */
 
class SignatureBuilder
{
	private $contentType = '';
	private $hexDigest = 'd41d8cd98f00b204e9800998ecf8427e'; // Hex digest of an empty string
	
	public function tmsSignature( $request , $secret_key ){
		
		$method = $request->getMethod();
		// The HTTP Header fields are used to authenticate the request
		$requestHeaders = $request->getHeaders();
		// note that header names are converted to lower case
		$dateValue = $requestHeaders['date'];
		
		$requestPath = $request->getURL()->getPath();
		
		// Not all requests will define a content-type
		if( isset( $requestHeaders['content-type'] ))
			$this->contentType = $requestHeaders['content-type'];
	
		if ( $method == 'GET' || $method == 'DELETE' ) {
			// Do nothing because the strings are already set correctly
		} else if ( $method == 'POST' || $method == 'PUT' ) {
			// If this is a POST or PUT the request should have a request body
			$this->hexDigest = md5( $request->getBody() , false );
			
		} else {
			print("ERROR: Invalid content type passed to Sig Builder");
		}
		
		$toDigest = $method . "\n" . $this->hexDigest . "\n" . $this->contentType . "\n" . $dateValue . "\n" . $requestPath ;

		//echo $toDigest;
		
		$shaHashed = "";
		
		try {
			// the SHA1 hash needs to be transformed from hexidecimal to Base64
			$shaHashed = $this->hexToBase64( hash_hmac("sha1", $toDigest , $secret_key) );
		} catch ( Exception $e) {
			$e->getMessage();
		}

		return $shaHashed;	
	}
	
	
	private function hexToBase64($hex){
	
		$return = "";
		
		foreach(str_split($hex, 2) as $pair){
	
			$return .= chr(hexdec($pair));
		}
		
		return base64_encode($return);
	}
}

?>