<?php
/**
 * Plugin Name:       Lock Reusable Blocks
 * Description:       Lock Reusable Blocks in the editor to avoid unintentional global changes.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.6.0
 * Author:            Marie Comet
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lock-reusable-blocks
 * Domain Path: /languages/
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'LRB_VERSION' ) ) {
    define( 'LRB_VERSION', '0.3.0' );
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

/**
 * Add custom settings in Writing admin screen
 */
add_action( 'admin_init', 'lrb_admin_settings' );
function lrb_admin_settings() {
    add_settings_section(
        'lrb_options_section',
        __( 'Lock Reusable Blocks', 'lrb' ),
        'lrb_options_section_cb',
        'writing'
    );
    register_setting( 'writing', 'hide_convert_reusable_block_button' );
    register_setting( 'writing', 'hide_edit_reusable_block_button' );
    add_settings_field(
        'hide_convert_reusable_block_button', 
        __( 'Hide "Convert to regular blocks" button', 'lrb' ),
        'lrb_options_hide_convert_button_cb',
        'writing',
        'lrb_options_section'
    );
    add_settings_field(
        'hide_edit_reusable_block_button', 
        __( 'Hide "Edit reusable block" button', 'lrb' ),
        'lrb_options_hide_edit_button_cb',
        'writing',
        'lrb_options_section'
    );
}

/**
 * Render custom section in Writing admin screen
 */
function lrb_options_section_cb() {
    printf(
        '<p>%s</p>',
        __( 'Settings related to Lock Reusable Blocks feature', 'lrb' )
    );
}

/**
 * Render checkbox setting in Writing admin screen
 */
function lrb_options_hide_convert_button_cb() {
    printf(
        '<input type="checkbox" id="hide_convert_reusable_block_button" name="hide_convert_reusable_block_button" value="1" %s />',
        get_option('hide_convert_reusable_block_button') == '1' ? 'checked="checked"' : ''
    );
}

function lrb_options_hide_edit_button_cb() {
    printf(
        '<input type="checkbox" id="hide_edit_reusable_block_button" name="hide_edit_reusable_block_button" value="1" %s />',
        get_option('hide_edit_reusable_block_button') == '1' ? 'checked="checked"' : ''
    );
}


/**
 * Enqueue admin scripts
 */
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
    wp_localize_script( 'lrb-editor-script', 'lrb', [
        'hide_convert_button'   => get_option( 'hide_convert_reusable_block_button', '' ),
        'hide_edit_button'      => get_option( 'hide_edit_reusable_block_button', '' )
    ] );
    wp_set_script_translations( 'lrb-editor-script', 'lock-reusable-blocks', LRB_PATH . '/languages/' );
}
add_action( 'enqueue_block_editor_assets', 'lrb_gutenberg_editor_scripts' );