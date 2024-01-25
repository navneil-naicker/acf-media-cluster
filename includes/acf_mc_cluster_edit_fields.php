<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

	if( empty($_GET['attachment_id']) or empty($_GET['acf-mc-key']) or empty($_GET['acf-mc-name']) ) return;

	$post_id = preg_replace('/\D/', '', $_GET['post_id']);
    $acf_mc_key = preg_replace('/[^a-z0-9_]/', '', $_GET['acf-mc-key']);
	$acf_mc_name = preg_replace('/[^a-z0-9_]/', '', $_GET['acf-mc-name']);
	$id = preg_replace('/\D/', '', $_GET['attachment_id']);
	$item = get_post($id);

	if( empty($item) ) return;

	$url = wp_get_attachment_url($item->ID);
	$alt_text = get_field('_wp_attachment_image_alt', $item->ID);
	$title = get_the_title($item->ID);
	$caption = get_the_excerpt($item->ID);
	$description = get_the_content(null, false, $item->ID);
?>
<div class="acf-mc-modal-cotaniner-content">
	<?php
		$tables = array();
		$post = array(
			'acf_field_key' => $acf_mc_key,
			'acf_field_name' => $acf_mc_name,
			'post_id' => $post_id,
			'attachment_id' => $id
		);
		$defaults = array(
			array(
				'label' => 'Alt Text',
				'control' => array('table' => 'postmeta', 'type' => 'text', 'name' => '_wp_attachment_image_alt', 'value' => esc_attr($alt_text))
			),
			array(
				'label' => 'Title',
				'control' => array('table' => 'posts', 'type' => 'text', 'name' => 'post_title', 'value' => esc_attr($title))
			),
			array(
				'label' => 'Caption',
				'control' => array('table' => 'posts', 'rows' => 3, 'type' => 'textarea', 'name' => 'post_excerpt', 'value' => esc_attr($caption))
			),
			array(
				'label' => 'Description',
				'control' => array('table' => 'posts', 'rows' => 3, 'type' => 'textarea', 'name' => 'post_content', 'value' => esc_html($description))
			)
		);

		$fields = apply_filters('acf/media-cluster-edit-fields', $post, $defaults);

		$html = '';
		
		if( !empty($fields) ){
			foreach( $fields as $attr ){
				$html .= '<div class="acf-mc-modal-container-controls">';
				if( !empty($attr['label']) ){
					$html .= "<label>" . esc_attr($attr['label']) . "</label>";
				}
				if( !empty($attr['control']) ){
					$c = "";
					foreach($attr['control'] as $a => $b){
						if($a == "table"){
							$tables[$attr['control']['name']] = $b;
						}
						if( ($attr['control']['type'] == "textarea" and $a == "value") or $a == "table" ){
							continue;
						}
						$c .= esc_attr($a) . ' = "' . esc_attr($b) . '"';
					}
					if( $attr['control']['type'] == "text" ){
						$html .= '<input ' . $c . '/>';
					} else if( $attr['control']['type'] == "textarea" ){
						$html .= '<textarea ' . $c . '>' . esc_html($attr['control']['value']) . '</textarea>';
					}
				}
				$html .= '</div>';
			}
		}

	?>
	<?php if( empty($html) ) return; ?>
	<form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
		<input type="hidden" name="action" value="acf_mc_cluster_edit_save_field"/>
		<input type="hidden" name="post_id" value="<?php echo preg_replace('/[^0-9]/', '', $id); ?>"/>
		<input type="hidden" name="acf-mc-field-key" value="<?php echo preg_replace('/[^a-z0-9_]/', '', $acf_mc_key); ?>"/>
		<input type="hidden" name="acf-mc-field-name" value="<?php echo preg_replace('/[^a-z0-9_]/', '', $acf_mc_name); ?>"/>
		<textarea name="tables" style="display:none;"><?php echo wp_json_encode($tables); ?></textarea>
		<h1 class="acf-mc-modal-cotaniner-header">Edit</h1>
		<?php echo $html; ?>
		<div class="acf-mc-modal-container-controls">
			<button type="submit" class="button-primary">Save Changes</button>
			<button type="button" class="button acf-mc_modal-close">Close</button>
		</div>
	</form>
</div>
<?php
