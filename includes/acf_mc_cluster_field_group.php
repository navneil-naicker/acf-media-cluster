<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

    $key = "";
	$url = "";
	$title = "";

	if( !empty($_REQUEST['key']) ){
		$key = preg_replace("/[^a-z0-9_]/", "", $_REQUEST['key']);
	} else if( !empty($pkey) ){
		$key = preg_replace("/[^a-z0-9_]/", "", $pkey);
	}
	$group = (!empty($_REQUEST['group']))?preg_replace("/[^0-9]/", "",$_REQUEST['group']):1;
	$fname = (!empty($_REQUEST['fname']))?preg_replace("/[^a-z0-9_]/", "",$_REQUEST['fname']):preg_replace("/[^a-z0-9_]/", "",$fname);
	if( $groupIndex > 0 ){
		$group = $groupIndex;
	}
	if($attachment_id > 0){
		$url = wp_get_attachment_url($attachment_id);
		$title = get_the_title($attachment_id);
	}
?>
<div class="acf-mc-container acf-mc-field-group acf-mc-field-group-<?php echo $group; ?>">
	<div class="acf-mc-field-column-filename">
		<input class="acf-mc-field-filename" type="text" readonly name="filename" value="<?php echo sanitize_text_field(basename($url)); ?>"/>
	</div>
	<div class="acf-mc-field-column-title">
		<input class="acf-mc-field-title" type="text" readonly name="title" value="<?php echo sanitize_text_field($title); ?>"/>
	</div>
	<div class="acf-mc-field-column-action">
		<a
			href="#"
			title="Choose File from Media Library"
			class="button button-choose-file"
			data-key="<?php echo preg_replace('/[^a-z0-9_]/', '', $key); ?>"
			data-name="<?php echo preg_replace('/[^a-z0-9_]/', '', $fname); ?>"
			data-group="<?php echo preg_replace("/[^0-9]/", "", $group); ?>"
		>
			<span class="dashicons dashicons-cloud-upload"></span>
		</a>
		<a
			href="#"
			title="Edit"
			class="button button-edit <?php echo ($showEditDel == true)?:'acf-mc-field-hide'; ?>"
			data-attachment_id="<?php echo preg_replace('/[^0-9]/', '', $attachment_id); ?>"
			data-key="<?php echo preg_replace('/[^a-z0-9_]/', '', $key); ?>"
			data-name="<?php echo preg_replace('/[^a-z0-9_]/', '', $fname); ?>" >
			<span class="dashicons dashicons-edit"></span>
		</a>
		<a
			href="#"
			title="Delete"
			class="button button-delete <?php echo ($showEditDel == true)?:'acf-mc-field-hide'; ?>"
			data-key="<?php echo preg_replace('/[^a-z0-9_]/', '', $key); ?>"
			data-group="<?php echo $group; ?>"
		>
			<span class="dashicons dashicons-trash"></span>
		</a>
		<a
			href="#"
			title="Add More"
			class="button button-plus <?php echo ($showAdd == true)?:'acf-mc-field-hide'; ?>"
			data-key="<?php echo preg_replace('/[^a-z0-9_]/', '', $key); ?>"
			data-name="<?php echo preg_replace('/[^a-z0-9_]/', '', $fname); ?>"
			data-group="<?php echo preg_replace("/[^0-9]/", "",$group); ?>"
		>
			<span class="dashicons dashicons-plus"></span>
		</a>
		<input
			type="hidden"
			name="acf-mc-fields[<?php echo preg_replace('/[^a-z0-9_]/', '', $fname); ?>][]"
			value="<?php echo $attachment_id; ?>"
		/>
	</div>
</div>
<?php
	if( !empty($_REQUEST['noajax']) and $_REQUEST['noajax'] == true){ 
        die();
    }