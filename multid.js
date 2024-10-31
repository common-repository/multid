// JavaScript Document
function $O(objname) { return document.getElementById(objname); } 
function $$(obj,subObj) { if (!obj) return; return  obj.elements[subObj];  } 
function clone(obj) { if (null == obj || "object" != typeof obj) return obj; var copy = obj.constructor(); for (var attr in obj) { if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr]; } return copy; }
function getAjaxparam(obj,Target,isObject) { 
 var Result=(Target)?Target:{}; 
 if (!isObject) { var form=document.getElementById(obj) } else { form=obj; }; 
	var isarray=false;
		jQuery(function($) {
    		 $(form).find(':input').each(function(index, element) {
				 isarray=false;
				 if ((element.id)&&(element.disabled==false)) {
				 var Val=null;
					 if (element.className&&(element.className.indexOf('autoNumeric')>-1)) {
						 Val=$(element).autoNumeric('get');  
					 } else 
					    if ((['checkbox','radio'].indexOf(element.type)>-1)) { 
						   if (element.checked) Val=element.value;
						   isarray=true;
					     } else { Val=element.value; console.log(element.id);}
						 
					 if (Val) if (!isarray) { Result[element.id]=Val; } else { 
					 	if (Result[element.id]) { Result[element.id].push(Val);} else {Result[element.id]=[Val];};
					 }
				 }
    		});;
			
		}); // jquery
 	 
return Result; 
}

function MultiD_SaveSettings(Btn) {
	if (Btn) { Btn.disabled=true; Btn.value='Saving settings...';}
var data=getAjaxparam('multid_settings');
jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
				  if (Btn) { Btn.value='Successfull';}
				  MultiD_LoadSettings(); 
				 
				},
				error: function(errorThrown){
				    console.log(errorThrown);
					
				}
				
			});		
	
}

function MultiD_LoadDictionary_Add(Btn) {
	var name;
    do {
        name=prompt("Please enter you new keyword.");
    }
    while(name.length < 1);
    jQuery('#myinput').val(name);
	
	if (Btn) { Btn.disabled=true; Btn.value='Saving new keyword...';}
			var data={action:'MultiD_LoadDictionary_Add'};
				data['id']=name;
				
				
				jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
					if (data) 
						if (data.indexOf('error')<0) {
							jQuery('#searchname').val(data); 
							MultiD_LoadDictionary(); 
							}
						else { 
						  if (data.indexOf('Duplicate')<0) { alert(data); } else { alert('Keyword already exists.');
						  
						  }
						}
						if (Btn) { Btn.disabled=false; Btn.innerHTML='Add new keyword';}	
					},
				error: function(errorThrown){
				    alert(errorThrown.statusText);
					  if (Btn) { Btn.disabled=false; Btn.innerHTML='Add new keyword';}				
				}
				
				});	

}

function MultiD_placedictionaryeditor(id,value,object) {
	object.innerHTML='';
	object.editor=document.createElement('div');
	var area=document.createElement('textarea');
	area.value=value;
	area.id=id;
	object.editor.area=area;
	var save=document.createElement('button');
	save.className='button size11';
	save.innerHTML='Save';
	object.editor.save=save;
	var del=document.createElement('button');
	del.className='button size11';
	del.innerHTML='Delete';	
	object.editor.del=del;
	var cancel=document.createElement('button');
	cancel.className='button size11';
	cancel.innerHTML='Cancel';	
	
	object.appendChild(object.editor);
	object.editor.appendChild(area);
	object.editor.appendChild(save);
	object.editor.appendChild(cancel);
	object.editor.appendChild(del);
	object.editor.area.focus();
	jQuery(cancel).on('click', 
			 function () {
				object.innerHTML=value;
	});
	jQuery(save).on('click', 
			 function () {
				 if (confirm('Are you sure?')) {
				object.editor.save.disabled=true;
				object.editor.save.innerHTML='Saving...';
				var data={action:'MultiD_DictionaryChangeKey'};
				data['id']=id;
				data['newid']=object.editor.area.value;
				
				jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
				object.innerHTML=data;

				},
				error: function(errorThrown){
				    alert(errorThrown.statusText);
				object.editor.save.disabled=false;
				object.editor.save.innerHTML='Saving...';					
				}
				
				});	
				 }
	});
	
		jQuery(del).on('click', 
			 function () {
				if (confirm('Are you sure?')) {
				object.editor.del.disabled=true;
				object.editor.del.innerHTML='Deleting...';
				var data={action:'MultiD_DictionaryDeleteKey'};
				data['id']=id;
				
				
				jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
				MultiD_LoadDictionary();

				},
				error: function(errorThrown){
				    alert(errorThrown.statusText);
				object.editor.del.disabled=false;
				object.editor.del.innerHTML='Delete';					
				}
				
				});	
				}
	});
}
function MultiD_LoadSettings() {
jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:{action:'MultiD_Content'},
				success:function(data){
				  document.getElementById('multid_content').innerHTML=data;
				},
				error: function(errorThrown){
				    console.log(errorThrown);
					
				}
				
			});		
	
}

function MultiD_LoadDictionary(){
	if (window.MultiDAjax) window.MultiDAjax.abort()	
	var data=getAjaxparam('multid_dictionary');
	document.getElementById('multid_dictionarycontent').innerHTML='please wait...';
	data.action='MultiD_LoadDictionary';
		window.MultiDAjax=jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
				  document.getElementById('multid_dictionarycontent').innerHTML=data;
				  window.MultiDAjax=null;
				},
				error: function(errorThrown){
					 
				    console.log(errorThrown);
					window.MultiDAjax=null;
				}
				
			});	
			
	}

function MultiD_updateDictionary(Obj,Key) {
	var data={};
	Obj.style.background='#e4f6ff';
	data['action']=('MultiD_updateDictionary');
	data['key']=Key;
	data['lan']=Obj.id;
	data['label']=Obj.value;
	
		jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:data,
				success:function(data){
				  console.log('Update successfull');
				  Obj.style.background='';
				},
				error: function(errorThrown){
				    console.log(errorThrown);
					Obj.style.background='#f00';
				}
				
			});			
}

function Multid_AjaxCreateDB(Btn) {
	if (Btn) { Btn.disabled=true; Btn.value='Creating user tables...';}
		jQuery.ajax({
				type:'POST',
				url: '/wp-admin/admin-ajax.php',
				data:{action:'Multid_AjaxCreateDB'},
				success:function(data){
				  if (Btn) { Btn.value='Successful';}
				  MultiD_LoadSettings(); 
				},
				error: function(errorThrown){
				    alert(errorThrown.statusText);
					if (Btn) { Btn.disabled=false; Btn.value='Try again';}
				}
				
			});	
}

function MultiD_ChangeSettingsLan(Lan) {
$('.multid_Tabcontainer').css("display", "none");	
$('.multid_Tabcontainer.'+Lan).css("display", "block");		
	}