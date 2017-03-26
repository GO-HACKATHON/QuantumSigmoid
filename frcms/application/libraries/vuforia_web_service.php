<?php

require_once APPPATH.'libraries/HTTP/Request2.php';
require_once APPPATH.'libraries/SignatureBuilder.php';

class vuforia_web_service
{
    protected $_ci;
    protected $vws_server = "https://vws.vuforia.com/";
    protected $req_path;
    protected $result;
    protected $headers = array();
    protected $request;
    // vuforia cloud database - server access key
    var $access_key = "6603a8a6b85e70e037f2ba921f6ac62fac16fcf6";
    var $secret_key = "c7ef4874b7246ae79b0200bb052f5a075ec694a1";
    protected $default_md5 = "d41d8cd98f00b204e9800998ecf8427e";

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function setReqPath($req_path) {
        $this->req_path = $req_path;
            //echo $this->req_path;
    }

    public function setTargetId($id)
    {
        $this->req_path.='/'.$id;
    }

    /*private function setHeaders($val)
    {
        array_push($this->headers, $val);
    }*/
    private function setHeaders()
    {
        $sb = 	new SignatureBuilder();
        $date = new DateTime("now", new DateTimeZone("GMT"));

        // Define the Date field using the proper GMT format
        $this->request->setHeader('Date', $date->format("D, d M Y H:i:s") . " GMT" );
        // Generate the Auth field value by concatenating the public server access key w/ the private query signature for this request
        $this->request->setHeader("Authorization" , "VWS " . $this->access_key . ":" . $sb->tmsSignature( $this->request , $this->secret_key ));
    }

    private function getDateNow()
    {
        $date = new DateTime("now", new DateTimeZone("GMT"));
        return $date->format("D, d M Y H:i:s")." GMT";
    }

    public function getAllTarget()
    {
        $this->request = new HTTP_Request2();
	$this->request->setMethod(HTTP_Request2::METHOD_GET);
        $this->request->setConfig(array('ssl_verify_peer' => false));
        $this->request->setURL( $this->vws_server . $this->req_path );
        $this->setHeaders();
        try
        {
            $response = $this->request->send();
            if (200 == $response->getStatus())
            {
                $this->result = $response->getBody();
            }
            else
            {
                $this->result = 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(). ' ' . $response->getBody();
            }
        }
        catch (HTTP_Request2_Exception $e)
        {
            $this->result = 'Error: ' . $e->getMessage();
        }
    }
    
    public function getTarget($target_id)
    {
        $this->request = new HTTP_Request2();
	$this->request->setMethod(HTTP_Request2::METHOD_GET);
        $this->request->setConfig(array('ssl_verify_peer' => false));
        $this->request->setURL( $this->vws_server . 'targets/'. $target_id );
        $this->setHeaders();
        try
        {
            $response = $this->request->send();
            if (200 == $response->getStatus())
            {
                $this->result = $response->getBody();
            }
            else
            {
                $this->result = 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(). ' ' . $response->getBody();
            }
        }
        catch (HTTP_Request2_Exception $e)
        {
            $this->result = 'Error: ' . $e->getMessage();
        }
    }
    
    public function addTarget($requestBody)
    {
        $jsonData = json_encode($requestBody);
        $credentials = $this->createSignature($this->req_path, "POST", "application/json", $jsonData);
        $this->setHeaders('Date: '.$this->getDateNow());
        $this->setHeaders('Authorization: VWS ' .$credentials);
        $this->setHeaders('Content-Type: application/json');
        $res = $this->postData($this->vws_server.$this->req_path, $jsonData);
        return $res;
        //echo print_r($jsonData);
        //echo $res;
    }

