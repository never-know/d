(function() {
 var region = [];

function diy_select(){
	this.init.apply(this,arguments)
};
diy_select.prototype={

	 init:function(opt) {
	 
		this.l=document.getElementById(opt.TTid).getElementsByTagName('ul');//容器
		this.lengths=this.l.length;
		var THAT = this;
		for (var i=0; i<this.lengths; i++) {
			this.l[i].index=i;
			this.l[i].style.display ='none';
		}
		Min.event.bind(opt.TTid,'mouseover',{handler:function(e){
			Min.css.addClass(opt.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'mouseout',{handler:function(e){
			Min.css.removeClass(opt.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'click',{handler:function(e){

			var index=this.parentNode.index;//获得列表
			var key = this.getAttribute("sid");
			var p = this.parentNode.parentNode;
			var origin = _$('region_selected').value;
			if (origin == key) return;
			
			if (key == 0) {
				 rkey = this.getAttribute("rid");
				if(rkey === null || rkey === undefined) return;
				_$('region_selected').value = rkey;
			} else {
				_$('region_selected').value = key;
			}

			p.getElementsByTagName('i')[0].innerHTML =this.innerHTML.replace(/^\s+/,'').replace(/\s+&/,'');
			this.parentNode.style.display='none';
			
			Min.obj.each(THAT.l,function(a,k){
				if(k > index){
					a.innerHTML = '';
					var li = document.createElement("li");
	    			 li.setAttribute("sid", 0);
					  var i = Min.dom.pre(a,'I');
					  li.innerHTML = i.innerHTML = '--不限--';
					  a.appendChild(li);  
				}
			});
			
			THAT.l[index].style.display ='none';
			
			if(key > 0 && index < THAT.lengths-1 && key < 100000000){
				if(!region[key]){
					Min.css.addClass('diy_select_list_loading', THAT.l[index+1]);
					JSONP.get( 'http://www.' + site_domain + '/region/id/'+key+'.html', {}, function(data){
						if(data.statusCode == 1){
							region[key]= data[key];
							Min.css.removeClass('diy_select_list_loading', THAT.l[index+1]);
							THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key)
							if(key > 0 &&  index < THAT.lengths-1 ){
								Min.obj.each(region[key], function(name, id){
								 var li = document.createElement("li");
					   		 li.setAttribute("sid", id);
							   li.innerHTML = name;
								  THAT.l[index+1].appendChild(li);
								});	
							}
						}
					}); 
					　
				} else {
					THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key);
					Min.obj.each(region[key], function(name, id){
					 	var li = document.createElement("li");
		   		 	li.setAttribute("sid", id);
				   	li.innerHTML = name;
						THAT.l[index+1].appendChild(li);
					});	
				}
				THAT.l[index+1].style.display ='block';
				
			} else {
				if(_$('search_form')) _$('search_form').submit(); 
			}

			Min.event.stopPropagation(e); 
		},selector:'li'});
		
		Min.event.bind(document,'click',function() {
			Min.obj.each(THAT.l,function(a){
				a.style.display='none';
			});
		});
		Min.event.bind(opt.TTid,'click',{handler:function(e){
			 
			var next = Min.dom.next(this);
			if(next.tagName.toUpperCase() != 'UL'){
				next = Min.dom.next(next);
			}
			next.style.display =  next.style.display == 'none'? 'block':'none';
			var index = next.index;
			
			Min.obj.each(THAT.l,function(a,key){
				if(key != index) a.style.display = 'none';
			});

			Min.event.stopPropagation(e); 	
			
		},selector:'i,span'});
	 }
}
new diy_select({ 
	TTid :'region',
	TTFcous:'focus'
});
})();