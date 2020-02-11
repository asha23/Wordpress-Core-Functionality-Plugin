<?php

namespace arlo;

if(!function_exists('\\arlo\\init')) {
    function init()
    {
        /**
		 * Bundle ACF Plugin
		 */
		$acf_plugin = new acf_plugin();
        $acf_plugin->listen();
        
        $core = new core();
        $core->listen();

        $custom_post_types = new custom_post_types();
        $custom_post_types->listen();
        
        $acf_improvements = new acf_improvements();
        $acf_improvements->listen();

        $utilities = new utilities();
        $utilities->listen();
    }
}
add_action( 'plugins_loaded', '\\arlo\\init' );


?>
