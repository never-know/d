<?php
namespace App\Module\M;

use Min\App;

class QrcodeController extends \App\Module\M\BaseController
{
	public function onConstruct($redirect = 2) 
	{
		parent::onConstruct(2);
	}
 
	/*
		generate qrcode in bottom of content and record view log;
	
	*/
	
	public function index_get()
	{
		$shared_user_wx_id	= session_get('wx_id')??0;
		$type = 'qrcode_avatar';
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if (preg_match('|^/content/([a-zA-Z0-9]+)/([a-zA-Z0-9_\-]+)\.html$|', $url['path'], $match)) {
				$params = [];
			
				if (!empty($match[1]) && !empty($match[2])   && validate('words', $match[2])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= session_get('wx_id');
					$params['current_user'] = session_get('USER_ID')??0;
					$params['content_id']   = \str2int($match[1]);
					$params['share_no']   	= $match[2];
					$params['lat']   		= ($_COOKIE['lat']??0)*10000000;
					$params['lng']   		= ($_COOKIE['lng']??0)*10000000;
					$params['ip']   		= ip_address();
					
					$result = $this->request('\\App\\Service\\ShareView::getShareUser', $params);
				 
					if ($result['body']['record'] == 1 && $result['body']['share_user'] > 1) {
						$sign = md5(md5(config_get('private_key') . ($_SERVER['REQUEST_TIME']*($_SERVER['REQUEST_TIME']%188))));
						min_socket(HOME_PAGE.'/cron/shareview.html?time='.$_SERVER['REQUEST_TIME'].'&sign=' .$sign);
					}
					$type = 'qrcode_logo';
					$shared_user_wx_id = intval($result['body']['share_user']??0);	// 分享者 用户ID
				}
			}
		} 
		
		$img = PUBLIC_PATH . config_get('wx_qrcode');
			 
		if (!empty($shared_user_wx_id)) {
			$img = $this->getQRCode($shared_user_wx_id, $type, $img);
		} 
		
		//redirect($img);
		header("Content-Type:image/jpeg"); 
		echo file_get_contents($img); 

