<?php
/*
  Plugin Name: MultiD 
  Plugin URI: http://multid.rs
  Description: MultiD is an easy install plugin that provides multi-language capability to your wordpress theme with seo support utils and a built-in dictionary.
  Version: 1.0
  Author: Alpay ABAY
  Author URI: http://multid.rs/author
  License: Copyright 2018. Subject to be charged.
  Text Domain: multid.rs
*/

require_once('multid-content.php');
require_once('metaboxes.php');
require_once('multid-lib.php');
require_once('multid-taxonomy.php');
require_once('multid-widgets.php');
require_once('multid-rewrite.php');
require_once('multid-db.php');
class MultiD {
    
	function __construct() {
        add_action( 'admin_menu', array( $this, 'wpa_add_menu' ));
		//add_action( 'admin_init','MultiD_RegisterSettings'); 
        register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );
    }
	
	function SettingCallBack() {}
	function wpa_page_file_path() {}
    function wpa_install() { 
		
	 }
    function wpa_uninstall() {
		/* Temporary */
		delete_option('multid_default');
		delete_option('multid_languages');
		delete_option('multid_lancodes');
		delete_option('multid_fields');
	    delete_option('multid_posts');
		delete_option('multid_taxonomies');
		delete_option('multid_rewrites');
		delete_option('multid_hreflang');
		delete_option('multid_postdesc');
		delete_option('multid_dictionary');
		delete_option('multid_sitemap');
		}
	
	function wpa_add_menu() {
		add_options_page('MultiD', 'MultiD Settings', 'manage_options', 'multid_options_page_slug', array($this,'MultiD_PluginSettingsPage'));
		add_options_page('MultiD Dictionary', 'MultiD Dictionary', 'manage_options', 'multid_dictionary_page_slug', array($this,'MultiD_PluginDictionaryPage'));
	}
	
	function MultiD_PluginSettingsPage() {
		require_once('settings.php');
	}
	
	function MultiD_PluginDictionaryPage() {
		require_once('dictionary.php');
		}
	
}

new MultiD();


	
function MultiD_RegisterSettings() {
	register_setting( 'multid_options', 'multid_default');
	register_setting( 'multid_options', 'multid_languages');
	register_setting( 'multid_options', 'multid_lancodes');
	register_setting( 'multid_options', 'multid_fields');
	register_setting( 'multid_options', 'multid_posts');
	register_setting( 'multid_options', 'multid_taxonomies');
	register_setting( 'multid_options', 'multid_forcerewrite',array('default'=>0));
	register_setting( 'multid_options', 'multid_rewrites', array('default'=>array()));
	register_setting( 'multid_options', 'multid_forcecontent',array('default'=>0));
	register_setting( 'multid_options', 'multid_hreflang',array('default'=>1));
	register_setting( 'multid_options', 'multid_postdesc',array('default'=>1));
	register_setting( 'multid_options', 'multid_dictionary',array('default'=>array()));
	register_setting( 'multid_options', 'multid_languageschanged',array('default'=>0));
	register_setting( 'multid_options', 'multid_sitemap',array('default'=>1));
	register_setting( 'multid_options', 'multid_linkschanged',array('default'=>1));
	register_setting( 'multid_options', 'multid_sitemapslug',array('default'=>''));
	register_setting( 'multid_options', 'multid_hidenotranslation',array('default'=>1)); // hide content if there no translation
		MultiD_LoadGlobals();
		
}
	
MultiD_RegisterSettings();
	
