<?php


function MultiD_DrawLanguageSelector($post) {
global $MultiDLocales;
global $multid_languages;
global $multid_default;
global $multid_postdesc;
$languages=$multid_languages;
$languages[$multid_default]=1;

?>

<div class="settinghead">
<select "multid_languageselect" onchange="MultiD_ChangeSettingsLan(this.value);">
<option value="<?php echo $multid_default?>" selected="selected"><?php echo $MultiDLocales[$multid_default]; ?> (Default)</option>
<?php foreach($multid_languages as $key=>$val) { ?>
<option value="<?php echo $key?>"><?php echo $MultiDLocales[$key]; ?></option>
<?php } ?>
</select>
</div>

<div class="MultiD_textcontainer">
<?php $x=0; foreach($languages as $key=>$val) { $x++; 
	  ?> 
    <div class="multid_Tabcontainer <?php echo $key;?>" id="<?php echo "multidiv_slug-".$key;?>" style="display:<?php echo ($multid_default!=$key)?"none":"";?>">
    
    <div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Title</span></div>
        <div class="SettingContent">
           <input type="text" id="<?php echo "multid_title-".$key?>" name="<?php echo "multid_title-".$key?>" class="ww100" placeholder="Title <?php $Key?>" value="<?php echo ($multid_default!=$key)?esc_attr(multid_Meta($post->ID,"multid_title-".$key,'')):$post->post_title;?>"/>
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>


<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Slug</span></div>
        <div class="SettingContent">
           <div style="width:20%; float:left">
           <div style="padding-right:5px">
           <input type="text" id="<?php echo "multid_root-".$key?>" name="<?php echo "multid_root-".$key?>" class="ww100" placeholder="RootFolder. Default /[lan]/" value="<?php echo ($multid_default!=$key)?esc_attr(multid_Meta($post->ID,"multid_root-".$key,'')):'';?>"/>
           </div>
           </div>   
           <div style="width:80%; float:left">
           <div style="padding-right:5px">    
           <input type="text" id="<?php echo "multid_slug-".$key?>" name="<?php echo "multid_slug-".$key?>" class="ww100" placeholder="slug <?php $Key?>. if empty will be created from title-" value="<?php echo ($multid_default!=$key)?esc_attr(multid_Meta($post->ID,"multid_slug-".$key,'')):$post->post_name;?>"/>
           </div>
           </div>   
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>
<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Content</span></div>
        <div class="SettingContent">

           <?php  wp_editor( ($multid_default!=$key)?multid_Meta($post->ID,"multid_postcontent-".$key,''):$post->post_content,"multid_postcontent-".$key, array("language"=>$key,"textarea_rows"=>15) ); ?> 
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>
    
  <?php if ($multid_postdesc) { ?>
<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Desc"><span>Site desciption</span></div>
        <div class="SettingContent">
           <?php  wp_editor( multid_Meta($post->ID,"multid_postdesc-".$key,''),"multid_postdesc-".$key, array("language"=>$key,"textarea_rows"=>5) ); ?> 
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>
<?php  }?>
  
    </div>
<?php } ?>
</div>
<?php
 }
 
function MultiD_DrawLanguageSelectorTerm($term) {
	
global $MultiDLocales;
global $multid_languages;
global $multid_default;
global $multid_postdesc;
$languages=$multid_languages;
$languages[$multid_default]=1;

?>
<div class="settinghead">
<select "multid_languageselect" onchange="MultiD_ChangeSettingsLan(this.value);">
<option value="<?php echo $multid_default?>" selected="selected"><?php echo $MultiDLocales[$multid_default]; ?> (Default)</option>
<?php foreach($multid_languages as $key=>$val) { ?>
<option value="<?php echo $key?>"><?php echo $MultiDLocales[$key]; ?></option>
<?php } ?>
</select>
</div>

<div class="MultiD_textcontainer">
 <?php 
	$terms = get_terms( array(
    'taxonomy' => $term->taxonomy,
    'hide_empty' => false,
	'exclude' => array($term->term_id)
) );

    ?>
    <div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel p5"><span>Parent</span></div>
        <div class="SettingContent">
           <select id="parent" name="parent">
           <option value="0" <?php echo ($term->parent==0)?' selected="selected" ':'';?>>None</option>
           <?php foreach($terms as $t) {?>
           <option value="<?php echo $t->term_id;?>" <?php echo ($term->parent==$t->term_id)?' selected="selected" ':'';?>><?php echo esc_attr($t->name);?></option>
           <?php } ?>
           </select>
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>

<?php $x=0; foreach($languages as $key=>$val) { $x++; 
	  ?> 
    <div class="multid_Tabcontainer <?php echo $key;?>" id="<?php echo "multidiv_slug-".$key;?>" style="display:<?php echo ($multid_default!=$key)?"none":"";?>">
    
   
    
    <div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Name</span></div>
        <div class="SettingContent">
           <input type="text" id="<?php echo "multid_title-".$key?>" name="<?php echo "multid_title-".$key?>" class="ww100" placeholder="Title <?php $Key?>" value="<?php echo ($multid_default!=$key)?esc_attr(multid_TermMeta($term->term_id,"multid_title-".$key,'')):$term->name;?>"/>
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>


<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Slug</span></div>
        <div class="SettingContent">
           <input type="text" id="<?php echo "multid_slug-".$key?>" name="<?php echo "multid_slug-".$key?>" class="ww100" placeholder="slug <?php $Key?>. if empty will be created from title-" value="<?php echo ($multid_default!=$key)?esc_attr(multid_TermMeta($term->term_id,"multid_slug-".$key,'')):$term->slug;?>"/>
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>

<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Youtube"><span>Description</span></div>
        <div class="SettingContent">
           <?php 
		   wp_editor( ($multid_default!=$key)?multid_TermMeta($term->term_id,"multid_postcontent-".$key,''):$term->description,"multid_postcontent-".$key, array("textarea_rows"=>15) ); ?> 
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>
    
    
    
<?php if ($multid_postdesc) { ?>
<div class="SettingRow">
    <div class="SettingCol">
        <div class="SettingLabel Desc"><span>Site desciption</span></div>
        <div class="SettingContent">
           <?php  wp_editor( multid_TermMeta($term->term_id,"multid_postdesc-".$key,''),"multid_postdesc-".$key, array("language"=>$key,"textarea_rows"=>5) ); ?> 
           <div class="clearfix"></div>
        </div>
    <div class="clearfix"></div></div>  
<div class="clearfix"></div>
</div>
<?php  }?>
</div>    
<?php } ?>
</div>
    <script type="text/javascript">
	   	   jQuery('.term-name-wrap').parent().remove();
//		       jQuery(document).ready( function($) {

  //  			});
    </script>
<?php } ?>
