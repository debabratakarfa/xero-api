<?php

	
if ( isset($_REQUEST['wipe'])) {
  session_destroy();
  header("Location: {$here}?authenticate=1");

// already got some credentials stored?
} elseif(isset($_REQUEST['refresh'])) {
    $response = $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['oauth_session_handle']);
    if ($XeroOAuth->response['code'] == 200) {
        $session = persistSession($response);
        $oauthSession = retrieveSession();
    } else {
        outputError($XeroOAuth);
        if ($XeroOAuth->response['helper'] == "TokenExpired") $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['session_handle']);
    }

} elseif ( isset($oauthSession['oauth_token']) && isset($_REQUEST) ) {

    $XeroOAuth->config['access_token']  = $oauthSession['oauth_token'];
    $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];
    $XeroOAuth->config['session_handle'] = $oauthSession['oauth_session_handle'];

$response = $XeroOAuth->request('GET', $XeroOAuth->url('Organisation', 'core'), array('page' => 0));
                    if ($XeroOAuth->response['code'] == 200) {
                        $organisation = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                        echo "Organisation Name: " . $organisation->Organisations[0]->Organisation->Name."<br>";
                        echo "Organisation Legal Name: " . $organisation->Organisations[0]->Organisation->LegalName."<br>";
                        echo "Tax Number: " . $organisation->Organisations[0]->Organisation->TaxNumber."<br>";
                        echo "Organisation Status: " . $organisation->Organisations[0]->Organisation->OrganisationStatus."<br>";
                        echo "Timezone: " . $organisation->Organisations[0]->Organisation->Timezone."<br>";
                        echo "Country: " . $organisation->Organisations[0]->Organisation->CountryCode."<br>";
                        foreach ( $organisation->Organisations[0]->Organisation->Phones->Phone as $phonedetail )
                            {
                                if($phonedetail->PhoneNumber!=0){
                                    echo $phonedetail->PhoneType. " Number : [" .$phonedetail->PhoneCountryCode. "] - ".$phonedetail->PhoneNumber."<br>";
                                    }
                               }
                        } else {
                        outputError($XeroOAuth);
                    }
   

}
