# orangesmsapi
send sms, get token, get status, get pourchases, get balance
orangesmsAPI


**INSTALLATION** 

	composer require 3kjos/orangesmsapi

**USAGE**

	use Kjos\Sms

**Without token**

	$config = ['client_id' => $client_id, 'client_secret' => $client_secret];

**With token**
	
	$config = ['access_token' => "SOME TOKENS"];

**More options: ressource_id and, verify_peerSSL**
	
	$config = ['client_id' => $client_id, 'client_secret' => $client_secret, 'ressource_id' => $ressource_id, 'verify_peerSSL' => $verify_peerSSL];

	

**After Config, add this code:**
	
	$sms = new OrangeSms($config);
	
	$message = "Hello World!";

	$sms->setRecipientPhoneNumber($recipientPhoneNumber); 
	$sms->setSenderAddress($ourDevPhoneNumber); 
	$sms->setMessage($message); 
	$result = $sms->sendSms();



**GET POUSCHASE HISTORY** 

	$smsPourchaseHistory = $sms->getSmsPourchaseHistory();

**GET SMS USAGE **

	$smsUsage = $sms->getSmsUsage();

**GET SMS BALANCE **

	$smsBalance = $sms->getSmsBalance();

**GET AUTHORIZATION HEADER **

	$smsAuthorizationHeader = $sms->generateAuthorizationHeader();

**with campany name** 

	$senderName = $sms->setSenderName("Your Campany");

**OTHER OPTIONS** 

	$sms->setAccept("application/json"); // default : "application/json" 
	$sms->setAccept("application/x-www-form-urlencoded"); // default : "application/x-www-form-urlencoded"
	$sms->setGrantType("client_credentials"); // default : "client_credentials"