function MultiD_LoadGlobals() {
	
		global $multid_languages;
		global $multid_lancodes;
		global $multid_taxonomies;
		global $multid_posts;
		global $multid_default;
		global $multid_fields;
		global $multid_forcerewrite;
		global $multid_forcecontent;
		global $multid_hreflang;
		global $multid_postdesc;
		global $multid_dbcheck;
		global $multid_rewrites;
		global $multid_hidenotranslation;
		global $multid_sitemap;
		global $multid_sitemapslug;
		$multid_rewrites=get_option('multid_rewrites');		
		$multid_languages=get_option('multid_languages');
		$multid_lancodes=get_option('multid_lancodes');
		$multid_default=get_option('multid_default');
		$multid_posts=get_option('multid_posts');
		$multid_taxonomies=get_option('multid_taxonomies');
		$multid_fields=get_option('multid_fields');	
		$multid_forcerewrite=get_option('multid_forcerewrite');	
		$multid_forcecontent=get_option('multid_forcecontent');
		$multid_hreflang=get_option('multid_hreflang');	
		$multid_postdesc=get_option('multid_postdesc');	
		$multid_sitemap=get_option('multid_sitemap');	
		$multid_sitemapslug=get_option('multid_sitemapslug');
		$multid_hidenotranslation=get_option('multid_hidenotranslation');	
		$multid_dbcheck=MultiD_CheckDB();
}
/*
add_action( 'pre_get_posts', function ( $q ) 
{  if (!is_admin()) {
   global $multid_forcerewrite;
   global $multid_default;
   if ((!$multid_forcerewrite)&&($multi_default!=MultiD_SiteLanguage()))
   if ( 
		$q->is_main_query()
    ) {
	

		$item=array(
				'relation' => 'OR',
				array(
                     'key' => 'multid_postcontent-'.MultiD_SiteLanguage(),
                     'compare' => 'EXISTS'
                ),
				array(
                     'key' => 'multid_slug-'.MultiD_SiteLanguage(),
                     'compare' => 'EXISTS'
                )				
				);
				
		$args=$q->meta_query;
		if ($args) { $args=$args+$item; } else { $args=array($item);}
		$q->set('meta_query',$args);
		
     
    }}
});
*/
function MultiD_AdminJS() { 
	wp_enqueue_script( 'multid', plugins_url() . '/multid/multid.js', array('jquery'), null, false );
	wp_enqueue_style( 'multid_admin_css',  plugins_url() . '/multid/multid.css' );
	/*TINYMICE REGISTER */

    wp_register_script('admin_js', get_template_directory_uri() . '/assets/js/admin.min.js', array( 'tiny_mce' ) );
}

add_action( 'admin_enqueue_scripts', 'MultiD_AdminJS' );

function MultiD_PostVar($Var,$Default=false) {
	return $_POST[$Var]!=null?$_POST[$Var]:$Default;
	}
function MultiD_GetVar($Var,$Default=false) {
	return $_GET[$Var]!=null?$_GET[$Var]:$Default;
	}

function MultiD_Meta($postID,$metakey,$defaultval="")
{ 	 if ($postID)
     return !empty(get_post_meta( $postID,$metakey,true))?get_post_meta( $postID,$metakey,true):$defaultval;
 } 
 
function MultiD_TermMeta($ID,$metakey,$defaultval='')
{ 	if ($ID)
     return !empty(get_term_meta( $ID,$metakey,true))?get_term_meta( $ID,$metakey,true):$defaultval;
 }
 
function MultiD_UniMeta($type=0,$id,$metakey,$default=null) { 
  if ($type==0) { return MultiD_Meta($id,$metakey,$default);} else return MultiD_TermMeta($id,$metakey,$default);
}

function MultiD_MetaboxesPost( $post_type ){ 
  global $multid_posts;
  if (isset($multid_posts[$post_type])) { 
  	add_meta_box( 'Multi_Metabox','Multi languages settings', 'MultiD_DrawMetaBoxContent');
  }

 }
add_action('add_meta_boxes', 'MultiD_MetaboxesPost');
function MultiD_DrawMetaBoxContent($item) {   
if (isset($item)&&$item) {
		MultiD_DrawLanguageSelector($item);
	} 
}

