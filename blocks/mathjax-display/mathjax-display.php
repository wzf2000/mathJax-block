<?php
namespace MathJax_Blocks;

use Error;

if ( ! defined('ABSPATH') ) exit;

if ( ! class_exists('mathJax_display_block') ) :
    class mathJax_display_block {

        public function __construct() {
            add_action( 'enqueue_block_editor_assets', array( $this, 'register_assets' ) );

            $block = register_block_type( 
                'mathjax-block/mathjax-display', 
                array( 'render_callback' => array( $this, 'render_block' ) )
            );
            // error_log("block is registered!" . $block->title);
        }

        public function register_assets() {
            wp_add_inline_script('MathJaxConfig', 'MathJax.Hub.Config({
                showProcessingMessages: false,
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\\\(", "\\\\)"]],
                    processEscapes:true
                },
                menuSettings: {
                    zoom: "Hover"
                }
            });');
            wp_enqueue_script('MathJax', 'https://uoj.ac/js/MathJax-2.7.9/MathJax.js?config=TeX-AMS_HTML');
            wp_enqueue_script(
                'mathjax-display', 
                plugins_url('index.js', __FILE__), 
                array( 
                    'wp-blocks', 
                    'wp-element', 
                    'wp-components', 
                    'wp-editor', 
                    'wp-rich-text' 
                )
            );
        }
        
        public function render_block( $attributes, $content ) {
            return $content;
        }
    }
endif;

new mathJax_display_block();