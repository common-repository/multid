<?php 
global $multid_languages;
global $multid_default;
global $MultiDLocales;
$languages=$multid_languages;
$languages=array($multid_default=>1)+$languages;
?>
<div id="multid" class="hh100">
<h1>MultiD Dictionary</h1>
<hr/>
<div id="multid_content" class="hh100">

<div class="table ww100 hh100">
<div class="trow">
    <div class="tcell h10 p5" >

    <div class="size12 clearfix">* Please double click on keyword for options.</div>
    <div class="size12 clearfix">* You may call your translations in text content as <strong>[MultiD keyword="your keyword" lang="language tag as en_US"]</strong>. Lang parameter is optional.</div>
    <div class="size12 clearfix">* If you are developing a team call function <strong>MultiDic('your keyword',languagetag);</strong>Lang parameter is optional. </div>
    <div class="size12 clearfix">* Our plugin registers <strong>MultiDictionary</strong> under widgets.</div>    
        <hr/>
    <form id="multid_dictionary" onsubmit="return false;">
    <label>Search&nbsp;<input type="text" id="searchname" placeholder="place your search" onfocus="this.val=this.value" onkeyup="if (this.value!=this.val) {MultiD_LoadDictionary(this);}"/></label>
    <button class="button" onclick="MultiD_LoadDictionary_Add(this);">Add new keyword</button>
    </form>

    </div>
    
</div> <!-- row -->
<div class="trow">
    <div class="tcell p0 h10">
    <div class="bgtableheader ww100" style="overflow:hidden;width:100%" id="multid_dictionaryheader">
        <div class="multid_row">
            <div class="multid_key hlabel">KEYWORD</div>
            <?php  foreach($languages as $lan=>$val) {   ?>
            <div class="multid_item hlabel"><?php echo $MultiDLocales[$lan]?></div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    
    
    
    </div>
    </div>
</div> <!-- row -->
<div class="trow">
    <div class="tcell p0">
    <div class=" bgwhite mh500 ww100 hh100" style="overflow:auto;width:100%" id="multid_dictionarycontent">
    
    </div>
    </div>
</div> <!-- row -->
</div> <!-- table -->

</div>
</div>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
		   
	   	    MultiD_DictionarySetSize();
			MultiD_LoadDictionary();
			 jQuery('#multid_dictionarycontent').on('scroll', 
			 function () {
			 		jQuery('#multid_dictionaryheader').scrollLeft(jQuery('#multid_dictionarycontent').scrollLeft());
			 });	
			 jQuery(window).on('resize', 
			 function () {
			 		MultiD_DictionarySetSize();
			 });			 		
		   	
    });
	
	function MultiD_DictionarySetSize() {
		
		 jQuery('#multid_dictionaryheader').css("width",jQuery('#multid').parent().width()-20);
		 jQuery('#multid_dictionarycontent').css("width",jQuery('#multid').parent().width()-20);

		}
	

    </script>