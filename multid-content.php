<?php function MultiD_Content($inline=null) { 
global $MultiDLocales;
global $multid_default;
global $multid_languages;
global $multid_posts;
global $multid_taxonomies;
global $multid_fields;
global $multid_forcerewrite;
global $multid_forcecontent;
global $multid_hreflang;
global $multid_canon;
global $multid_postdesc;
global $multid_sitemap;
global $multid_sitemapslug;
?>

<form id="multid_settings" onSubmit="return false;">
<input type="hidden" value="MultiD_SaveSettings" id="action" name="">
<div class="table ww100 mw800">
    <?php if (MultiD_CheckDB()) { ?>
    <div class="trow">
        <div class="tcell label">
        Default language
        </div>
        <div class="tcell">
        <select id="multid_default" class="ww100">
        <?php
		
        $translations = $MultiDLocales;
		
		foreach($translations as $locale=>$lan) {?>
        <option value="<?php echo esc_attr($locale);?>" <?php echo (($multid_default&&$multid_default==$locale)||(!$multid_default&&$locale==get_locale()))?' selected="selected" ':''; ?>><?php echo esc_attr($locale); ?> => <?php echo esc_attr($lan); ?></option>
        <?php } ?>
        </select>
        </div>        
    </div><!-- trow -->
    <?php if ($multid_default) { ?>
    
    <div class="trow">
        <div class="tcell label">
        Additional languages
        </div>
        <div class="tcell">
            <div class="ww100 h200 border bgwhite" style="overflow-y: auto; overflow-x: hidden;">
                <div class="p5">
                <?php
                foreach($translations as $locale=>$lan) {?>
                <div class="ww25 left ohidden nofloat100">
                <label class="nowrap"><input type="checkbox" id="multid_languages[]" value="<?php echo esc_attr($locale);?>" <?php echo ($multid_languages&&isset($multid_languages[$locale]))?' checked="checked" ':''?>/><?php echo esc_attr($lan); ?> </label></div>
                
                <?php } ?>
                </div>           
            </div>
        </div>       
    </div><!-- trow -->   
    <?php if ($multid_languages) { ?>
    
    <div class="trow">
        <div class="tcell label">
        Post types
        </div>
        <div class="tcell">
        <div class="ww100 h100 border bgwhite" style="overflow-y: auto; overflow-x: hidden;">
                <div class="p5">
                <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_posts[]" value="post" <?php echo ($multid_posts&&isset($multid_posts['post']))?' checked="checked" ':''?>/>post (default)</label></div>
                <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_posts[]" value="page" <?php echo ($multid_posts&&isset($multid_posts['page']))?' checked="checked" ':''?>/>page (default)</label></div>
                <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_posts[]" value="attachment" <?php echo ($multid_posts&&isset($multid_posts['attachment']))?' checked="checked" ':''?>/>attachment (default)</label></div>
        <?php $pt=get_post_types(array('public'   => true,'_builtin' => false)); 
		foreach ($pt as $p) { ?>
        <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_posts[]" value="<?php echo esc_attr($p);?>" <?php echo ($multid_posts&&isset($multid_posts[$p]))?' checked="checked" ':''?>/><?php echo esc_attr($p); ?></label></div>
        <?php } ?>
        </div>           
            </div>
        </div>        
    </div><!-- trow -->
     
    <div class="trow">
        <div class="tcell label">
        Taxonomies
        </div>
        <div class="tcell">
        <div class="ww100 h100 border bgwhite" style="overflow-y: auto; overflow-x: hidden;">
                <div class="p5">
                <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_taxonomies[]" value="category" <?php echo ($multid_taxonomies&&isset($multid_taxonomies['category']))?' checked="checked" ':''?>/>category (default)</label></div>
        <?php $pt=get_taxonomies(array('public' => true,'_builtin' => false)); 
		foreach ($pt as $p) { ?>
        <div class="ww25 left ohidden nofloat100"><label class="nowrap"><input type="checkbox" id="multid_taxonomies[]" value="<?php echo esc_attr($p);?>"  <?php echo ($multid_taxonomies&&isset($multid_taxonomies[$p]))?' checked="checked" ':''?>/><?php echo esc_attr($p); ?></label></div>
        <?php } ?>
        </div>           
            </div>
        </div>        
    </div><!-- trow -->
    <div class="trow">
        <div class="tcell label">
        Force rewrite
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap"><input type="checkbox" id="multid_forcerewrite" value="1" <?php echo ($multid_forcerewrite==1)?' checked="checked" ':''?>/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">This option force system to rewrite url under /languagefolder/ even there is not any translated slug.<br />
                Activating this option may cause publishing content in different language then shown in /languagefolder/.<br />
(not recommended)</div>
                
            </div>
             
    </div><!-- trow --> 
    </div>
    <h2>SEO settings</h2>
    <hr/>
    <div class="table mw800"> 
    <div class="trow">
        <div class="tcell label">
        Provide hreflang tags for header.
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap"><input type="checkbox" id="multid_hreflang" value="1" <?php echo ($multid_hreflang==1)?' checked="checked" ':''?>/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">
                <strong>hreflang meta tags</strong> provide information to browser if there is another location for content in diffent language. <br />
				For example, if you a post named "places to see" and you have content in English and Dutch. Then below tags will be inserted to your header. This will disable canonical redirections to forward visitors to content in their language. 
                <hr>
               	&lt;link rel="alternate" hreflang="en_US" href="/en/places-to-see"&gt;<br />
				&lt;link rel="alternate" hreflang="nl_NL" href="/nl/plaatsen-om-te-zien"&gt;
                </div>
                
            </div>
             
    </div><!-- trow -->
    <div class="trow">
        <div class="tcell label">
        Content description
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap"><input type="checkbox" id="multid_postdesc" value="1" <?php echo ($multid_postdesc==1)?' checked="checked" ':''?>/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">
                	Enabling this option will provides an additional text box for all posts and taxonomies to fill meta tag description in selected language to increase seo score.
                </div>
                
            </div>
             
    </div><!-- trow -->
    <div class="trow">
        <div class="tcell label">
        Sitemap
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap"><input type="checkbox" id="multid_sitemap" value="1" <?php echo ($multid_sitemap==1)?' checked="checked" ':''?>/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">
                	Enabling this option will provide you a dynamic sitemap at [your domain]/multid_sitemap
                </div>
                
            </div>
             
    </div><!-- trow -->  
    <div class="trow">
        <div class="tcell label">
        Sitemap slug
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap">sitedomain/<input type="edit" id="multid_sitemapslug" value="<?php echo ($multid_sitemapslug);?>"  placeholder="default is sitemap.xml"/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">
                	You can change the slug of xml sitemap here
                </div>
                
            </div>
             
    </div><!-- trow -->                     
    <div class="trow">
        <div class="tcell label">
        Content Behaviour
        </div>
        <div class="tcell">
        <div class="p5">
        
                <div class="ww100 ohidden"><label class="nowrap"><input type="checkbox" id="multid_forcecontent" value="1" <?php echo ($multid_forcecontent==1)?' checked="checked" ':''?>/></label></div>
                </div>
 
                <div class="p5 bgwarning2 border size11">
                <strong>Disabled :</strong> Post content and Term descriptions will return empty string if there not translation. (Recommended) <br />
                <strong>Enabled :</strong> Post content and Term descriptions will return default content if there not translation.
                <hr>
                &middot; Titles will not be effected.<br />
                &middot; Enabling this option may cause duplicate content issues for SEO.
                </div>
                
            </div>
             
    </div><!-- trow -->       
    <?php  } else { MultiD_message("Please select additional languages."); } ?> 
    <?php } else { MultiD_message("Please confirm your default language to continue."); } ?> 
	<div class="trow">
        <div class="tcell label">
        &nbsp;
        </div>
        <div class="tcell">
        <input type="button" class="button" value="Update settings" onClick="MultiD_SaveSettings(this);"/>
        </div>        
    </div>
    <?php } else {  MultiD_message('<span class="bold">Setup DB</span><br/>MultiD requires 2 tables to be created in your db. Please confirm to continue.<br/><input type="button" class="button cold" value="Confirm" onclick="Multid_AjaxCreateDB(this);"/>'); ?>
	
	<?php } ?>                 
</div><!-- tbl -->
</form>

<?php if (!$inline) die("");}

