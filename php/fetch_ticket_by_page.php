<?php

// get dat from dataflow
$input_data = file_get_contents('php://input');

if($input_data == null){
  $str = array('code' => '-1', 'msg' => 'not data received');
  $str = json_encode($str);
  print_r($str);
} else{
  //transfer it to array
  $ar_data = json_decode($input_data, true);

  // check if "id" value is contain
  if(array_key_exists('page', $ar_data) == false || array_key_exists('per_page', $ar_data) == false ){
    $str = array('code' => '-1', 'msg' => 'received data lack page or per_page data');
    $str = json_encode($str);
    print_r($str);
  } else {
    // using curl to get all the tickets from the account.
    $curl = curl_init();
    $page = $ar_data['page'];
    $per_page = $ar_data['per_page'];

    // set up basic auth , 30 second timeout
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://bleve.zendesk.com/api/v2/organizations/360069165351/tickets.json?page=$page&per_page=$per_page",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "{\n\t\"user_id\": \"360069165351\"\n\t\n}",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Basic YXVpdGpvYnNlYXJjaEBnbWFpbC5jb206b3JjOGsxazQ=",
        "Cache-Control: no-cache",
        "Content-Type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // error handle
    if ($err) {
      // if the curl connection is fail return message and code -1
      $str = array('code' => '-1', 'msg' => "cURL Error #:" . $err);
      $str = json_encode($str);
      print_r($str);
    } else {
      $res = json_decode($response, true);
      // check if the api is wrong or unavailable, if wrong return code -1
      if(isset($res['error'])){
        $str = array('code' => '-1', 'msg' => $res['error']);
        $str = json_encode($str);
        print_r($str);
      }else{
        // success api calling response
        print_r($response);
      }
    }
  }

}


?>
