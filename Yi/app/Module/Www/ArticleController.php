<?php
namespace App\Module\Www;

use Min\App;

class ArticleController extends \Min\Controller
{
	 

	public function index_get()
	{
		$result = ['menu_active' => 'article', 'title' =>'文字列表'];
		$this->response($result);
	}
	
	public function edit_get()
	{
		$result = ['menu_active' => 'article_edit', 'title' =>'新增文字'];
		$this->response($result);
	}
	
	public function edit_post()
	{
		$param['title'] = trim($_POST['title']);
		$param['desc'] 	= trim($_POST['desc']);
		$param['icon'] 	= $_POST['icon'];
		
		if (!\validate('length', $param['title'], 32)) 	$this->error(1, '标题最多包含32个字符');
		if (!\validate('length', $param['desc'], 64)) 	$this->error(1, '简介最多包含64个字符');
		if (!\validate('img_url', $param['icon'], 128)) $this->error(1, '图像url不合法');
		 
		if (!empty($_POST['date_start']) && !\validate('date_Y-m-d', $_POST['date_start'])) {
			$this->error(1, '开始日期格式错误');
		}
		if (!empty($_POST['date_end']) && ! \validate('date_Y-m-d', $_POST['date_end'])) {
			$this->error(1, '结束日期格式错误');
		}
		
		$param['start'] 	= strtr($_POST['date_start'], '-', '');
		$param['end'] 		= strtr($_POST['date_end'], '-', '');
		$param['region'] 	= intval($_POST['date_end']);
		$param['content'] 	= \Min\Xss::filter($_POST['content']);

		$this->response($this->request('\\App\\Service\\Article::add', $param));
	}
	
	 


}