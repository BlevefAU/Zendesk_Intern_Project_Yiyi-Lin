<?php

// get dat from dataflow
$input_data = file_get_contents('php://input');

// check if there is any data pass to php
if($input_data == null){
  $str = array('code' => '-1', 'msg' => 'not data received');
  $str = json_encode($str);
  print_r($str);
} else{

  //transfer it to array
  $ar_data = json_decode($input_data, true);

  // check if "id" value is contain
  if(array_key_exists('id', $ar_data) == false){
    $str = array('code' => '-1', 'msg' => 'not id value contain');
    $str = json_encode($str);
    print_r($str);
  }else{
    $id = $ar_data['id'];
    $url = "https://bleve.zendesk.com/api/v2/tickets/$id.json";
    // using curl to get all the tickets from the account.
    $curl = curl_init();
    // set up basic auth , 30 second timeout
    curl_setopt_array($curl, array(
      CURLOPT_URL => "$url",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Basic YXVpdGpvYnNlYXJjaEBnbWFpbC5jb206b3JjOGsxazQ=",
        "Cache-Control: no-cache",
        "Postman-Token: 8ca2126d-fafc-4f74-8dc1-a64741838255"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    // close curl
    curl_close($curl);

    // error handle
    if ($err) {
      // if curl connection fail
      $str = array('code' => '-1', 'msg' => "cURL Error #:" . $err);
      $str = json_encode($str);
      print_r($str);
    } else {
      // if api is wrong or unavailable
      $res = json_decode($response, true);
      if(isset($res['error'])){
        $str = array('code' => '-1', 'msg' => $res['error']);
        $str = json_encode($str);
        print_r($str);
      }else{
        // success require
        print_r($response);
      }
    }
  }
}



?>
