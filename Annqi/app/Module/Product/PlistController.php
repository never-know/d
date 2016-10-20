<?php
namespace App\Module\Product;

use Min\App;

class PlistController{

	public function __construct($action){
		if( $action == 'category') {
			$this->category();
		}
	}
	
	private function category(){ 
	 
		 if(is_numeric(app::getargs())){
			$service	= new \min\service\product();
			$service->category(app::getargs());
		 }
	
	}
	
}