# orangesmsapi
send sms, get token, get status, get pourchases, get balance
orangesmsAPI


**INSTALLATION** 

<sub>composer require 3kjos/orangesmsapi</sub>

**USAGE**

	<sub> use Kjos\Sms</sub>

**Without token**

<sub>$config = ['client_id' => $client_id, 'client_secret' => $client_secret];</sub>
//$config = ['client_id' => $client_id, 'client_secret' => $client_secret, 'ressource_id' => $ressource_id, 'verify_peerSSL' => $verify_peerSSL];

$message = "Hello World!";

$sms = new OrangeSms($config); $sms->setRecipientPhoneNumber($recipientPhoneNumber); $sms->setSenderAddress($ourDevPhoneNumber); $sms->setMessage($message); $result = $sms->sendSms();

*With token generation $sms = new OrangeSms($config);

/* $sms->generateToken(); $token = $sms->getAccessToken(); $config = ['access_token' => $token]; */ $config = ['access_token' => "SOME TOKENS"];

$sms->setRecipientPhoneNumber($recipientPhoneNumber); $sms->setSenderAddress($ourDevPhoneNumber); $sms->setMessage($message); $result = $sms->sendSms();

GET POUSCHASE HISTORY $sms->getSmsPourchaseHistory();

GET SMS USAGE $sms->getSmsUsage();

GET SMS BALANCE $sms->getSmsBalance();

GET AUTHORIZATION HEADER $sms->generateAuthorizationHeader();

OTHERS $sms->setSenderName("Your Campany");

OPTION $sms->setAccept("application/json"); // default : application/json $sms->setAccept("application/x-www-form-urlencoded"); // default : application/x-www-form-urlencoded $sms->setGrantType("client_credentials"); // default : client_credentials
