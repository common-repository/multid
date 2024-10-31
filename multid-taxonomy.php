<?php 

function MultiD_SaveTaxonomyMeta($termid) {
	//$varMulti=false;	
    static $recursing = false;
    if ( ! $recursing ) {
      $recursing = true;
	  	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $termid;
	
	if ( ! current_user_can( 'edit_term', $termid ) ){ return; }
	$targetlanguages=array();
	global $multid_default;
	$updateparams=array();
	foreach($_POST as $key=>$val) 
	if (substr( $key , 0, 7)=='multid_') 
	{   
		$master=explode('-',$key);

	    $keycode=$master[0];
		$lancode=$master[1];
		$val=MultiD_PostVar($key,null);
		
		if (!$val) delete_term_meta( $termid, $key);
		if ($keycode=='multid_slug') $val=sanitize_title($val);
		if (($keycode=='multid_slug')&&!$val&&MultiD_PostVar('multid_title-'.$lancode,null)) { $val=sanitize_title(MultiD_PostVar('multid_title-'.$lancode,null)); }
		if ($val) {
			$targetlanguages[$lancode]=1;
			update_term_meta( $termid, $key, $val);
			if ($lancode==$multid_default) {
				
				if ($keycode=='multid_slug') { $updateparams['slug']=$val; }
				if ($keycode=='multid_title') { $updateparams['name']=$val;}
				if ($keycode=='multid_postcontent') { $updateparams['description']=$val;}
				
				}
			
			}
	//$varMulti=true;	
	}
	//if ($varMulti) {
	if (count($updateparams)>0) 
		wp_update_term( $termid, MultiD_PostVar('taxonomy'),$updateparams);  
			
		update_option('multid_linkschanged',1);
	//}
	 $recursing = false;
	 
	}		
	
}

function MultiD_Term_Title($term,$language=null) {

	global $multid_default;
	$language=($language)?$language:MultiD_SiteLanguage();
	if ($language==$multid_default) { return $term->name; }
	return MultiD_TermMeta($term->term_id,'multid_title-'.$language,$term->name);
	
}

function MultiD_Term_Content($term,$language=null) {

	global $multid_default;
	global $multid_forcecontent;
	$language=($language)?$language:MultiD_SiteLanguage();
	return wpautop(MultiD_TermMeta($term->term_id,'multid_postcontent-'.$language,($multid_forcecontent==1||$language==$multid_default)?$term->description:""));
	
}

?>