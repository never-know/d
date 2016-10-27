<?php
namespace App\Module\Product;

use Min\App;

class DetailsController
{
	public function __construct($action){
		if( $action == 'item') {
			$this->item();
		}
	}
	
	private function item(){ 
	 
		 if(is_numeric(App::getargs())){
			$service	= new \App\Service\ProductService();
			$service->item(App::getargs());
		 }
	
	}

}