<?php

namespace arlo;

if(!function_exists('\\arlo\\init')) {
    function init()
    { 
        $head_cleanup = new head_cleanup();
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
