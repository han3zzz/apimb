
<?php
 //ini_set('display_errors', 1);
include 'mb2023.php';
      ########## thông tin đăng nhập MBBANK ############

    $phonembbank = '037202003316'; // viết hoa toàn bộ dòng tài khoản
    $passmbbank = 'Lamhong2702'; 
    $stkmbbank = '999999878';  // Nhập sai stk cũng có thể bị lỗi
    
$thangbomaycheck = 'hanzzz.txt'; // file chứa SESSION LOGIN, bắt buộc bạn thay đổi tên thành file bất kỳ





    
  function generateRandom($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
date_default_timezone_set('Asia/Ho_Chi_Minh');
$time3 = date("YmdHis").'00';
$phienban = 'elerooyy'; //generateRandom(8);
$phienban = "$phienban-mbib-0000-0000-$time3";
$mbbank = new MBBANK();
 $mbbank->deviceIdCommon_goc = $phienban;
$deviceIdCommon_goc =   $mbbank->deviceIdCommon_goc;
$keycaptcha = 'v4uvn'; // để mặc định
$urlcaptcha = 'https://captcha.vsicloud.com'; // để mặc định
$imgpath = 'captcha.png'; // chỗ lưu ảnh captcha


    
    
    
    
    
    
    goto to9xvn_checkngay;
    
    ######## tiến hành lưu captcha và giải ########
    
    to9xvn:
    
$captcha = json_decode($mbbank->get_captcha(), true);
$to9xvn = 'data:image/png;base64,'.$captcha['imageString'];
//echo 'Ảnh <img src="'.$to9xvn.'"><br>';
// Function to write image into file
file_put_contents($imgpath, file_get_contents($to9xvn));

$urldecode = $urlcaptcha. '/in.php'; 
$curl = curl_init();
$fields = [
    'file' => curl_file_create('captcha.png'),
    'key' => $keycaptcha
];
// Set CURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $urldecode,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_VERBOSE => 1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $fields,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false
));
//create the multiple cURL handle
$mh = curl_multi_init();
//add the handle
curl_multi_add_handle($mh, $curl);
//execute the handle
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);
//close the handles
curl_multi_remove_handle($mh, $curl);
curl_multi_close($mh);
// all of our requests are done, we can now access the results
$ketquadecode = curl_multi_getcontent($curl);

//exit($ketquadecode);

$tachkq  = explode('|', $ketquadecode);
$idkq = $tachkq[1];
if($tachkq[0] == 'OK'){
    
}
//sleep(1);
LAYKETQUA:
$ketqua = file_get_contents($urlcaptcha.'/res.php?key='.$keycaptcha.'&action=get&id='.$idkq); // lấy kết quả

//exit($ketqua.$keycaptcha);


$tachkq2 = explode('|', $ketqua);
if($tachkq2[0] == "OK"){
   //exit($ketqua) ;
}else{
    
 goto LAYKETQUA;   
}
$to9xvn = $tachkq2[1];
//echo $to9xvn;

//exit;



########## giải xong captcah ta đi login thử ###################


    if (empty($phonembbank)) { 
        exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền tài khoản đăng nhập'))); 
    } 
    if (empty($passmbbank)) { 
        exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền mật khẩu'))); 
    } 
     if (empty($stkmbbank)) { 
        exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền số tài khoản'))); 
    } 
    $mbbank->user = $phonembbank; 
    $mbbank->pass = $passmbbank; 
    $time = time(); 
    $text_captcha = $to9xvn;
    $login = json_decode($mbbank->login($text_captcha),true);//responseCode 283 lỗi captcha, GW21 thông tin sai 
    $jsonlogin = json_encode($login);
    if($login['result']['message'] == "Capcha code is invalid") 
    { 
        exit(json_encode(array('status' => '1', 'msg' => 'Captcha không chính xác'))); 
    } 
    else if($login['result']['message'] == 'Customer is invalid') 
    { 
        exit(json_encode(array('status' => '1', 'msg' => 'Thông tin không chính xác'))); 
    } else {
        
        ######### lưu con mẹ nó session các thứ ###########
        $sessionIdc = $login['sessionId'];
        $deviceId = $login['cust']['softTokenList']['0']['deviceId'];;
        file_put_contents($thangbomaycheck, "$deviceIdCommon_goc|$sessionIdc");
       //echo "$jsonlogin"; exit;
      $to9xvn_total = 1;
    }
    
    
    
  ########## check lịch sử giao dịch ##########
  

  to9xvn_checkngay:
  
  $infocheck= file_get_contents($thangbomaycheck, true);
$infocheck = explode("|", $infocheck);
 $deviceId1 = $infocheck[0];
 $session_id = $infocheck[1];
// echo "So 1 $session_id va so 0 la $deviceId1 <br> ";exit;
 
$kqcheck =  $mbbank-> get_lsgd($phonembbank, $session_id,$deviceId1,$stkmbbank, 1); // 1 là 1 days ago
//exit($kqcheck);

$kqcheck1 = json_decode($kqcheck, true);
$trangthai = $kqcheck1['result']['message'];
// Chuyển chuỗi JSON thành mảng


//echo "KQ: $trangthai <br>";
if($trangthai != 'Success'){
    if($to9xvn_total == 1){
        echo $kqcheck1;
        
    }else{
        
  goto to9xvn;  
  exit;
    }
}

// echo '<style>';
// echo 'table { width: 100%; border-collapse: collapse; }';
// echo 'table, th, td { border: 1px solid black; }';
// echo 'th, td { text-align: center; padding: 8px; }';
// echo '</style>';
// echo '<table class="table">';
// echo '<tr><th>STT</th><th>Posting Date</th><th>Transaction Date</th><th>Account No</th><th>Credit Amount</th><th>Debit Amount</th><th>Currency</th><th>Description</th><th>Available Balance</th></tr>';

// foreach ($kqcheck1['transactionHistoryList'] as $index => $transaction) {
//     echo '<tr>';
//     echo '<td>' . ($index + 1) . '</td>';
//     echo '<td>' . $transaction['postingDate'] . '</td>';
//     echo '<td>' . $transaction['transactionDate'] . '</td>';
//     echo '<td>' . $transaction['accountNo'] . '</td>';
//     echo '<td>' . $transaction['creditAmount'] . '</td>';
//     echo '<td>' . $transaction['debitAmount'] . '</td>';
//     echo '<td>' . $transaction['currency'] . '</td>';
//     echo '<td>' . $transaction['description'] . '</td>';
//     echo '<td>' . $transaction['availableBalance'] . '</td>';
//     echo '</tr>';
// }

// echo '</table>';

exit($kqcheck);
  


    