function MultiD_SavePost( $post_id ){
    static $recursing = false;
    $varMulti=false;
	if ( ! $recursing ) {
      $recursing = true;
	  	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id;
	if ( ! current_user_can( 'edit_post', $post_id ) ){ return; }
	
	global $multid_default;
	$targetlanguages=array();
	
	foreach($_POST as $key=>$val) 
	if (substr( $key , 0, 7)=='multid_') 
	{   
		$master=explode('-',$key);

	    $keycode=$master[0];
		$lancode=$master[1];
		$val=MultiD_PostVar($key,null);
		
		if (!$val) delete_post_meta( $post_id, $key);
		if ($keycode=='multid_slug') $val=sanitize_title($val);
		if (($keycode=='multid_slug')&&!$val&&MultiD_PostVar('multid_title-'.$lancode,null)) { $val=sanitize_title(MultiD_PostVar('multid_title-'.$lancode,null)); }
		if ($val) {
			update_post_meta( $post_id, $key, $val);
			$targetlanguages[$lancode]=1;
			if ($lancode==$multid_default) {
				
				if ($keycode=='multid_slug') { wp_update_post( array('ID' => $post_id,'post_name' => sanitize_title($val)));}
				if ($keycode=='multid_title') { wp_update_post( array('ID' => $post_id,'post_title' => $val));}
				if ($keycode=='multid_postcontent') { wp_update_post( array('ID' => $post_id,'post_content' => $val));}
				
				}
			
			}
	$varMulti=true;	
	}
		if ($varMulti) {		
		update_option('multid_linkschanged',1);
		}
	 $recursing = false;
	}	

}

add_action('save_post', 'MultiD_SavePost');



function MultiD_ClearFields() { 
  global $_wp_post_type_features;
  global $multid_posts;
  global $multid_taxonomies;
  $pt=MultiD_AllPostTypes();
  foreach ($pt as $p) {
  	if (isset($multid_posts[$p])) { 
		unset($_wp_post_type_features[$p]['title']);
		unset($_wp_post_type_features[$p]['editor']);
    }
  }
  
}
add_action('admin_init', 'MultiD_ClearFields');

function MultiD_AllPostTypes(){
	
 $pt=get_post_types(array('public'=> true,'_builtin' => false));
  array_push($pt,'post');
  array_push($pt,'page');
  array_push($pt,'attachment');
  return $pt;
  }
  
function MultiD_MetaboxesTerm( $item ){ 
  global $multid_taxonomies;
  if (isset($item)&&$item) {
		MultiD_DrawLanguageSelectorTerm($item);

	}
}
 





	
function MultiD_SiteLanguage()
{   global $multid_default;
 $site_language=get_query_var("site_language");
 if ($site_language) { 
 $_SESSION['multid_sitelanguage']=$site_language;
			return $site_language;
			} else
    			if (is_home()||is_front_page()) { $_SESSION['multid_sitelanguage']=$multid_default; return $multid_default; } 
				else
				return isset($_SESSION['multid_sitelanguage'])?$_SESSION['multid_sitelanguage']:$multid_default;
}	


function MultiD_Table($Table) {
	global $wpdb;
	return $wpdb->prefix . $Table;
	}
	
function MultiD_title_filter( $title) {
	
	static $recursing = false;
		if ( ! $recursing ) {
		global $wpdb;
		global $multid_default;
		
	 	$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_status='publish' and post_title = '" . $title . "'");
		
		if ($postid) {
		return MultiD_Post_Title(get_post($postid)); 
		} else  {
			
		$termid = $wpdb->get_var( "SELECT term_id FROM $wpdb->terms WHERE name = '" . $title . "'" );
		
		if ($termid) {
		return MultiD_Term_Title(get_term($termid)); 			
			
			} else return $title;
		}
		$recursing=false;
	}
	//else return $title;
}

add_filter( 'the_title', 'MultiD_title_filter' );

function MultiD_Post_Title($post,$language=null) {

	global $multid_default;
	$language=($language)?$language:MultiD_SiteLanguage();
	if ($language==$multid_default) { return $post->post_title; }
	return MultiD_Meta($post->ID,'multid_title-'.$language,$post->post_title);
	
}

