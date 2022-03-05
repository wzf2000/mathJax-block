<?php
/**
 * Plugin Name: mathJax-block
 * Plugin URI: https://wzf2000.top
 * Description: 用于在区块编辑器中使用数学公式
 * Version: 1.0.0
 * Author: wzf2000
 * Author URI: http://wzf2000.top
 */

namespace MathJax_Blocks;

if ( ! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

class MathJax_Gutenberg {
    public $version = '1.0.0';

    private static $_instance = null;

    /**
     * Instance
     * Ensures only one instance of the class is loaded or can be loaded.
     * @since 1.0.0
     * @access public
     * @static
     * @return MathJax_Gutenberg An instance of the class.
     */
    public static function instance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!function_exists('register_block_type'))
            return;
        $this->includes();
        $this->register_block_category();
        add_action('init', [$this, 'load_modules']);
    }

    public function includes() {
        require_once( __DIR__ . '/includes/mathJax-block-functions.php' );
    }

    public function register_block_category() {
        add_filter( 'block_categories_all', array( $this, 'mb_block_categories' ), 10, 2 );
    }

    public function mb_block_categories( $categories, $post ) {
        return array_merge(
            $categories, 
            array(
                array(
                    'slug'  => 'mathJax-block',
                    'title' => 'mathJax Blocks',
                ),
            )
        );
    }

    public function load_modules() {
        $modules = [
            'mathjax-display'
        ];

        foreach ($modules as $module) {
            $module_file = __DIR__ . "/blocks/$module/$module.php";
            if ( file_exists( $module_file ) )
				require_once $module_file;
			else
				_doing_it_wrong( 'MathJax_Gutenberg->load_modules()', 'Requested file ' . $module_file . ' not found.', $version );
        }
    }
}

MathJax_Gutenberg::instance();