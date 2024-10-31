<?php 

function MultiD_UrlRewrite() {
	MultiD_UrlRewriteCache();
	//if (!is_admin()) {
	global $multid_rewrites;
	if (get_option('multid_linkschanged')) { MultiD_UrlRewriteCache(); }
	
	$multid_rewrites=$multid_rewrites?$multid_rewrites:array();
	foreach($multid_rewrites as $item) {
		add_rewrite_rule( '^'.$item["rw"], $item["int"], 'top' );
		}
	
	flush_rewrite_rules();	
	//}
}

function MultiD_UrlRewriteCache() {
	static $recursing = false;
    if ( ! $recursing ) {
      $recursing = true; 
	
	
	$rewrites=get_option('multid_rewrites');
	
	//
	
	$RWArray=array();
	
	/* POSTS */
	global $wpdb;
	global $multid_default;
	global $multid_languages;
	global $multid_posts;
	$query='DROP TEMPORARY TABLE IF EXISTS multid_language;';	$result=$wpdb->get_results($query,OBJECT);
		if (!is_wp_error($result)) {} else var_dump($result);
			
	$query='CREATE TEMPORARY TABLE multid_language Select \''.esc_sql($multid_default).'\' as lan;'; $result=$wpdb->get_results($query,OBJECT);
		if (!is_wp_error($result)) {} else var_dump($result);
		
	foreach($multid_languages as $item=>$Val) {
			$query='insert into multid_language values (\''.esc_sql($item).'\');'; $result=$wpdb->get_results($query,OBJECT);
			if (!is_wp_error($result)) {} else var_dump($result);
	      
		}	
	
	$q="";
	foreach($multid_posts as $Key=>$Val) {
	$q.="'".esc_sql($Key)."',";
	}
	
		
	$query='SELECT distinct P.ID,P.post_name,P.post_type,R.meta_value root,if(M.meta_value is null,P.post_name,M.meta_value) slug,L.lan meta_key,DATE_FORMAT(P.post_modified,\'%Y/%m/%d\') post_modified FROM '.MultiD_Table('posts').' P 
left join multid_language L on L.lan=L.lan
left join '.MultiD_Table('postmeta').' M on M.post_id=P.ID and M.meta_key=concat(\'multid_slug-\',L.lan)
left join '.MultiD_Table('postmeta').' R on R.post_id=P.ID and R.meta_key=concat(\'multid_root-\',L.lan)
 where P.post_status=\'publish\' '.(($q!="")?' and P.post_type in ('.substr($q,0,strlen($q)-1).')':'').'
			order by P.ID ';
		
    $result=$wpdb->get_results($query,OBJECT);

	if (!is_wp_error($result)) {	
	
		
		foreach($result as $row) {
			if ($row->meta_key) {

		    $lancode=$row->meta_key;
			} else {
				$lancode=MultiD_SiteLanguage();
				
				}
			$lan=current(explode('_',$lancode));
			//$rw=$lan.(($row->root)?'/'.$row->root:'').'/'.$row->slug.'/?';
			$rw=(($lancode!=$multid_default)?$lan:'').(($row->root)?'/'.$row->root:'').'/'.$row->slug.'/?';
			if (substr($rw,0,1)=='/') { $rw=substr($rw,1,strlen($rw));}
			
			
			if ((string) $row->post_type=='page') {
			$link='index.php?pagename='.$row->post_name.'&site_language='.$lancode;
			} 
			else if ((string) $row->post_type=='post') {
			$link='index.php?p='.$row->ID.'&site_language='.$lancode;
			}
			  else {
				$link='index.php?'.$row->post_type.'='.$row->post_name.'&site_language='.$lancode;
			//$link='index.php?p='.$row->ID.'&site_language='.$lancode;
			}
			$RWArray['p'.$row->ID.$lancode]=array('rw'=>$rw,'int'=>$link,'mod'=>$row->post_modified);

			}
	
	  
	} else var_dump($result);

	//* TERMS *//

	global $multid_taxonomies;
	$q="";
	foreach($multid_taxonomies as $Key=>$Val) {
	$q.="'".esc_sql($Key)."',";
	}
	
	$query='SELECT distinct P.term_id ID,P.slug post_name,T.taxonomy,R.meta_value root,if(M.meta_value is null,P.slug,M.meta_value) slug,L.lan meta_key
FROM '.MultiD_Table('terms').' P 
left join multid_language L on L.lan=L.lan
left join '.MultiD_Table('term_taxonomy').' T on T.term_id=P.term_id 
left join '.MultiD_Table('termmeta').' M on M.term_id=P.term_id and M.meta_key=concat(\'multid_slug-\',L.lan)
left join '.MultiD_Table('termmeta').' R on R.term_id=P.term_id and R.meta_key=concat(\'multid_root-\',L.lan) 
'.(($q!="")?' where T.taxonomy in ('.substr($q,0,strlen($q)-1).')':'').'  order by P.term_id';		
			
    $result=$wpdb->get_results($query,OBJECT);
	if (!is_wp_error($result)) {	
		
		
		foreach($result as $row) {
			if ($row->meta_key) {
			
		    $lancode=$row->meta_key;
			} else {
				$lancode=MultiD_SiteLanguage();
				}
			$lan=current(explode('_',$lancode));
			
			$rw=(($lancode!=$multid_default)?$lan:'').(($row->root)?'/'.$row->root:'').'/'.$row->slug.'/?';
			if (substr($rw,0,1)=='/') { $rw=substr($rw,1,strlen($rw));}
			
			
			$link='index.php?'.$row->taxonomy.'='.$row->post_name.'&site_language='.$lancode; 
			//$link='index.php?'.$row->taxonomy.'='.$row->post_name.'&site_language='.$lancode; 
			$RWArray['c'.$row->ID.$lancode]=array('rw'=>$rw,'int'=>$link);

			}
	
	} else var_dump($result);	
	

	
		
	global $multid_languages;
	$what= get_option('show_on_front');
		if (($multid_languages))
		foreach($multid_languages as $item=>$val) {
		$lan=current(explode('_',$item));
			if ($what=='page') { 
				$front = get_post( get_option( 'page_on_front' ) );
				
							unset($RWArray['p'.$front->ID.$item]);
							array_values($RWArray);
							$RWArray['p'.$front->ID.$item]=array('rw'=>$lan.'/?','int'=>'index.php?page_id='.$front->ID.'&site_language='.$item);
			} else {
				$RWArray[$item]=array('rw'=>$lan.'/?','int'=>'index.php?post_type=post&site_language='.$item);
				
			}
		 
			}
		
	update_option('multid_rewrites',$RWArray);
	update_option('multid_linkschanged',0);
	
	    flush_rewrite_rules();
		
	 $recursing = false;
	 
	}
			$query='DROP TEMPORARY TABLE IF EXISTS multid_language;';	$result=$wpdb->get_results($query,OBJECT);
		if (!is_wp_error($result)) {} else var_dump($result);
	}


function MultiD_query_vars_filter( $vars ){
  $vars[] = "site_language";
  $vars[] = "p";
  return $vars;
}
add_filter( 'query_vars', 'MultiD_query_vars_filter' );

function MultiD_The_Permalink( $url ) {
  static $recursing = false;
  if ( ! $recursing ) {
	     $recursing = true;
		  if (!is_admin()) {
			  $postid = url_to_postid( $url );
			  
			  $url=MultiD_Post_Permalink(null,array('postid'=>$postid,'url'=>$url));
			  
			}
				
	$recursing = false;	
	return $url;
 } ;
}

function MultiD_Post_Permalink($post,$args=array()) {
	global $multid_default;
	global $multid_rewrites;
	global $multid_forcerewrite;
	$_postid=isset($args['postid'])?$args['postid']:null;
	$url=isset($args['url'])?$args['url']:null;

	$language=isset($args['language'])?$args['language']:MultiD_SiteLanguage();
	$post=(isset($post))?$post:($_postid)?get_post($_postid):null;

		if ($post) {
			$order=isset($multid_rewrites['p'.$post->ID.$language])?$multid_rewrites['p'.$post->ID.$language]:null;
			
			if ($order==null&&(!$multid_forcerewrite)) $order=isset($multid_rewrites['p'.$post->ID.$multid_default])?$multid_rewrites['p'.$post->ID.$multid_default]:null;
			
			if ($order) {
				
			$link=$order['rw'];
			$url = site_url()."/".substr($link,0,strlen($link)-1);
			
			}
		return $url;
		}
		
}

add_filter( 'post_link', 'MultiD_The_Permalink' );
add_filter( 'post_type_link', 'MultiD_The_Permalink' );

function MultiD_Term_Permalink($term,$args=array()) {
  
	global $multid_default;
	global $multid_rewrites;
	global $multid_forcerewrite;

	$_termid=isset($args['termid'])?$args['termid']:null;
	$url=isset($args['url'])?$args['url']:null;
	$language=isset($args['language'])?$args['language']:MultiD_SiteLanguage();
	$hideempty=isset($args['hideempty'])?$args['hideempty']:0;
	$term=(isset($term)&&(!is_wp_error($term)))?$term:(($_termid)?get_term($_termid):null);
	

		if ($term) {
		$order=isset($multid_rewrites['c'.$_termid.$language])?$multid_rewrites['c'.$_termid.$language]:null;
		if (!$order) if ($hideempty==1&&$language!=$multid_default) { return null; } 
		
		if ($order==null&&(!$multid_forcerewrite)) $order=isset($multid_rewrites['c'.$_termid.$multid_default])?$multid_rewrites['c'.$_termid.$multid_default]:null;
		if ($order) {
			
		$link=$order['rw'];
		$url = site_url()."/".substr($link,0,strlen($link)-1);
		
		}
		return $url;
		}
   

}

function MultiD_Term_Link_Filter($url, $term, $taxonomy) {
  static $tlrecursing = false;
  if ( ! $tlrecursing ) {
	     $tlrecursing = true;
		  if (!is_admin()) {
			  
			  $url=MultiD_Term_Permalink(null,array('termid'=>$term->term_id,'taxonomy'=>$taxonomy,'url'=>$url)); 
			}	
	$tlrecursing = false;	
	return $url;
 }

}

add_filter( 'term_link', 'MultiD_Term_Link_Filter', 10, 3 );

function MultiD_Get_Term($_term) {
	$_term->name=MultiD_Term_Title($_term);
	return $_term;
	}
add_action('get_term','MultiD_Get_Term');

function MultiD_filter_wp_nav_menu_objects( $sorted_menu_items, $args ) { 
  static $recursing = false;
  if ( ! $recursing ) {
	     $recursing = true;
		 global $multid_posts;
		 global $multid_terms;
		 global $multid_default;
    foreach($sorted_menu_items as &$item) {
		
		
		 
  			if (isset($multid_posts[$item->object])) { 
			$item->url = MultiD_Post_Permalink(null,array('postid'=>$item->object_id,'url'=>$item->url));
		    }
  			if (isset($multid_terms[$item->object])) { 
			$item->url = MultiD_Term_Permalink(null,array('termid'=>$item->object_id,'url'=>$item->url));
		    }
						
			if ($item->url=='/'||$item->url=='') { if ($multid_default!=MultiD_SiteLanguage()) $item->url=MultiD_HomeUrl(); }
			
			 
				$i=MultiDic(sanitize_title($item->title),null,'');
				if ($i) $item->title=$i; 
				
				
		
		}
	$recursing = false;		
    return $sorted_menu_items; 
  }
}; 
         

add_filter( 'wp_nav_menu_objects', 'MultiD_filter_wp_nav_menu_objects', 99999, 2 ); 

function MultiD_HomeUrl() {
	global $multid_default;
return site_url().'/'.(($multid_default!=MultiD_SiteLanguage())?current(explode('_',MultiD_SiteLanguage())).'/':'');	
}
?>