function MultiD_Post_Content($post,$language=null) {

	global $multid_default;
	global $multid_forcecontent;
	$language=($language)?$language:MultiD_SiteLanguage();
	
	return wpautop(MultiD_Meta($post->ID,'multid_postcontent-'.$language,($multid_forcecontent==1||$language=$multid_default)?$post->post_content:""));
	
}


add_action( 'init', 'MultiD_UrlRewrite');
add_action( 'init', 'MultiD_SessionLoadDictionary');

remove_action('wp_head', 'rel_canonical');

function MultidD_disable_canonical_front_page( $redirect ) {
   //if ( is_page() && $front_page = get_option( 'page_on_front' ) ) {
      //  if ( is_page( $front_page ) ) {
            $redirect = false;
			
	//	}
   // } 
   // return $redirect;
}

add_filter( 'redirect_canonical', 'MultidD_disable_canonical_front_page' );



function Multid_Register_Taxonomies() {
	global $multid_taxonomies;	
	if ($multid_taxonomies)
		foreach($multid_taxonomies as $tax=>$val) {
			add_action('edited_'.$tax, 'MultiD_SaveTaxonomyMeta');
			add_action($tax.'_edit_form', 'MultiD_MetaboxesTerm' );
		}
}

Multid_Register_Taxonomies();

function MultiD_LoadDictionary() {
global $multid_languages;
global $multid_default;
$languages=$multid_languages;
$languages=array($multid_default=>1)+$languages;

$languages = array($multid_default => $languages[$multid_default]) + $languages;
	$query='select K.id,K.multid ';
	foreach($languages as $lan=>$val) 
	{   
		$query.=','.(($lan=='as')?'md_as':$lan).'.label '.(($lan=='as')?'md_as':$lan);  
	}
	$query.=' from multid_keyword K ';
	foreach($languages as $lan=>$val) { 
	$query.=' left join multid_value '.(($lan=='as')?'md_as':$lan).' on '.(($lan=='as')?'md_as':$lan).'.keyword=K.id and '.(($lan=='as')?'md_as':$lan).'.lan=\''.$lan.'\'';
	 }
	$q=""; 
	$sq="";
	if (MultiD_PostVar('searchname',null)) { 
	 	foreach($languages as $lan=>$val) { 
		$sq.=" ".(($lan=='as')?'md_as':$lan).".label like '%".MultiD_PostVar('searchname','')."%' or ";
		 }
		$sq.=" K.id like '%".MultiD_PostVar('searchname','')."%' or ";
		
		$sq=substr($sq,0,strlen($sq)-3); 
	}
	
	if ($sq!="") $q.="(".$sq.") and ";
	if ($q!="") $query.=" where ".substr($q,0,strlen($q)-4);
	 
	$query.=' order by K.id limit 25'; 
	global $wpdb;
	$result=$wpdb->get_results($query,OBJECT);
	if (!is_wp_error($result)) {	
	  require_once('dictionary_content.php');
	} else var_dump($result);
	 die("");
}
add_action( 'wp_ajax_MultiD_LoadDictionary', 'MultiD_LoadDictionary' );	



function MultiD_updateDictionary() { 
   MultiD_UPdateDictionaryValue(MultiD_PostVar('key',''),MultiD_PostVar('lan',''),MultiD_PostVar('label',''));
}

function MultiD_UPdateDictionaryValue($id,$lan,$label) {
	
	global $wpdb;
	$result=$wpdb->get_results("select * from multid_value where keyword='$id' and lan='$lan'",OBJECT);
	if (!is_wp_error($result)) {
		$row=current($result);
		if ($row) { $query="update multid_value set label='$label' where keyword='$id' and lan='$lan'"; }
			else { $query="insert into multid_value (keyword,lan,label) values ('$id','$lan','$label');"; }
	
			$result=$wpdb->query($query,OBJECT);
			if (is_wp_error($result)) { var_dump($result); exit(); }
				MultiD_SynronizeDictionary(true);
	} else var_dump($result);	
	die("");
	}
add_action( 'wp_ajax_MultiD_updateDictionary', 'MultiD_updateDictionary' );	

