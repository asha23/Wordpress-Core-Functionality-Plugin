<?php

namespace arlo;

/**
 * Bundle the ACF plugin
 *
 * @author Ash Whiting <ash@digitaltactics.co.uk>
 */
final class acf_plugin_bundle
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Listener
     */
    public function listen()
    {
        $this->check_acf();
    }

    /**
     * Check ACF exists
     */
    public function check_acf()
    {
        if (!class_exists('acf')) {
            $this->init();
        } else {
            return;
        }
    }

    /**
     * Initialize ACF
     */
    public function init()
    {
        add_filter('acf/settings/path', [$this, 'acf_settings_path']);
        add_filter('acf/settings/dir', [$this, 'acf_settings_dir']);
        add_filter('acf/settings/show_admin', [$this, 'acf_settings_show_admin']);
        
        include_once( plugin_dir_path(__DIR__) . 'vendor/advanced-custom-fields-pro/acf.php' );
    }

    /**
     * Settings path
     *
     * @param basedir $path
     */
    public function acf_settings_path($path)
    {
        $path = plugin_dir_url(__DIR__)  . 'vendor/advanced-custom-fields-pro/';
        return $path;
    }

    /**
     * Settings directory
     *
     * @param path $dir
     */
    public function acf_settings_dir($dir) 
    {
        $dir = plugin_dir_url(__DIR__)  . 'vendor/advanced-custom-fields-pro/';
        return $dir;
    }

    /**
     * Hide the menu
     *
     * @param boolean $show_admin
     */
    function acf_settings_show_admin( $show_admin ) {
       // return false;
    }
}