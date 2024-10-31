<style>
.multid_row { min-width:<?php echo ((count($languages)+1)*200)?>px}
</style>
<div class="multid_dic_bottom">
<?php
$x=0;foreach($result as $row) { $x++;
?>
<div class="multid_row <?php echo ($x%2==0)?"b":""; ?>">
    <div class="multid_key"><div class="multid_editable" id="multi_key<?php echo $x;?>" ondblclick="MultiD_placedictionaryeditor($O('multi_key<?php echo $x;?>').innerHTML,$O('multi_key<?php echo $x;?>').innerHTML,$O('multi_key<?php echo $x;?>'));"/><?php  echo $row->id; ?></div></div>
    <?php  foreach($languages as $lan=>$val) {   ?>
    <div class="multid_item"><textarea id="<?php echo $lan?>" onfocus="this.val=this.value" onkeyup=" if (this.val!=this.value) MultiD_updateDictionary(this,'<?php  echo esc_js($row->id); ?>');"><?php  echo esc_attr($row->{$lan}); ?></textarea></div>
    <?php } ?>
    <div class="clearfix"></div>
</div>
<?php } ?>
</div>