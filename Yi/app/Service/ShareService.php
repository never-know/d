<?php
namespace App\Service;

use Min\App;

class ShareService extends \Min\Service
{
 
	public function check($name, $type = null) 
	{	
		if (validate('word', $name)) {
			$type = 'share_id';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
		 
		$cache 	= $this->cache('share');
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
 
			$sql = 'SELECT * FROM {share_record} WHERE '. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
		
			return $this->success($result);
			
		} else {
			 
			return $this->error('不存在', 30206);
		}
	}

	public function record($data) 
	{
		$check = $this->check($data['sid']);
		
		if (0 !== $check['statusCode'] || $check['body']['content_id'] != $data['id'] || $data['current_user'] == $check['body']['user_id']) {
		
			return $this->success();		// can not find share user then return directly
			$data['salary'] = $data['sid'] = $check['body']['user_id'] = 0;
		}
		
		$params 				= [];
		$params['viewer_id'] 	= intval($data['viewer_id']);
		$params['content_id'] 	= intval($data['id']);
		$params['share_user'] 	= intval($check['body']['user_id']);
		
		$sql_count = 'SELECT count(1) as count FROM {share_view} WHERE '. plain_build_query($params, ' AND '). ' LIMIT 1';
		$count = $this->query($sql_count);
		if ($count['count'] > 2) {
			return $this->success(['userid' => $params['share_user']]);
		}
		 
		$params['view_time'] 	= intval($data['view_time'])?:time();
		$params['salary'] 		= intval($data['salary']);
		$params['share_id'] 	= json_encode($data['sid']);
		
		
		$sql = 'INSERT INTO {share_view} (viewer_id, content_id, share_user, view_time, salary, share_id) values ('. implode(',', array_values($params)). ')';
		
		$result =  $this->query($sql);
		
		if ($result['id'] > 0) {
			return $this->success(['userid' => $params['share_user']]);
		} else {
			return $this->error('fail', 30204);
		}
	}
	
	public function login($params) 
	{
		$result = $this->checkAccount($params['name']);
		
		if (0 !== $result['statusCode']) {
			return $result;
		}
		
		if (password_verify($params['pwd'], $result['body']['pwd'])) {					 
			$this->initUser($result['body']);
			return $this->success();
		} else {
			return $this->error('帐号密码错误', 30201);
		}		
	}
	
	public function resetPwd($params) 
	{
		$result = $this->checkAccount($params['uid'], 'UID');
		
		if (0  !== $result['statusCode']) {
			return $result;
		}
		
		if (password_verify($params['pwd'], $result['body']['pwd'])) {					 
			$pwd = password_hash($params['newpwd'], PASSWORD_BCRYPT, ['cost' => 9]);
			$update = $this->query('update {user} set pwd ="'. $pwd.'" where uid = ' . $result['body']['uid']);
			if ($update) {
				return $this->success('修改成功');
			} else {
				return $this->error('修改失败', 30201);
			}
		} else {
			return $this->error('密码错误', 30201);
		}		
	}
	
	private function initUser($user)
	{ 
		if($user['uid'] > 0) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			//if (!empty($user['nick'])) setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('logined', 1);
			session_set('UID', $user['uid']);
			session_set('user', $user);
		}
	}
	
	private function getCacheKey($type, $value)
	{
		return '{account}:'.$type. ':'. $value;
	}
}