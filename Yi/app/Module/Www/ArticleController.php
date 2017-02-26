<?php
namespace App\Module\Www;

use Min\App;

class ArticleController extends \Min\Controller
{
	public function onConstruct(){
		require CONF_PATH .'/keypairs.php';
	} 

	public function list_get()
	{	 
		$meta = ['menu_active' => 'article', 'title' =>'文字列表'];
		$region_id = intval($_GET['region']);
		
		$region_key =  ($region_id > 100000000) ? intval($region_id/1000) : $region_id;
		$key = 'regionChain_'. $region_key;
		$cache = $this->cache('region');
		
		if ($region_id > 0) {
			$region_list = $cache->get($key);
		}
		
		if (empty($region_list)) {
		
			$region_list = $cache->get('regionChain_0');
			
			if (empty($region_list)) {
				$region_list0 = $this->request('\\App\\Service\\Region::nodeChain', 0);
				foreach ($region_list0['body'] as $v) {
					$region_list[0][$v['id']] = $v;
				}
				$cache->set('regionChain_0', $region_list);
			}
			if ($region_id > 0) {
				
				$node_chain = $this->request('\\App\\Service\\Region::nodeChain', $region_key);
				if  (!empty($node_chain['body'])) {
					foreach ($node_chain['body'] as  $value) {
						$region_list[$value['parent_id']][$value['id']] = $value;
					}
					$cache->set($key, $region_list);
				}
			}
		}
		
		$region_chain = array_keys($region_list);

		if ($region_id > 0) {
			$end = end($region_chain);
			if (0 == $end) {
				$_GET['region']  = 0;
			} elseif ($region_id > 100000000) {
				if (empty($region_list[$end][$region_id])) {
					$_GET['region'] = 0;
					$region_chain 	= [0];
					$region_list 	= [0 => $region_list[0]];
				} else {
					$region_chain[] = $region_id;
				}
			}
		}
		
		$result = $this->request('\\App\\Service\\Article::list', $_GET);
		$result['body']['region_list'] 		= $region_list;
		$result['body']['params']['region'] = $region_chain;
		$result['body']['meta'] 			= $meta;
		
		$this->success($result['body']);
	}
	
	public function detail_get()
	{
		$id = short_int_convent(App::getArgs());
		
		if(!$id) {
			$this->error('参数错误', 1);
		}
		
		$result = $this->request('\\App\\Service\\Article::detail', $id);
	}
	
	public function edit_get()
	{
		$result = ['menu_active' => 'article_edit', 'title' =>'新增文字'];
		$this->response($result);
	}
	
	public function edit_post()
	{
		$param = [];
		$param['title'] = trim($_POST['title']);
		$param['desc'] 	= trim($_POST['desc']);
		$param['icon'] 	= $_POST['icon'];
		$param['content'] 	= $_POST['content'];
		
		if (!\validate('length', $param['title'], 32,1)) 	$this->error(1, '标题最多包含32个字符');
		if (!\validate('length', $param['desc'], 64,10)) 	$this->error(1, '简介最多包含64个字符');
		if (!\validate('img_url', $param['icon'], 128,20)) 	$this->error(1, '图像url不合法');
		if (!\validate('length', $param['content'], 60000,10)) $this->error(1, '文章内容长度超限制');
		 
		if (!empty($_POST['date_start']) && !\validate('date_Y-m-d', $_POST['date_start'])) {
			$this->error(1, '开始日期格式错误');
		}
		if (!empty($_POST['date_end']) && ! \validate('date_Y-m-d', $_POST['date_end'])) {
			$this->error(1, '结束日期格式错误');
		}
		
		$param['start'] 	= strtr($_POST['date_start'], ['-' =>'']);
		$param['end'] 		= strtr($_POST['date_end'], ['-' =>'']);
		$param['region'] 	= intval($_POST['region']);
		$param['tag'] 		= intval($_POST['tag']);
		$param['content'] 	= \Min\Xss::filter($_POST['content']);

		$this->response($this->request('\\App\\Service\\Article::add', $param));
	}
	
	 


}