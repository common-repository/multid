<?php 
function MultiD_DictionaryWidget($atts,$Content=null) {
    global $wp_widget_factory;
    extract(shortcode_atts(array(       'widget_name' => FALSE    ), $atts));
    $widget_name = 'MultiD_Dictionary';
    $a=$wp_widget_factory->widgets[$widget_name];	
    ob_start();
    the_widget($widget_name, $atts, array('widget_id'=>"",
        'before_widget' => null,
        'after_widget' => null,
        'before_title' => null,
        'after_title' => null
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode('MultiD','MultiD_DictionaryWidget');

function MultiD_LanguageWidget($atts,$Content=null) {
    global $wp_widget_factory;
    extract(shortcode_atts(array(       'widget_name' => FALSE    ), $atts));
    $widget_name = 'MultiD_Changer';
    $a=$wp_widget_factory->widgets[$widget_name];	
    ob_start();
    the_widget($widget_name, $atts, array('widget_id'=>"",
        'before_widget' => null,
        'after_widget' => null,
        'before_title' => null,
        'after_title' => null
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode('MultiDLanguage','MultiD_LanguageWidget');

class MultiD_Changer extends WP_Widget {

public function __construct() {
    $widget_options = array( 
      'classname' => 'MultiDW_LanguageSelector',
      'description' => 'Provides a language changer.',
    );
   parent::__construct( 'MultiD_Changer', 'MultiD - Language selector.', $widget_options );

  }

public function widget( $args, $instance ) {

  if (isset($instance)) {
  $Content = apply_filters( 'MultiD_Changer_Content', array("instance"=>$instance));
  echo $Content;
  }
  
}

public function form( $instance ) {
  $id = ! empty( $instance['id'] ) ? $instance['id'] : '';
  $onchange = ! empty( $instance['onchange'] ) ? $instance['onchange'] : '';
  $style = ! empty( $instance['style'] ) ? $instance['style'] : '';
  $classname = ! empty( $instance['classname'] ) ? $instance['classname'] : '';
  $css = ! empty( $instance['css'] ) ? $instance['css'] : '';
  $label = ! empty( $instance['label'] ) ? $instance['label'] : '';
  ?>
  	<div>Object ID</div>
    <input type="text" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo (($this->get_field_name( 'id' ))?$this->get_field_name( 'id' ):""); ?>" value="<?php echo esc_attr( $id ); ?>" class="InbosoStyled ww100"/>  	<div>Label</div>
    <input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo (($this->get_field_name( 'label' ))?$this->get_field_name( 'label' ):""); ?>" value="<?php echo esc_attr( $label ); ?>" class="InbosoStyled ww100" placeholder="Word/MultiD keyword"/>        
  	<div>OnChange(js)</div>
    <input type="text" id="<?php echo $this->get_field_id( 'onchange' ); ?>" name="<?php echo (($this->get_field_name( 'onchange' ))?$this->get_field_name( 'onchange' ):""); ?>" value="<?php echo esc_attr( $onchange ); ?>" class="InbosoStyled ww100"/> 
  	<div>DrawStyle</div>
    <input type="text" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo (($this->get_field_name( 'style' ))?$this->get_field_name( 'style' ):""); ?>" value="<?php echo esc_attr( $style ); ?>" class="InbosoStyled ww100"/>
  	<div>Classname</div>
    <input type="text" id="<?php echo $this->get_field_id( 'classname' ); ?>" name="<?php echo (($this->get_field_name( 'classname' ))?$this->get_field_name( 'classname' ):""); ?>" value="<?php echo esc_attr( $classname ); ?>" class="InbosoStyled ww100"/>                           
    <div>Css style</div>
    <input type="text" id="<?php echo $this->get_field_id( 'css' ); ?>" name="<?php echo (($this->get_field_name( 'css' ))?$this->get_field_name( 'css' ):""); ?>" value="<?php echo esc_attr( $css ); ?>" class="InbosoStyled ww100"/>  
  <?php  
}

} // END OF CLASS

function Register_MultiD_Changer() { 
  register_widget( 'MultiD_Changer' );
}
add_action( 'widgets_init', 'Register_MultiD_Changer' );

function MultiD_Changer_Content($arguments) {
	if (!is_admin()) {
		$instance=$arguments["instance"];
		global $multid_languages;
		global $multid_default;
		global $MultiDLocales;
		
		$x=array();
		$x[$multid_default]=1;
		$languages=$multid_languages;
		$languages=$x+$multid_languages;
		//unset($languages[MultiD_SiteLanguage()]);
		array_values($languages);
		
		$label=!empty($instance['label'])?$instance['label']:'';
		$id=!empty($instance['id'])?$instance['id']:'';
		$what = get_option('show_on_front');
		$front = get_post( get_option( 'page_on_front' ) );
		$queried_object = get_queried_object();
		
		
		
			if ($queried_object) {
				$class=get_class($queried_object);
				
			
			
		}
		
		?>
		<div <?php echo ($id)?' id="'.$id.'"':''?> class="MultiDW_LanguageSelectorContainer <?php echo !empty($instance['classname'])?$instance['classname']:''?>" <?php echo !empty($instance['css'])?' style="'.$instance['css'].'"':''?>><?php if ($label) { ?><label><?php echo esc_html(MultiDic($label,'',$label)); ?><?php } ?><?php if ($label) { ?></label><?php } ?>
		<select  onchange="<?php echo !empty($instance['onchange'])?$instance['onchange'].";":'' ?>window.location.href = this.value;" >
		<?php 
		$done=0;
		foreach($languages as $lan=>$val) { 
		$lancode=current(explode('_',$lan));
		
		if ($class=='WP_Term') { 
			$term=$queried_object;
			
			
			 $link=MultiD_Term_Permalink(null,array('termid'=>$term->term_id,'language'=>$lan,'hideempty'=>1));
			 if ($link) {
			?>
			<option class="<?php echo $lan; ?>" value="<?php echo esc_attr($link); ?>" <?php echo ($lan==MultiD_SiteLanguage())?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]); ?></option>
            <?php
			 }
			} else 
			if (is_home()) { $done=1; ?>
			<option class="<?php echo $lan; ?>" value="<?php echo esc_attr(site_url().(($multid_default!=$lan)?"/".$lancode."/":"")); ?>" <?php echo ($lan==MultiD_SiteLanguage())?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]) ?></option>
			<?php 
			} if (is_front_page()&&$done==0) { ?>
			<option class="<?php echo $lan; ?>" value="<?php echo esc_attr(site_url().(($multid_default!=$lan)?"/".$lancode."/":"")); ?>" <?php echo ($lan==MultiD_SiteLanguage())?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]) ?></option>
			<?php 
			}
			
			 else if ($class=='WP_Post') { 
			$post=$queried_object;
			if (MultiD_Meta($post->ID,'multid_slug-'.$lan,null)) 
			{ $link=MultiD_Post_Permalink($post,array('language'=>$lan));
			?>
			<option class="<?php echo $lan; ?>" value="<?php echo esc_attr($link); ?>" <?php echo ($lan==MultiD_SiteLanguage())?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]) ?></option>
            <?php }} 
			
		} ?>
        </select>
        
		</div> 
		<?php return "";
	}
}
add_filter('MultiD_Changer_Content', 'MultiD_Changer_Content');

