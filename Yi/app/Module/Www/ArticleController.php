<?php
namespace App\Module\Www;

use App\Traits\RegionControllerTrait;
use Min\App;

class ArticleController extends \App\Module\Www\BaseController
{	
	use RegionControllerTrait;
	
	public function onConstruct()
	{
		//require CONF_PATH .'/keypairs.php';
	} 

	public function list_get()
	{	 
		$meta = ['menu_active' => 'article_list', 'title' =>'文字列表'];
		$region_id = intval($_GET['region']??0);
		
		$region_list = $this->region_list($region_id);
		
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
			} elseif ($end != $region_id) { // 最后一级ID < 100000000
				$region_chain[] = $region_id;
			}
		}
		
		$result = $this->request('\\App\\Service\\Article::list', $_GET);
		$result['body']['region_list'] 		= $region_list;
		$result['body']['params']['region'] = $region_chain;
		$result['body']['meta'] 			= $meta;
		
		$this->response($result);
	}
	
	public function detail_get()
	{
		$id = \str2int(App::getArgs());
		
		if(!$id) {
			$this->error('参数错误', 1);
		}
		
		$result = $this->request('\\App\\Service\\Article::detail', $id, $this::EXITERROR);
		$result['body']['meta'] = ['menu_active' => 'article_list', 'title' => $result['body']['title'], 'description' => $result['body']['desc']];
		 
		$this->response($result);
	}
	
	public function preview_post()
	{
		$result = $this->processPostData();
		$result['meta'] = ['title' => $result['title'], 'description' => $result['desc']];
		 
		$this->success($result);
	}
	
	private function processPostData()
	{
		$param = [];
		$param['title'] 	= trim($_POST['title']);
		$param['desc'] 		= trim($_POST['desc']);
		$param['icon'] 		= $_POST['icon'];
		 
		if (!\validate('length', $param['title'], 32,1)) 	$this->error( '标题最多包含32个字符', 1);
		if (!\validate('length', $param['desc'], 64,10)) 	$this->error('简介最多包含64个字符', 1);
		if (!\validate('img_url', $param['icon'], 128,20)) 	$this->error('图像url不合法', 1);
		if (!\validate('length', $_POST['content'], 60000,10)) $this->error('文章内容长度超限制', 1);
		 
		if (!empty($_POST['date_start']) && !\validate('date_Y-m-d', $_POST['date_start'])) {
			$this->error('开始日期格式错误', 1);
		}
		if (!empty($_POST['date_end']) && ! \validate('date_Y-m-d', $_POST['date_end'])) {
			$this->error('结束日期格式错误', 1);
		}
		
		$param['start'] 	= strtr($_POST['date_start'], ['-' =>'']);
		$param['end'] 		= strtr($_POST['date_end'], ['-' =>'']);
		$param['region'] 	= intval($_POST['region']);
		
		// 判断 region Id 是否合法
		$region_list		= $this->region_list($param['region']);
		if (!(isset($region_list[$param['region']]) || ($end = end($region_list) && !empty($end[$param['region']])))) {
			$this->error('无效的地区ID', 1);
		}
		
		$param['tag'] 		= intval($_POST['tag']);
		$param['content'] 	= \Min\Xss::filter($_POST['content']);
		return $param;
	
	}
	
	public function add_get()
	{
		$result['params']['region'] 	= [0];
		$result['template_path'] 		= '/www/article/edit';
		$result['region_list'] 	= $this->region_list(0);
		$result['meta'] 		= ['menu_active' => 'article_add', 'title' =>'新增文字'];
		$result['detail'] 		= [
			'id'		=> 0,
			'title' 	=> '',
			'icon'  	=> '',
			'region' 	=> 0,
			'desc'  	=> '',
			'tag' 		=> 1, 
			'start' 	=> date('Y-m-d'),
			'end' 		=> '',
			'content' 	=> ''
		];

		$this->success($result);
	}
	
	public function edit_get()
	{
		$id = \str2int(App::getArgs());
		
		if(!$id) {
			$this->error('参数错误', 1);
		}
		
		$article = $this->request('\\App\\Service\\Article::detail', $id);
		$result['detail'] 				= $article['body'];
		$result['region_list'] 			= $this->region_list($article['body']['region']);
		$result['params']['region'] 	= array_keys($result['region_list']);
		
		// last level region need processed special
		if (0 != $article['body']['region']%1000) $result['params']['region'][] = $article['body']['region'];
		$result['meta'] 				= ['menu_active' => 'article_list', 'title' =>'编辑文字'];
		$this->success($result);
	}
	
	public function edit_post()
	{
		$param = $this->processPostData();

		if (!empty($_POST['id'])) $param['id'] = str2int($_POST['id']);

		$this->request('\\App\\Service\\Article::add', $param, $this::EXITALL);
	}
	
	public function test_get(){
		phpinfo();
	}	
}