<?php

namespace state_pricing;

GFForms::include_addon_framework();

class GFSimpleAddOn extends GFAddOn {
    protected $_version = '1.0';
    protected $_min_gravityforms_version = '2.3';
    protected $_slug = 'simpleaddon';
    protected $_path = 'simpleaddon/simpleaddon.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms Simple Add-On';
    protected $_short_title = 'Simple Add-On';

    
    /**
     * @var object|null $_instance If available, contains an instance of this class.
     */
    private static $_instance = null;
    
    /**
     * Returns an instance of this class, and stores it in the $_instance property.
     *
     * @return object $_instance An instance of this class.
     */
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
}