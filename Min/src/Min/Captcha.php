<?php
namespace Min;

class Captcha{

	private $width  =120;
	private $height = 36;
	private $charset = 'ABCDEFGHJKLMNPQRTUVWXY';
	private $special = 'FLTVY';
	private $bigcode = 'W';
	private $seccode = 'MQUGNH';
	private $closecode = 'ALJ';
	private $opencode = 'JPTVY';
    private $fontsize = 18;       
    private $length = 4;         
    private $bg = [250, 250, 250];

	
	/**
	 * 获取随机数值
	 * @return string  返回转换后的字符串
	 */
	
	private function creatCode($key) 
	{
        $code = '';
        $charset_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $code .= $this->charset[mt_rand(0, $charset_len)];
        }
		$_SESSION[$key . '_code'] = strtolower($code);		
		return $code;		 
    }

	
	/**
	 * 获取验证码图片
	 * @return string  
	 */
	public function getCode($key) 
	{	
		header('Pragma: no-cache'); 
		Header('Content-type: image/PNG');  
		$this->width = $this->length * $this->fontsize + 16;
		$im = imagecreate($this->width, $this->height);       
        imagecolorallocate($im, $this->bg[0], $this->bg[1], $this->bg[2]); 
		
        // 验证码字体随机颜色   
        $_color = imagecolorallocate($im, mt_rand(1, 120), mt_rand(1, 120), mt_rand(1, 120));  
   
        $ttf = MIN_PATH.'/Min/Font/6.ttf';  

		$code = $this->creatCode($key);
	
        $codeNX = -2; // 验证码第N个字符的左边距 
		$codeNY = rand(23, 35);
		$arr = [0, 26];
		$oldny = $codeNY;
		$weight = 0.8;
		 
        for ($i = 0; $i < $this->length; $i++) {   
           
			$jiaodu = $arr[rand(0, 1)] - rand(10, 16);
			
			// 初始化默认宽度
			$weight = 0.8;
			if ($i>0) {
				if($code[$i - 1] == 'W'){
					$weight = 1;
				} elseif (strpos($this->seccode,$code[$i - 1]) !== false) {
					$weight = 0.9;
				}
			} 
			if (isset($code[$i-1]) && strpos($this->special,$code[$i - 1]) !== false) {
				$weight -= 0.1;
			}
			if (strpos($this->special,$code[$i]) !== false) {
				$weight -= 0.06;
			}
			if (isset($oldjiaodu) && $oldjiaodu * $jiaodu >= 0) {
				if ($oldny-$codeNY > 10 || $oldny-$codeNY < -10) {
					$weight -= 0.16;
				}
				if ($oldjiaodu-$jiaodu  >= 10 || $oldjiaodu-$jiaodu <= -10) {
					$weight += 0.1;
				} 
			
			}
			// 方向不同，张开式
			if (isset($oldjiaodu) && $oldjiaodu > 0 && $jiaodu < 0) {
			
				if ($oldny-$codeNY > 10 || $oldny-$codeNY <-10 ) {
					$weight -= 0.1;
				}
				
				if (isset($code[$i - 1]) && strpos($this->opencode,$code[$i - 1])!==false) {
					$weight -= 0.1;
				}
				if (strpos($this->opencode, $code[$i])!==false ){
					$weight -= 0.1;
				}
				if ($oldjiaodu-$jiaodu > 23){
					$weight -= 0.06;
				}
				
			}
			// 方向不同，闭合式
			if (isset($oldjiaodu) && $oldjiaodu < 0 && $jiaodu > 0) {
			
				if ($oldny-$codeNY < 10 || $oldny-$codeNY > -10) {
					$weight += 0.1;
				}
				if (isset($code[$i - 1]) && strpos($this->closecode,$code[$i - 1]) !== false) {
					$weight -= 0.1;
				}
				if (strpos($this->closecode,$code[$i]) !== false) {
					$weight -= 0.1;
				}
				if (isset($oldjiaodu) && $oldjiaodu < -13) {
					$weight += 0.05;
				}
				if ($jiaodu > 13) {
					$weight += 0.05;
				}	
			}
			
			$codeNX += $this->fontsize * $weight;
		
            imagettftext($im, $this->fontsize, $jiaodu, $codeNX, $codeNY, $_color, $ttf, $code[$i]);
			
			$oldny = $codeNY;
			$oldjiaodu = $jiaodu;
			
			 if ($codeNY > 28) {
				$codeNY = rand(23, 26);
			} else {
				$codeNY = rand(30, 35);
			}	
		}
 
		imagepng($im); 
		imagedestroy($im);
	}
	
	public function checkCode($code, $key) 
	{ 
		 
		return (!empty($_SESSION[$key.'_code']) && $_SESSION[$key.'_code'] === strtolower($code));
	}
	
}