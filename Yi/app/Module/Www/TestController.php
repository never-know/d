<?php
namespace App\Module\Www;

use Min\App;

class TestController extends \Min\Controller
{ 

	function b_get()
	{
		var_dump([] == false);
		
		var_dump(validate('nickname', '{'));
	
	}
	function from10_to62($num) {
		$to = 62;
		$dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ret = '';
		do {
			$ret = $dict[($num%$to)] . $ret;
			$num = intval($num/$to);
		} while ($num > 0);
		return $ret;
	}
	public function a_get()
	{
	
	
		 	//echo '100000: ',base_convert('100000',  36, 10), '<br>';
		 	//echo 'zzzzzz: ',base_convert('zzzzzz',  36, 10), '<br>';
		$a =$u = []; 
		$t = microtime(true)*10000;
		for($i=0;$i<60;$i++){
			$shareid =  shareid(700000000, 600000000, 2);
		  echo $shareid  , "<br>";
		  echo $this->from10_to62(base_convert(substr($shareid, 0, 12), 36, 10)).
			$this->from10_to62(base_convert(substr($shareid, 12), 36, 10));
			echo "<br>";
		  $_SERVER['REQUEST_TIME']++;
		  //echo strlen($r['id']),"<br>";
		  
		  /*
		 
		  if (isset($r['a'])) {
			$a[] = $r['a'];
		  }
		  
		  if (isset($r['u'])) {
			$u[] = $r['u'];
		  }
		 */
		   
		}
		
		echo microtime(true)*10000-$t;
		echo "<br>";
	 
		echo 'u:',count($u);
		//var_dump($u);
		echo "<br>";
		
		echo 'a:',count($a);
		//var_dump($a);
		echo "<br>";
		
		$t = microtime(true)*10000;
		for($i=0;$i<10000;$i++){
		
			uniqid();
		}
		
		echo microtime(true)*10000-$t;
		echo "<br>";
		$pairs		= [
			'a' => 	'f',
			'f' => 	'k',
			'k'	=>	'p',
			'p'	=>  'u',
			'u'	=>	'z',
			'0'	=>	'5',
			'9' => 	'a'
		];
		$t = microtime(true)*10000;
		for($i=0;$i<10000;$i++){
			
			$c =  strtr(base_convert('18357193201', 10, 36).  base_convert(18357193201, 10, 36).  base_convert(18357193201, 10, 36).  base_convert(18357193201, 10, 36), 'af', 'fa');
		}
		
		echo microtime(true)*10000-$t;
		echo "<br>";
		
		
		
		/*
			echo '1000: ',base_convert('10000',  10,36), '<br>';
			echo '1000: ',base_convert('100000',  10,36), '<br>';
			echo '1000: ',base_convert('1000000',  10,36), '<br>';
			echo '1000: ',base_convert('10000000',  10,36), '<br>';
			echo '1000: ',base_convert('100000000',  10,36), '<br>';
			echo '1000: ',base_convert('123424242',  10,36), '<br>';
			echo '1000: ',base_convert('1234242420',  10,36), '<br>';
			echo '1000: ',base_convert('12342424200',  10,36), '<br>';
			echo '1000: ',base_convert('12342424200',  10,36), '<br>';
		*/
	/*	
	
	echo shareid(1800, 18999, 1233,1), '<br>';
	echo shareid(1, 1,1233, 1), '<br>';
	echo shareid(1800, 18999, 1233, 1), '<br>';
	echo shareid(475689087, 189127654, 1259, 2), '<br>';
	*/
/*	
		echo '1000: ',base_convert('rs', 36, 10), '<br>';
		echo '1000: ',base_convert('-1000',  10,36), '<br>';
		
		echo '10000: ',base_convert('10000', 36, 10), '<br>';
		echo 'zzzzzz: ' ,base_convert('zzzzzz', 36, 10), "<br>";
		echo 'zzzzzz: ' ,date('Y-m-d H:i:s', base_convert('zzzzzz', 36, 10)), "<br>";
		 
		echo 'zzzzzz: ' ,  strtotime('2016-9-21') - base_convert('100000', 36, 10), "<br>";
		
		echo 'subtime: ' ,date('Y-m-d H:i:s',base_convert('zzzzzz', 36, 10)+strtotime('2016-9-21') - base_convert('100000', 36, 10)), "<br>";
	*/
	
	/*
	 echo 'zzzzzzz: ',base_convert('zzzzzzz', 36, 10), '<br>';
	 echo '1000000: ',base_convert('1000000', 36, 10), '<br>';
	 echo 'zzzzz: ',base_convert('100', 36, 10), '<br>';
	 echo '10000: ',base_convert('300', 36, 10), '<br>';		
	 echo '10000: ',base_convert('2000', 10, 36), '<br>';
	 echo '10000: ',base_convert('1295', 10, 36), '<br>';
	//	78364 2176
	// time  from 		1kw	  	mt_rand(1296, 46655);
	 echo 'time min: ',base_convert(1000000*1296, 10, 36), '<br>';
	 echo 'time max: ',base_convert(1000000*46655, 10, 36), '<br>';
	 echo 10000000*46655, '<br>';
	 echo base_convert('zzzzzzzz', 36, 10), '<br>';
	 echo "";echo "------------<br>";
	 echo base_convert('10000000', 36, 10)/26655, '<br>';
	 echo base_convert('zzzzzzzz', 36, 10)/3888, '<br>';
	
		$t = microtime(true)*10000;
		for($i=0;$i<100;$i++){
		
			//echo shareid(189678678, 2);echo "<br>";
		}
		
		echo microtime(true)*10000-$t;
		echo "<br>";
		//echo shareid(189678678, 2);
		
	*/	
	}
	
	
	function shareidtest_get()
	{
	
		echo "<style>div{display:block;float:left;width:135px;
		
		
		} .a{display:none;}.b{display:none;}.c{display:none;} .d{display:none;}</style>";
		$a = [];$f = 0; $s = 0; $t = 0;$k = 0;$m =0;
		for($salt = 37;  $salt <1296; $salt++){
		
			if ($salt < 88) {
				// $salt2 < $salt3	差值 12
				$salt2 = 108 - $salt;
				$salt3 = 120 - $salt;
				if ($f ==0) {
					echo "<div class='c'>";
					$f =1;
				}
				
			} elseif ($salt < 130){
				// $salt2 < $salt3 差值 13
				$salt2 = 183 - $salt;
				$salt3 = 196 - $salt;
				if ($s ==0) {
					echo "</div><div class='d'>";
					$s=1;
				}
				 
				//echo  $salt , '---',$salt2,'---',$salt3,'<br>';
			} else {
				//$salt2 > $salt3 差值 1-11
				$salt2  = ($salt%108)?:108;  //  最大 108
				$salt3  = ($salt%109)?:109;	//   最大 107
			 
				
				if ($m ==0 ) {
					echo "</div><div class='b'>";
					$m=1;$t=1;
				}
				
				if ($salt2 < 21  || $salt3 < 21 ) {
					if ($k ==0) {
						echo "</div><div class='a'>";
						$k =1;
						 
					}
				} else {
					if ($k ==1 ) {
						$t =0;
						$k = 0;
						echo "</div><div class='b'>";
					}
				}
				
				if ($salt2 < 21 ) {
					$salt2 = 59 - $salt2;
					
				}
				
				if ($salt3 < 21) {
					$salt3 = 79 - $salt3;
					 
				}
				
				 
				
			}
			echo  $salt , '-',$salt2,'-',$salt3,'<br>';
			$a[] = $salt2.'-'.$salt3;
			$b[] = max($salt2, $salt3).'-'.min($salt3, $salt2);
			
		}
		echo "</div>";
		echo count(array_count_values($a));
		echo "<br>";
		echo count(array_count_values($b));
		
		
		exit;
	
	}
	
	
	function abc_get()
	{
	
		for($i=80;$i<110;$i++){
		
		echo "$i: ", intval(78364164095/$i), '<br>';
		}
	}
}