//* MULTID DICTIONARY *//

class MultiD_Dictionary extends WP_Widget {

public function __construct() {
    $widget_options = array( 
      'classname' => 'MultiDW_LanguageSelector',
      'description' => 'Provides a language changer.',
    );
   parent::__construct( 'MultiD_Dictionary', 'MultiD - Dictionary Keyword', $widget_options );

  }

public function widget( $args, $instance ) {

  if (isset($instance)) {
  $Content = apply_filters( 'MultiD_Dictionary_Content', array("instance"=>$instance));
  echo $Content;
  }
  
}

public function form( $instance ) {
		global $multid_languages;
		global $multid_default;
		global $MultiDLocales;
		$languages=array($multid_default=>1)+$multid_languages;
		
		$keyword=!empty($instance['keyword'])?$instance['keyword']:'';
		$lang=!empty($instance['lang'])?$instance['lang']:'';
  ?>
  	<div>Keyword</div>
    <input type="text" id="<?php echo $this->get_field_id( 'keyword' ); ?>" name="<?php echo (($this->get_field_name( 'keyword' ))?$this->get_field_name( 'keyword' ):""); ?>" value="<?php echo esc_attr( $keyword ); ?>" class="InbosoStyled ww100"/>        
    <div>Language</div>
    <select id="<?php echo $this->get_field_id( 'lang' ); ?>" name="<?php echo (($this->get_field_name( 'lang' ))?$this->get_field_name( 'lang' ):""); ?>" class="InbosoStyled ww100">
    <option value="">-/-</option>
	<?php foreach($languages as $lan=>$val) { ?>
    <option value="<?php echo $lan?>" <?php echo ($lang==$lan)?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]); ?></option>
    <?php } ?>
    </select> 
   
  <?php  
}

} // END OF CLASS