    public function updateTarget($requestBody)
    {
        $jsonData = json_encode($requestBody);
        $credentials = $this->createSignature($this->req_path, "PUT", "application/json", $jsonData);
        $this->setHeaders('Date: '.$this->getDateNow());
        $this->setHeaders('Authorization: VWS ' .$credentials);
        $this->setHeaders('Content-Type: application/json');
        $res = $this->putData($this->vws_server.$this->req_path, $jsonData);
        return $res;
        //echo print_r($jsonData);
        //echo $res;
    }
    
    public function deleteTarget()
    {
        $credentials = $this->createSignature($this->req_path, "DELETE");
        $this->setHeaders('Date: '.$this->getDateNow());
        $this->setHeaders('Authorization: VWS ' .$credentials);
        $res = $this->deleteData($this->vws_server.$this->req_path);
        return $res;
        //echo print_r($jsonData);
        //echo $res;
    }

    public function getFinalResult() {
            //explode("{"
        return $this->result;
    }
    
    public function postData($target, $data){
            $ch = curl_init();
            $param = array(
                    CURLOPT_URL => $target,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_HEADER => FALSE,
                    CURLOPT_VERBOSE => TRUE,
                    CURLOPT_HTTPHEADER => $this->headers
            );
            curl_setopt_array($ch, $param);
            $page = curl_exec($ch);
            //echo curl_errno($ch) . '-' .
    //curl_error($ch);
            //curl_close($ch);
            return $page;
    }

    public function putData($target, $data){
            $ch = curl_init();
            $param = array(
                    CURLOPT_URL => $target,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_HEADER => FALSE,
                    CURLOPT_VERBOSE => TRUE,
                    CURLOPT_HTTPHEADER => $this->headers
            );
            curl_setopt_array($ch, $param);
            $page = curl_exec($ch);
            //echo curl_errno($ch) . '-' .
    //curl_error($ch);
            //curl_close($ch);
            return $page;
    }

    public function getData($target){
            $ch = curl_init();
            $param = array(
                    CURLOPT_URL => $target,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_HEADER => FALSE,
                    CURLOPT_VERBOSE => TRUE,
                    CURLOPT_HTTPHEADER => $this->headers
            );
            curl_setopt_array($ch, $param);
            $page = curl_exec($ch);
            //echo curl_errno($ch) . '-' .
    //curl_error($ch);
            //curl_close($ch);
            return $page;
    }
    
    public function deleteData($target){
            $ch = curl_init();
            $param = array(
                    CURLOPT_URL => $target,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_FOLLOWLOCATION => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_HEADER => FALSE,
                    CURLOPT_VERBOSE => TRUE,
                    CURLOPT_HTTPHEADER => $this->headers
            );
            curl_setopt_array($ch, $param);
            $page = curl_exec($ch);
            //echo curl_errno($ch) . '-' .
    //curl_error($ch);
            //curl_close($ch);
            return $page;
    }

    public function hexToBase64($hex){
        $return = '';
        foreach(str_split($hex, 2) as $pair){
                $return .= chr(hexdec($pair));
        }
        return base64_encode($return);
    }

    private function createSignature($requestPath, $postType = "GET", $contentType = "", $content = "")
    {
        if($content!="") {
                $contentMD5 = md5($content);
        } else {
                $contentMD5 = $this->default_md5;
        }
        $date = new DateTime("now", new DateTimeZone("GMT"));
        $date = $date->format("D, d M Y H:i:s")." GMT";
        //echo "Date : ".$date.'<br />';
        $stringToSign = $postType . "\n" . $contentMD5 . "\n" . $contentType . "\n" . $date . "\n" . '/'.$requestPath;
        //echo "StringToSign : " . $stringToSign.'<br />';
        $signature = $this->hexToBase64(hash_hmac('sha1', $stringToSign, $this->secret_key));
        //$signature = hash_hmac('sha1', $stringToSign, "9305eff9529cc92dd9039305b451d3119ce8775f");
        //echo "Signature : ".$signature.'<br />';

        $credentials = $this->access_key . ":" . $signature;
        return $credentials;
    }
}
?>