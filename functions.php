<?php

namespace arlo;

if(!function_exists('\\arlo\\init')) {
    function init()
    {
        $core = new core();
        $core->listen();

        $menus = new menus();
        $menus->listen();

        $custom_post_types = new custom_post_types();
        $custom_post_types->listen();
    }
}

add_action( 'wp_loaded', '\\arlo\\init' );


?>