function MultiD_DictionaryChangeKey() {
	if (!MultiD_PostVar('id',null)||!MultiD_PostVar('newid',null)) { echo "Can not enter empty key."; exit(); }
	global $wpdb;
	$query="update multid_keyword set id='".esc_sql(sanitize_title(MultiD_PostVar('newid')))."' where id='".esc_sql(MultiD_PostVar('id'))."';";
	$result=$wpdb->query($query,OBJECT);
	if (is_wp_error($result)) { var_dump($result); exit(); }
	$query="update multid_value set keyword='".esc_sql(sanitize_title(MultiD_PostVar('newid')))."' where keyword='".esc_sql(MultiD_PostVar('id'))."';";
	$result=$wpdb->query($query,OBJECT);
	if (is_wp_error($result)) { var_dump($result); exit(); }
	
	echo sanitize_title(MultiD_PostVar('newid'));
	MultiD_SynronizeDictionary(true);
	die("");
	
	}
	
add_action( 'wp_ajax_MultiD_DictionaryChangeKey', 'MultiD_DictionaryChangeKey' );

function MultiD_LoadDictionary_Add() {
	if (!MultiD_PostVar('id',null)) { echo "Can not enter empty key."; exit(); }
	global $wpdb;
	$query="insert into multid_keyword (id) values ('".esc_sql(sanitize_title(MultiD_PostVar('id')))."');";
	$result=$wpdb->query($query,OBJECT);
	if (is_wp_error($result)) { var_dump($result); exit(); }
	echo sanitize_title(MultiD_PostVar('id'));
	MultiD_SynronizeDictionary(true);
	die("");
	
	}
	
add_action( 'wp_ajax_MultiD_LoadDictionary_Add', 'MultiD_LoadDictionary_Add' );


function MultiD_DictionaryDeleteKey() {
		if (!MultiD_PostVar('id',null)) { echo "Provide a key."; exit(); }
	global $wpdb;
	$query="delete from multid_keyword where id='".esc_sql(MultiD_PostVar('id'))."';";
	$result=$wpdb->query($query,OBJECT);
	
	if (is_wp_error($result)) { var_dump($result); exit(); }
	MultiD_SynronizeDictionary(true);
	die("");
	
	}		
add_action( 'wp_ajax_MultiD_DictionaryDeleteKey', 'MultiD_DictionaryDeleteKey' );
	
/* COMMENTS */
function Multid_comment_inserted($comment_id, $comment_object) {
    if ($comment_object->comment_parent > 0) {
		update_comment_meta( $comment_id, 'multid_lan', MultiD_PostVar('multid_lan',null)?MultiD_PostVar('multid_lan'):MultiD_SiteLanguage()); 
    }
}

add_action('wp_insert_comment','Multid_comment_inserted',99,2);




// DICTIONARY



function MultiD_SynronizeDictionary($LoadSession=false) {
$res=array();

global $multid_languages;
global $multid_default;
$languages=$multid_languages;
$languages=array($multid_default=>1)+$languages;
$languages = array($multid_default => $languages[$multid_default]) + $languages;
	
	$query='select K.id,K.multid ';
	foreach($languages as $lan=>$val) 
	{   
		$query.=','.(($lan=='as')?'md_as':$lan).'.label '.(($lan=='as')?'md_as':$lan);  
	}
	$query.=' from multid_keyword K ';
	foreach($languages as $lan=>$val) { 
	$query.=' left join multid_value '.(($lan=='as')?'md_as':$lan).' on '.(($lan=='as')?'md_as':$lan).'.keyword=K.id and '.(($lan=='as')?'md_as':$lan).'.lan=\''.$lan.'\'';
	 }
	$q=""; 
	$sq="";
	if (MultiD_PostVar('searchname',null)) { 
	 	foreach($languages as $lan=>$val) { 
		$sq.=" ".(($lan=='as')?'md_as':$lan).".label like '%".MultiD_PostVar('searchname','')."%' or ";
		 }
		$sq.=" K.id like '%".MultiD_PostVar('searchname','')."%' or ";
		
		$sq=substr($sq,0,strlen($sq)-3); 
	}
	
	if ($sq!="") $q.="(".$sq.") and ";
	$q=$q." K.multid=0 and ";
	if ($q!="") $query.=" where ".substr($q,0,strlen($q)-4);
	 
	$query.=' order by K.id'; 
	global $wpdb;
	$result=$wpdb->get_results($query,OBJECT);
	if (!is_wp_error($result)) {	
	 foreach($result as $row) {
	 $res[$row->id]=array();
	   	foreach($languages as $lan=>$val) { 
		 $field=(($lan=='as')?'md_as':$lan);
		   $res[$row->id][$lan]=$row->{$field};
		 }	
	 
	 }
	 
	 update_option('multid_dictionary',$res);
	 if ($LoadSession==true) { MultiD_SessionLoadDictionary(true); }
	} else var_dump($result);
	
}