function MultiD_message($msg) { ?>
<div class="trow">
        <div class="tcell label">
        &nbsp;
        </div>
        <div class="tcell">
        <div class="bgwarning p5 border">
        <?php echo $msg;?>
        </div>
        </div>        
    </div>
<?php } 

function MultiD_SaveSettings() {

	update_option('multid_default',MultiD_PostVar('multid_default',null));
	
	update_option('multid_forcecontent',(int) MultiD_PostVar('multid_forcecontent',0)); 
	update_option('multid_postdesc',(int) MultiD_PostVar('multid_postdesc',0)); 
	update_option('multid_xmlmap',(int) MultiD_PostVar('multid_xmlmap',0)); 
	    update_option('multid_forcerewrite',(int) MultiD_PostVar('multid_forcerewrite',0)); 
		$languages=MultiD_PostVar('multid_languages');
		if ($languages) { 
			$arr=array(); $codes=array();		
			foreach($languages as $item) { $arr[$item]=1; $c=explode('_',$item); $codes[$c[0]]=1;}
			$oldlanguages=get_option('multid_languages');
			if ($oldlanguages!=$languages) {
				update_option('multid_languageschanged',1);
				update_option('multid_oldlanguages',$oldlanguages?$oldlanguages:array());
				}
			
			update_option('multid_languages',$arr);
			update_option('multid_lancodes',$codes);
		}
	
		$posts=MultiD_PostVar('multid_posts');
		if ($posts) { 
			$arr=array();		
			foreach($posts as $item) $arr[$item]=1;
			update_option('multid_posts',$arr);
		}
		
		$terms=MultiD_PostVar('multid_taxonomies');
		if ($terms) { 
			$arr=array();		
			foreach($terms as $item) $arr[$item]=1;
			update_option('multid_taxonomies',$arr);
		}		
		
		 update_option('multid_linkschanged',1);
		 MultiD_LoadGlobals();

}

add_action( 'wp_ajax_MultiD_SaveSettings', 'MultiD_SaveSettings' );	
add_action( 'wp_ajax_MultiD_Content', 'MultiD_Content' );