function Register_MultiD_Dictionary() { 
  register_widget( 'MultiD_Dictionary' );
}
add_action( 'widgets_init', 'Register_MultiD_Dictionary' );

function MultiD_Dictionary_Content($arguments) {
	if (!is_admin()) {
		$instance=$arguments["instance"];
		$keyword=!empty($instance['keyword'])?$instance['keyword']:'';
		$lang=!empty($instance['lang'])?$instance['classname']:'';
		
		return MultiDic($keyword,$lang);
	}
}
add_filter('MultiD_Dictionary_Content', 'MultiD_Dictionary_Content'); 

/* MULTID POST & TERM LINK */ 

class MultiD_Link extends WP_Widget {

public function __construct() {
    $widget_options = array( 
      'classname' => 'MultiDW_LanguageSelector',
      'description' => 'Provides a language changer.',
    );
   parent::__construct( 'MultiD_Link', 'MultiD - Link builder', $widget_options );

  }

public function widget( $args, $instance ) {

  if (isset($instance)) {
  $Content = apply_filters( 'MultiD_Link_Content', array("instance"=>$instance));
  echo $Content;
  }
  
}

public function form( $instance ) {
		global $multid_languages;
		global $multid_default;
		global $MultiDLocales;
		$languages=array($multid_default=>1)+$multid_languages;
		
		$itemtype=!empty($instance['itemtype'])?$instance['itemtype']:'';
		$itemid=!empty($instance['itemid'])?$instance['itemid']:'';
		$lang=!empty($instance['lang'])?$instance['lang']:'';
		$target=!empty($instance['target'])?$instance['target']:'';
  ?>
  	<div>Link to</div>
    <input type="text" id="<?php echo $this->get_field_id( 'itemtype' ); ?>" name="<?php echo (($this->get_field_name( 'itemtype' ))?$this->get_field_name( 'itemtype' ):""); ?>" value="<?php echo esc_attr( $itemtype ); ?>" class="InbosoStyled ww100"/>        
    <div>Language</div>
    <select id="<?php echo $this->get_field_id( 'lang' ); ?>" name="<?php echo (($this->get_field_name( 'lang' ))?$this->get_field_name( 'lang' ):""); ?>" class="InbosoStyled ww100">
    <option value="">-/-</option>
	<?php foreach($languages as $lan) { ?>
    <option value="<?php echo $lan?>" <?php echo ($lang==$lan)?' selected="selected" ':''; ?>><?php echo esc_attr($MultiDLocales[$lan]); ?></option>
    <?php } ?>
    </select> 
   
  <?php  
}

} // END OF CLASS

function Register_MultiD_Link() { 
  register_widget( 'MultiD_Link' );
}
add_action( 'widgets_init', 'Register_MultiD_Link' );

function MultiD_Link_Content($arguments) {
	if (!is_admin()) {
		$instance=$arguments["instance"];
		$keyword=!empty($instance['keyword'])?$instance['keyword']:'';
		$lang=!empty($instance['lang'])?$instance['classname']:'';
		
		return MultiDic($keyword,$lang);
	}
}
add_filter('MultiD_Link_Content', 'MultiD_Link_Content'); ?>