		exit;
	}
	 
	public function getQRCode($shared_user_wx_id, $type = 'qrcode_avatar', $default = null)
	{
		$scene_id = base_convert($shared_user_wx_id, 10, 36);
		$cache 	= $this->cache('qrcode');
		$key	= '{qrcode}:'. $scene_id;
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
 
			$wx 	= $this->getWx();
			$result = $wx->getQRCode($scene_id);
			if (!empty($result['ticket'])) {
			
				$img = http_get($wx->getQRUrl($result['ticket']));
				
				if (!empty($img)) {
					
					$base = PUBLIC_PATH . '/qrcode/' . implode('/', str_split($scene_id, 2));

					$dir = dirname($base);
					if (!is_dir($dir)) {
						if (!mkdir($dir, 0755, true)) {
							return $default;
						}
					}
					
					$avatar_path = get_avatar($shared_user_wx_id, PUBLIC_PATH);
					
					$white_bg = imagecreatetruecolor(68, 68);
					$white_color = imagecolorallocate($white_bg, 96, 96, 96);
					imagefill($white_bg, 0, 0, $white_color);
				
					if (is_file($avatar_path)) {
						$new_image_avatar = imagecreatefromstring($img);
						$qrcode_avatar = imagecreatefromstring(file_get_contents($avatar_path));
						imagecopymerge($new_image_avatar, $white_bg, 181, 181, 0, 0, 68, 68, 100);
						imagecopymerge($new_image_avatar, $this->abc($qrcode_avatar), 183, 183, 0, 0, 64, 64, 100);
						$result['qrcode_avatar'] = $base . '1.png';
						imagepng($new_image_avatar, $result['qrcode_avatar']);
						imagedestroy($new_image_avatar);
						imagedestroy($qrcode_avatar);
					} 
					
					$new_image_logo = imagecreatefromstring($img);
					
					$result['qrcode_base'] = $base . '0.png';
					
					imagepng($new_image_logo, $result['qrcode_base']);
					
					$qrcode_logo = imagecreatefromstring(file_get_contents(PUBLIC_PATH. config_get('logo64')));
					imagecopymerge($new_image_logo, $this->abc($qrcode_logo), 183, 183, 0, 0, 64, 64, 100);
					$result['qrcode_logo'] = $base . '2.png';
					imagepng($new_image_logo, $result['qrcode_logo']);
					imagedestroy($new_image_logo);
					imagedestroy($qrcode_logo);
					
					imagedestroy($white_bg);
					
					$cache->set($key, $result, $result['expire_seconds']-100);
				}
			}
		}
		
		return ($result[$type]?:($result['qrcode_logo']?:$default));
	}
	
	public function get_lt_rounder_corner($radius) 
	{  
        $img     = imagecreatetruecolor($radius, $radius);  // 创建一个正方形的图像  
        $bgcolor    = imagecolorallocate($img, 255, 255, 255);   // 图像的背景  
        $fgcolor    = imagecolorallocate($img, 0, 0, 0);  
        imagefill($img, 0, 0, $bgcolor);  
        // $radius,$radius：以图像的右下角开始画弧  
        // $radius*2, $radius*2：已宽度、高度画弧  
        // 180, 270：指定了角度的起始和结束点  
        // fgcolor：指定颜色  
        imagefilledarc($img, $radius, $radius, $radius*2, $radius*2, 180, 270, $fgcolor, \IMG_ARC_PIE);  
        // 将弧角图片的颜色设置为透明  
        imagecolortransparent($img, $fgcolor);  
        // 变换角度  
        // $img = imagerotate($img, 90, 0);  
        // $img = imagerotate($img, 180, 0);  
        // $img = imagerotate($img, 270, 0);  
        // header('Content-Type: image/png');  
        // imagepng($img);  
        return $img;  
    }  
	
	public function abc($resource)
	{
		$radius  = 12;  
		// lt(左上角)  
		$lt_corner  = $this->get_lt_rounder_corner($radius);  
		imagecopymerge($resource, $lt_corner, 0, 0, 0, 0, $radius, $radius, 100);  
		// lb(左下角)  
		$lb_corner  = imagerotate($lt_corner, 90, 0);  
		imagecopymerge($resource, $lb_corner, 0, 64 - $radius, 0, 0, $radius, $radius, 100);  
		// rb(右上角)  
		$rb_corner  = imagerotate($lt_corner, 180, 0);  
		imagecopymerge($resource, $rb_corner, 64 - $radius, 64 - $radius, 0, 0, $radius, $radius, 100);  
		// rt(右下角)  
		$rt_corner  = imagerotate($lt_corner, 270, 0);  
		imagecopymerge($resource, $rt_corner, 64 - $radius, 0, 0, 0, $radius, $radius, 100);  
		imagedestroy($rt_corner);
		imagedestroy($rb_corner);
		imagedestroy($lb_corner);
		imagedestroy($lt_corner);
		return $resource;
	}
	
	public function ylb_number_limit_get()
	{
		$scene_id = 1;

		$wx 	= $this->getWx();
		$result = $wx->getQRCode($scene_id, 1);
		if (!empty($result['ticket'])) {
			
			$img = http_get($wx->getQRUrl($result['ticket']));
				
			if (!empty($img)) {
				
				$base = PUBLIC_PATH . '/qrcode/common/' . $scene_id . '.png';

				$dir = dirname($base);
				if (!is_dir($dir)) {
					if (!mkdir($dir, 0755, true)) {
						exit('error');
					}
				}
				
				$result = imagepng(imagecreatefromstring($img), $base);
				exit(($result?'ok':'error3'));
			}
		}
		
		exit('error2');
	}
	
}