function MultiDic($keyword,$lang='',$default='') {
	global $multid_default;
	global $multid_dictionary;
	//echo $keyword." ".$default;
	$source=(isset($_SESSION)&&isset($_SESSION['multid_dictionary']))?$_SESSION['multid_dictionary']:$multid_dictionary;
	
	if (($keyword=='')||(!$keyword)) return '';
	if ($lang=='') $lang=MultiD_SiteLanguage()?MultiD_SiteLanguage():$multid_default;
	
	if ($source) {
	$k=isset($source[$keyword])?$source[$keyword]:null;
	if ($k) {
		$l=$k[$lang];
		if ($l) { return $l; } else { return ($k[$multid_default])?$k[$multid_default]:''; }
		
		} else return $default;
	} return $default;
}

function MultiD_SessionLoadDictionary($force) {
	global $multid_dictionary;
	if (isset($_SESSION)) {
	if (!isset($_SESSION['multid_dictionary'])||($force==true)) {
		$_SESSION['multid_dictionary']=get_option('multid_dictionary');
		}
	
	} else {
		
		$multid_dictionary=get_option('multid_dictionary');
		
		}
		
		
	
}

function MultiD_pre_get_document_title( $array ) { 
    
  $obj=get_queried_object();
  $t=get_bloginfo('name'); 	 
  if ($obj!=null) {
		$title=(get_class($obj)=="WP_Post")?MultiD_Post_Title($obj):'';
		$title=(get_class($obj)=="WP_Term")?MultiD_Term_Title($obj):$title;
		if ($title) return $title.' | '.$t;
  } else return $t;
}; 
         
// add the filter 
add_filter( 'pre_get_document_title', 'MultiD_pre_get_document_title', 10, 1 ); 

function MultiD_WPHead() {
	
	switch_to_locale(MultiD_SiteLanguage());
	
	global $multid_hreflang;
	global $multid_languages;
	global $multid_default;
	global $multid_postdesc;
	if ($multid_hreflang) {

		$x=array();
		$x[$multid_default]=1;
		$languages=$multid_languages;
		$languages=$x+$multid_languages;
		unset($languages[MultiD_SiteLanguage()]);
		array_values($languages);
		
		$obj=get_queried_object();
		if ($obj)
		 
	
			foreach($languages as $item=>$val) {
				
				$link=(get_class($obj)=="WP_Post")?MultiD_Post_Permalink(null,array('postid'=>$obj->ID,'language'=>$item)):'';
				$link=(get_class($obj)=="WP_Term")?MultiD_Term_Permalink(null,array('termid'=>$obj->term_id,'language'=>$item)):$link;
				if ($link) {
				?>
				<link rel="alternate" hreflang="<?php echo $item?>" href="<?php echo $link ?>">
				<?php
				}
				}		
		
		
		} // hreflang
		global $multid_postdesc;
		if ($multid_postdesc&&isset($obj)) {
				$desc=MultiD_SiteDesc($obj);
				
				
				?>
                <meta name="description" content="<?php echo esc_html($desc); ?>">
				<?php
					
		}
		if (isset($obj)) {
		$link=(get_class($obj)=="WP_Post")?MultiD_Post_Permalink(null,array('postid'=>$obj->ID,'language'=>MultiD_SiteLanguage())):'';
		$link=(get_class($obj)=="WP_Term")?MultiD_Term_Permalink(null,array('termid'=>$obj->term_id,'language'=>MultiD_SiteLanguage())):$link;
		}
		if (isset($link)) {
					
		?>
		<link rel="canonical" href="<?php echo $link ?>">
        <?php
		}
	}
