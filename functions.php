<?php

namespace arlo;

if(!function_exists('\\arlo\\init')) {
    function init()
    {
	    $acf_plugin_bundle = new acf_plugin_bundle();
        $acf_plugin_bundle->listen();
        
        $head_cleanup = new core();
        $head_cleanup->listen();

        $mime_types = new mime_types();
        $mime_types->listen();
        
        $acf_improvements = new acf_improvements();
        $acf_improvements->listen();

        $utilities = new utilities();
        $utilities->listen();
    }
}
add_action( 'plugins_loaded', '\\arlo\\init' );


?>
