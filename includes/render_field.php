<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

    $key = $field['key'];
    $fname = $field['_name'];
    $show_in_screen = $field['show_in_screen'];
    $minimum = $field['minimum'];
    $maximum = $field['maximum'];
    $acf_mc_attachment_ids = array_filter(explode(',', get_field($fname)));
    if( !empty($acf_mc_attachment_ids) and count($acf_mc_attachment_ids) > 0 ){
        $data = get_posts(array(
            'post__in' => $acf_mc_attachment_ids,
            'post_type' => 'attachment',
            'orderby' => 'post__in',
            'order' => 'ASC',
        ));
    } else {
        $data = array();
    }
?>
<div class="acf-mc-<?php echo $key; ?>" data-minimum="<?php echo $minimum; ?>" data-maximum="<?php echo $maximum; ?>">
    <input type="hidden" name="acf-mc-fields[<?php echo $fname; ?>][]" value="0"/>
    <input type="hidden" name="acf-mc-field-key" value="<?php echo $key; ?>"/>
    <input type="hidden" name="acf-mc-field-name" value="<?php echo $fname; ?>"/>
    <div class="acf-mc-field-group acf-mc-field-group-label">
        <div class="acf-mc-field-column acf-mc-field-column-filename"><label>Filename</label></div>
        <div class="acf-mc-field-column acf-mc-field-column-title"><label>Title</label></div>
        <div class="acf-mc-field-column acf-mc-field-column-action"><label>Action</label></div>
    </div>
    <div class="acf-mc-field-group acf-mc-field-group-container">
        <?php 
            if( count($data) < 1 ){
                acf_mc_cluster_field_group(false, 0, $fname, $key); 
            } else {
                foreach($data as $index => $item){
                    $url = wp_get_attachment_url($item->ID);
                    $title = get_the_title($item->ID);
                    acf_mc_cluster_field_group(false, $item->ID, $fname, $key, $url, $title, true, false, $index+1); 
                }
                acf_mc_cluster_field_group(false, 0, $fname, $key, null, null, false, true, count($data) + 1); 
            }
        ?>
    </div>
</div>