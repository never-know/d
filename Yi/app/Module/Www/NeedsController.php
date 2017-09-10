<?php
namespace App\Module\Www;

use App\Traits\RegionTrait;
use Min\App;

class NeedsController extends \App\Module\Www\BaseController
{	

	public function list_get()
	{	 
		$meta = ['menu_active' => 'needs_list', 'title' =>'需求列表'];
		 
		
		$result = $this->request('\\App\\Service\\Needs::list', $_GET);
 
		$result['body']['meta'] 			= $meta;
		
		$this->response($result);
	}
	
	public function detail_get()
	{
		$id = \str2int(App::getArgs());
		
		if(!$id) {
			$this->error('参数错误', 20108);
		}
		
		$result = $this->request('\\App\\Service\\Needs::detail', $id, $this::EXITERROR);
		$result['body']['meta'] = ['menu_active' => 'needs_list', 'title' => $result['body']['title'], 'description' => $result['body']['desc']];
		 
		$this->response($result);
	}
	
	 
	private function processPostData()
	{
	
		$param = [];
		$param['title'] 	= trim($_POST['title']);
		$param['desc'] 		= trim($_POST['desc']);
 
		if (!\validate('length', $param['title'], 50,1)) 	$this->error( '标题最多包含50个字符', 20108);
		if (!\validate('length', $param['desc'], 600,1)) 	$this->error('简介最多包含600个字符', 20108);
		return $param;
	
	}
	
	public function add_get()
	{
		$result['template'] 	= '/www/needs/edit';
		$result['meta'] 		= ['menu_active' => 'needs_add', 'title' =>'新增需求'];
		$result['detail'] 		= [
			'needs_id'		=> 0,
			'title' 	=> '',
			'desc'  	=> '',
		 
		];

		$this->success($result);
	}
	
	public function edit_get()
	{
		$id = App::getArgs();
		
		if(!$id) {
			$this->error('参数错误', 20108);
		}
		
		$article = $this->request('\\App\\Service\\Needs::detail', $id);
		$result['detail'] 				= $article['body'];
		$result['meta'] 				= ['menu_active' => 'needs_list', 'title' =>'编辑需求'];
		$this->success($result);
	}
	
	public function edit_post()
	{
		$param = $this->processPostData();

		if (!empty($_POST['id'])) $param['id'] = str2int($_POST['id']);

		$this->request('\\App\\Service\\Needs::add', $param, $this::EXITALL);
	}
	
}