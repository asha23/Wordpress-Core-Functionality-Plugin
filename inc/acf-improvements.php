<?php

// Add Flexible Content layout title

function my_layout_title($title, $field, $layout, $i)
{
    if ($value = get_sub_field('layout_title')) {
        return $value;
    } else {
        foreach ($layout['sub_fields'] as $sub) {
            if ($sub['name'] == 'layout_title') {
				if($sub['key']):
					$key = $sub['key'];
				endif;
                // if (array_key_exists($i, $field['value']) && $value = $field['value'][$i][$key]) {
                //     return $value;
                // }
            }
        }
    }

    return $title;
}

add_filter('acf/fields/flexible_content/layout_title', 'my_layout_title', 10, 4);

add_filter('acf/settings/remove_wp_meta_box', '__return_true');


// Google maps API

function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyDMW59kxun73jWTdoyRyvnDVZCKZL50M5s');
}

    add_action('acf/init', 'my_acf_init');


// ACF Custom Colours

if(is_admin()):
    function my_acf_admin_head() {
    ?>
    <style type="text/css">

        .acf-flexible-content .layout .acf-fc-layout-handle {
            /*background-color: #00B8E4;*/
            background-color: #666;
            color: white;
            font-weight:bold;
            border-top-left-radius:10px;
            border-top-right-radius:10px;
        }

        .acf-repeater.-row > table > tbody > tr > td,
        .acf-repeater.-block > table > tbody > tr > td {
            border-top: 2px solid #202428;
        }

        .acf-repeater .acf-row-handle {
            vertical-align: top !important;
            padding-top: 16px;
        }

        .acf-repeater .acf-row-handle span {
            font-size: 20px;
            font-weight: bold;
            color: #202428;
        }

        .imageUpload img {
            width: 75px;
        }

        .acf-repeater .acf-row-handle .acf-icon.-minus {
            top: 30px;
        }

        .acf-flexible-content .layout {
            border:none;
            background:#FDFAFA;
            border-bottom-left-radius:10px;
            border-bottom-right-radius:10px;
        }

        .acf-fields {
            padding:0;
            border:none;
            background:transparent;
        }

        .acf-fields>.acf-tab-wrap .acf-tab-group {
            background:white;
        }

        .acf-fields>.acf-tab-wrap .acf-tab-group li.active a {
            background:#FDFAFA;
        }

        .acf-fields.-border {
            border:none;
        }

        .acf-button {
            background:#666!important;
            color:white!important;
            text-shadow:none!important;
            box-shadow:none!important;
        }

        .acf-button-group label.selected {
            background:#666!important;
            color:white!important;
            border:#666;
        }
		
		.acf-label .description {
			padding:8px;
			background:red;
			color:white;
		}

    </style>
    <?php
    }

    add_action('acf/input/admin_head', 'my_acf_admin_head');
endif;