<?php
/**
 * Plugin Name:       Lock Reusable Blocks
 * Description:       Lock Reusable Blocks in the editor to avoid unintentional global changes.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Marie Comet
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lock-reusable-blocks
 * Domain Path: /languages/
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'LRB_VERSION' ) ) {
    define( 'LRB_VERSION', '0.0.1' );
}
if ( ! defined( 'LRB_PATH' ) ) {
    define( 'LRB_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'LRB_URL' ) ) {
    define( 'LRB_URL', plugin_dir_url( __FILE__ ) );
}

add_action( 'plugins_loaded', 'lrb_include_files' );
function lrb_include_files() {
    load_plugin_textdomain( 'lock-reusable-blocks', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

function lrb_gutenberg_editor_scripts() {
    wp_enqueue_style(
        'lrb-gutenberg',
        LRB_URL . 'build/style-index.css',
        LRB_VERSION
    );
    wp_register_script(
        'lrb-editor-script',
        LRB_URL . 'build/index.js',
        [ 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ],
        LRB_VERSION
    );
    wp_enqueue_script( 'lrb-editor-script' );
    wp_set_script_translations( 'lrb-editor-script', 'lock-reusable-blocks', LRB_PATH . '/languages/' );
}
add_action( 'enqueue_block_editor_assets', 'lrb_gutenberg_editor_scripts' );