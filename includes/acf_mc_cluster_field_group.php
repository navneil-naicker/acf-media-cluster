<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

    $key = "";
	if( !empty($_REQUEST['key']) ){
		$key = $_REQUEST['key'];
	} else if( !empty($pkey) ){
		$key = $pkey;
	}
	$group = (!empty($_REQUEST['group']))?$_REQUEST['group']:'1';
	$fname = (!empty($_REQUEST['fname']))?$_REQUEST['fname']:$fname;
	if( $groupIndex > 0 ){
		$group = $groupIndex;
	}
?>
<div class="acf-mc-field-group acf-mc-field-group-row acf-mc-field-group-<?php echo $group; ?>">
	<div class="acf-mc-field-column acf-mc-field-column-filename">
		<input class="acf-mc-field-filename" type="text" readonly name="filename" value="<?php echo $url; ?>"/>
		<a href="<?php echo $url; ?>" target="_blank" title="View file" class="acf-mc-field-file-viewer"><span class="dashicons dashicons-welcome-view-site"></span></a>
	</div>
	<div class="acf-mc-field-column acf-mc-field-column-title">
		<input class="acf-mc-field-title" type="text" readonly name="title" value="<?php echo $title; ?>"/>
	</div>
	<div class="acf-mc-field-column acf-mc-field-column-action">
		<?php if( $attachment_id > 0 ){ ?>
			<input type="hidden" name="acf-mc-fields[<?php echo $fname; ?>][]" value="<?php echo $attachment_id; ?>"/>
		<?php } ?>
		<a href="#" title="Choose File from Media Library" class="button button-choose-file button-primary" data-key="<?php echo $key; ?>" data-name="<?php echo $fname; ?>" data-group="acf-mc-field-group-<?php echo $group; ?>">Choose File</a>
		<a href="#" title="Edit" class="button button-edit <?php echo ($showEditDel==true)?:'acf-mc-field-hide'; ?>" data-post_id="<?php echo $_GET['post']; ?>" data-key="<?php echo $key; ?>" data-name="<?php echo $fname; ?>" ><span class="dashicons dashicons-edit"></span></a>
		<a href="#" title="Delete" class="button button-delete  <?php echo ($showEditDel==true)?:'acf-mc-field-hide'; ?>" data-key="<?php echo $key; ?>" data-group="acf-mc-field-group-<?php echo $group; ?>"><span class="dashicons dashicons-trash"></span></a>
		<a href="#" title="Add More" class="button button-plus <?php echo ($showAdd==true)?:'acf-mc-field-hide'; ?>" data-key="<?php echo $key; ?>" data-name="<?php echo $fname; ?>" data-group="acf-mc-field-group-<?php echo $group; ?>"><span class="dashicons dashicons-plus"></span></a>
	</div>
</div>
<?php
	if( !empty($_REQUEST['noajax']) and $_REQUEST['noajax'] == true){ 
        die();
    }