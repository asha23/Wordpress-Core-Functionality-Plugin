<?php

namespace arlo;

final class menus
{
	public function __construct()
	{

	}

	public function listen()
	{

	}

	public function setup_menus()
	{
		if ( has_nav_menu( "main-nav" ) ) {
			wp_nav_menu(array(
				'container' => false,                           // remove nav container
				'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
				'menu' => __( 'The Main Menu', 'SEEDtheme' ),  	// nav name
				'menu_class' => 'nav navbar-nav',  				// adding custom nav class
				'theme_location' => 'main-nav',                 // where it's located in the theme
				'before' => '',                                 // before the menu
				'after' => '',                                  // after the menu
				'link_before' => '',                            // before each link
				'link_after' => '',                             // after each link
				'depth' => 2,                                   // limit the depth of the nav
				'walker' => new wp_bootstrap_navwalker()        // for bootstrap nav
			));
		};

		wp_nav_menu(array(
			'container' => '',                              // remove nav container
			'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
			'menu' => __( 'Footer Links', 'SEEDtheme' ),   	// nav name
			'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
			'theme_location' => 'footer-links',             // where it's located in the theme
			'before' => '',                                 // before the menu
			'after' => '',                                  // after the menu
			'link_before' => '',                            // before each link
			'link_after' => '',                             // after each link
			'depth' => 0,                                   // limit the depth of the nav
			'fallback_cb' => 'seed_footer_links_fallback', 	// fallback function
		));

		wp_page_menu( array(
			'show_home' => true,
			'menu_class' => 'nav top-nav clearfix',      	// adding custom nav class
			'include'     => '',
			'exclude'     => '',
			'echo'        => true,
			'link_before' => '',                            // before each link
			'link_after' => ''                             	// after each link
		));
	}
}
