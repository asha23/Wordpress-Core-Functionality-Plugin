<?php

namespace Arlo;

if(!function_exists('\\arlo\\init')) {
    function init()
    { 
        $head_cleanup = new HeadCleanup();
        $head_cleanup->listen();

        $mime_types = new MimeTypes();
        $mime_types->listen();
        
        $acf_improvements = new AcfImprovements();
        $acf_improvements->listen();

        $utilities = new Utilities();
        $utilities->listen();
    }
}
add_action( 'plugins_loaded', '\\arlo\\init' );

?>