add_action('wp_head', 'MultiD_WPHead');	

function MultiD_language_attributes( $output, $doctype ) {  
    return 'lang="'.MultiD_SiteLanguage().'"'; 
}; 
         
add_filter( 'language_attributes', 'MultiD_language_attributes', 10, 2 ); 

function MultiD_SiteDesc($obj,$language='') { 
	global $multid_forcecontent;
	global $multid_default;
	$language=($language)?$language:MultiD_SiteLanguage();
	
	
if (get_class($obj)=="WP_Post") { 
	$desc=MultiD_Meta(
		$obj->ID,
		'multid_postdesc-'.$language,
		($multid_forcecontent==1)?MultiD_Meta($obj->ID,'multid_postdesc-'.$multid_default,''):''
		); }
else if (get_class($obj)=="WP_Term") { 
	$desc=MultiD_TermMeta(
		$obj->term_id,
		'multid_postdesc-'.$language,
		($multid_forcecontent==1)?MultiD_TermMeta($obj->term_id,'multid_postdesc-'.$multid_default,''):''
		); }
return $desc;
}

function MultiD_XmlDoc($Filename,$RootName)
{ $xmlDoc = new DOMDocument();
 if ($Filename!="") { 
  if ($xmlDoc->load($filename)) { } else { throw new Exception("No Load");}
 } 	else { $xmlDoc->appendChild($xmlDoc->createElement($RootName)); }
 return $xmlDoc;
}

function MultiD_Addxmlchild($XmlDoc,$XmlNode,$Tagname) 
{ 
   return $XmlNode->appendChild($XmlDoc->createElement($Tagname));      
   }
   


/* Add the feed. */
function MultiD_SiteMap_init(){
	global $multid_sitemapslug;
	add_feed(($multid_sitemapslug!='')?$multid_sitemapslug:'sitemap.xml', 'MultiD_SiteMap');
}
add_action('init', 'MultiD_SiteMap_init');

/* Filter the type, this hook wil set the correct HTTP header for Content-type. */
function MultiD_SiteMap_content_type( $content_type, $type ) {
	if ( 'my_custom_feed' === $type ) {
		return feed_content_type( 'rss2' );
	}
	return $content_type;
}
add_filter( 'feed_content_type', 'MultiD_SiteMap_content_type', 10, 2 );

/* Show the RSS Feed on domain.com/?feed=my_custom_feed or domain.com/feed/my_custom_feed. */
function MultiD_SiteMap() {
	global $multid_rewrites;
	
	$m='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	$s=site_url();
	foreach ($multid_rewrites as $item) {
		$m.='<url><loc>'.esc_attr($s."/".substr($item["rw"],0,strlen($item["rw"])-1)).'</loc>'.((isset($item['mod']))?'<lastmod>'.$item["mod"].'</lastmod>':'').'</url>';		
		}
	$m.="</urlset>";
	header("Content-type: text/xml");
	echo $m;
	die("");	
} ;	



/* BUGGY // BETTER USE MULTID_POST_CONTENT // IS SINGULAR MAY FIX IT
Dom_TemplatePart ile kesişiyor */

function MultiD_the_content_in_the_main_loop( $content ) {

if ( in_the_loop() ) {
 $post=get_post();
if ($post) { 
 return  MultiD_Post_Content($post);
  }  else return $content;
} else return $content;
}
add_filter( 'the_content', 'MultiD_the_content_in_the_main_loop' )
?>