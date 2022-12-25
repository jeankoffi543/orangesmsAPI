<?php
//Src/MedcyBundle\OrangeSms.php
namespace Kjos\Sms;

class OrangeSms{
    
    private $verify_peerSSL;
    private $client_id;
    private $client_secret;
    private $access_token;
    private $content_type;
    private $accept;
    private $grant_type;
    private $message;
    private $senderAddress;
    private $senderName;
    private $recipientPhoneNumber;
    private $authorization_header;
    private $ressource_id;

    const BASE_URL = 'https://api.orange.com';


    //le constructeur
    public function __construct(?Array $config)
    {
        if(array_key_exists('client_id', $config)){
            $this->client_id = $config['client_id'];
        }

        if(array_key_exists('client_secret', $config)){
            $this->client_secret = $config['client_secret'];
        }

        if(array_key_exists('ressource_id', $config)){
            $this->ressource_id = $config['ressource_id'];
        }

        if(array_key_exists('access_token', $config)){
            $this->access_token = $config['access_token'];
        }

        if(array_key_exists('verify_peerSSL', $config)){
            $this->verify_peerSSL = $config['verify_peerSSL'];
        }else{
            $this->verify_peerSSL = true;
        }
    }

  //start setters and getters  
    public function getVerifyPeerSSL(): ?string
    {
        return $this->verify_peerSSL;
    }

    public function setVerifyPeerSSL(?string $verify_peerSSL): self
    {
        $this->verify_peerSSL = $verify_peerSSL;
        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setTAccessToken(?string $access_token): self
    {
        $this->access_token = $access_token;
        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function setClientId(?string $client_id): self
    {
        $this->client_id = $client_id;
        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->client_secret;
    }

    public function setClientSecret(?string $client_secret): self
    {
        $this->client_secret = $client_secret;
        return $this;
    }


    public function getGrantType(): ?string
    {
        return $this->grant_type;
    }

    public function setGrantType(?string $grant_type = "client_credentials"): self
    {
        $this->grant_type = $grant_type;
        return $this;
    }

    public function getAuthorizationHeader(): ?string
    {
        return $this->authorization_header;
    }

    public function setAuthorizationHeader(?string $authorization_header): self
    {
        $this->authorization_header = $authorization_header;
        return $this;
    }


    public function getContentType(): ?string
    {
        return $this->content_type;
    }

    public function setContentType(?string $content_type = "application/x-www-form-urlencoded"): self
    {
        $this->content_type = $content_type;
        return $this;
    }


    public function getAccept(): ?string
    {
        return $this->accept;
    }

    public function setAccept(?string $accept = "application/json"): self
    {
        $this->accept = $accept;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getSenderAddress(): ?string
    {
        return $this->senderAddress;
    }

    public function setSenderAddress(?string $senderAddress): self
    {
        $this->senderAddress = $senderAddress;
        return $this;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): self
    {
        $this->senderName = $senderName;
        return $this;
    }





    public function getRecipientPhoneNumber(): ?string
    {
        return $this->recipientPhoneNumber;
    }

    public function setRecipientPhoneNumber(?string $recipientPhoneNumber): self
    {
        $this->recipientPhoneNumber = $recipientPhoneNumber;
        return $this;
    }
    
    public function getRessourceId(): ?string
    {
        return $this->ressource_id;
    }

    public function setRessourceId(?string $ressource_id): self
    {
        $this->ressource_id = $ressource_id;
        return $this;
    }
    
 
    //end setters and getters  




    public function generateAuthorizationHeader()
    {
        $credentials = $this->getClientId() . ':' . $this->getClientSecret();
        $headers = 'Basic ' . base64_encode($credentials);

        $this->setAuthorizationHeader($headers);

    }

    public function generateToken()
    {
        $url = self::BASE_URL.'/oauth/v3/token';
        $this->setGrantType();
        $this->generateAuthorizationHeader();
        $this->setContentType();
        $this->setAccept();
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=".$this->getGrantType());

        $headers = array();
        $headers[] = 'Authorization: '.$this->getAuthorizationHeader();
        $headers[] = 'Content-Type: '.$this->getContentType();
        $headers[] = 'Accept: '.$this->getAccept();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $this->setTAccessToken($result);
    }

    public function sendSms():string
    {

        //generate the token
        //si le token n'existe pas on le crée
        if(null === $this->getAccessToken()){

            $this->generateToken();
            $token_json= $this->getAccessToken();
            $token_json_decode = json_decode($token_json);

            $token = $token_json_decode->{'access_token'};

        }else{
            $token = $this->getAccessToken();
        }

        $this->setContentType('application/json');

        //ressource id
        if(
            null !== $this->getRessourceId() && 
            $this->getRessourceId() != '' &&
            null !== $this->getSenderName() && 
            $this->getSenderName() != '' 
            ){

            $url = self::BASE_URL.'/smsmessaging/v1/outbound/tel+'.$this->getRecipientPhoneNumber().'/requests'.'/'.$this->getRessourceId();

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{
                \"outboundSMSMessageRequest\":{ \n
                    \"senderName\": \"".$this->getSenderName()."\",\n
                    \"senderAddress\":\"tel:+".$this->getSenderAddress()."\", \n
                    \"outboundSMSTextMessage\":{ \n
                        \"message\": \"".$this->getMessage()."\" \n
                    } \n    
                } \n
            }");

        }else{

            $url = self::BASE_URL.'/smsmessaging/v1/outbound/tel%3A%2B'.$this->getSenderAddress().'/requests';

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{
                \"outboundSMSMessageRequest\":{ \n
                    \"address\": \"tel:+".$this->getRecipientPhoneNumber()."\",\n
                    \"senderAddress\":\"tel:+".$this->getSenderAddress()."\", \n
                    \"outboundSMSTextMessage\":{ \n
                        \"message\": \"".$this->getMessage()."\" \n
                    } \n    
                } \n
            }");

        }

        $headers = array();
        $headers[] = 'Authorization: Bearer '.$token;
        $headers[] = 'Content-Type: '.$this->getContentType();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }


    public function getSmsBalance():string
    {

        //generate the token
        //si le token n'existe pas on le crée
        if(null === $this->getAccessToken()){

            $this->generateToken();
            $token_json= $this->getAccessToken();
            $token_json_decode = json_decode($token_json);

            $token = $token_json_decode->{'access_token'};

        }else{
            $token = $this->getAccessToken();
        }

        $url = self::BASE_URL.'/sms/admin/v1/contracts';

    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);


        return $result;
    }

    public function getSmsUsage():string
    {

        //generate the token
        //si le token n'existe pas on le crée
        if(null === $this->getAccessToken()){

            $this->generateToken();
            $token_json= $this->getAccessToken();
            $token_json_decode = json_decode($token_json);

            $token = $token_json_decode->{'access_token'};

        }else{
            $token = $this->getAccessToken();
        }

        $url = self::BASE_URL.'/sms/admin/v1/statistics';

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    public function getSmsPourchaseHistory():string
    {

        //generate the token
        //si le token n'existe pas on le crée
        if(null === $this->getAccessToken()){

            $this->generateToken();
            $token_json= $this->getAccessToken();
            $token_json_decode = json_decode($token_json);

            $token = $token_json_decode->{'access_token'};

        }else{
            $token = $this->getAccessToken();
        }

        $url = self::BASE_URL.'/sms/admin/v1/purchaseorders';

        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

}