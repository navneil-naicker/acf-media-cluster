<?php

    // exit if accessed directly
    if( ! defined( 'ABSPATH' ) ) exit;

    $key = preg_replace('/[^a-z0-9_]/', '', $field['key']);
    $fname = preg_replace('/[^a-z0-9_]/', '', $field['_name']);
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
<div class="acf-mc-<?php echo preg_replace('/[^a-z0-9_]/', '', $key); ?>">
    <div class="acf-mc-container header acf-mc-field-group acf-mc-field-group-label">
        <div class="acf-mc-field-column acf-mc-field-column-filename">Filename</div>
        <div class="acf-mc-field-column acf-mc-field-column-title">Title</div>
        <div class="acf-mc-field-column acf-mc-field-column-action">Action</div>
    </div>
    <div class="acf-mc-field-group-container">
        <?php 
            if(!empty($data) and count($data) < 1){
                acf_mc_cluster_field_group(false, 0, $fname, $key); 
            } else {
                foreach($data as $index => $item){
                    acf_mc_cluster_field_group(false, $item->ID, $fname, $key, true, false, $index + 1); 
                }
                acf_mc_cluster_field_group(false, 0, $fname, $key, false, true, count($data) + 1);
            }
        ?>
    </div>
</div>