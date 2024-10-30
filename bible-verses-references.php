<?php
/*
    Plugin Name: Bible Verses References
    Version: 1.1.2
    Description: This plugin adds a popup to all bible references present in the content.
    Author: Klaylton Fernando
    Author URI: https://github.com/klaylton
    Plugin URI: https://blogdosemeador.com
    License: GPL-2.0+
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    Requires at least: 5.0
    Requires PHP: 7.0
    Text Domain: bible-verses-refs
    Domain Path: /languages
*/

    // If this file is called directly, abort.
    if ( ! defined( 'WPINC' ) ) {
        die;
    }

    require plugin_dir_path( __FILE__ ) . 'includes/bible-verses-refs-activate.php';
    require plugin_dir_path( __FILE__ ) . 'includes/bible-verses-refs-deactivate.php';
    require plugin_dir_path( __FILE__ ) . 'includes/bible-verses-refs-settings.php';

    define( 'BIBLE_VERSES_REFERENCES_VERSION', '1.1.2' );

class Bible_Verses_References {

    private static $instance;

    private $plugin_name = 'Bible Verses References';
 
    private function __construct() {

        register_activation_hook(__FILE__, 'bible_verses_references_activate' );
        register_deactivation_hook(__FILE__, 'bible_verses_references_deactivate' );
        add_filter( 'the_content', array( $this, 'main' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_scripts' ) );
        // add_shortcode( 'tooltip', array( $this, 'create_tooltip_shortcode' ) ); temp
        
    }
 
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function enqueue_custom_scripts() {
        $translation = get_option( 'bible_verses_refs_settings' )['translations'];
        $data = array(
            'translation' => $translation,
            'await' => __('Loading...', 'bible-verses-refs')
        );
        wp_enqueue_style( 'bible-verses-refs-css', plugin_dir_url( __FILE__ ) . 'includes/css/bible-verses-refs.css', array(), BIBLE_VERSES_REFERENCES_VERSION, 'all' );
        wp_enqueue_script( 'bible-verses-refs-js', plugin_dir_url( __FILE__ ) . 'includes/js/bible-verses-refs.js', array(), BIBLE_VERSES_REFERENCES_VERSION, true );
        wp_localize_script( 'bible-verses-refs-js', 'bible_verses_refs', $data );
    }

    public function main( $content ){

        $reg = '/((?:(?:[123]|I{1,3})\s*)?(?:[A-Z][A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ]+|Ct|Cc).?\s*(?:1?[0-9]?[0-9])(:|\.)\s*\d{1,3}(?:[,-]\s*\d{1,3})*(?:;\s*(?:(?:[123]|I{1,3})\s*)?(?:[A-Z][A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ]+|Ct|Cc)?.?\s*(?:1?[0-9]?[0-9]):\s*\d{1,3}(?:[,-]\s*\d{1,3})*)*)/';
        $reg2 = '/((?:[1234]\s?)?[a-zа-я]+)(\s?\d+(?::(?:\d+[—–-]\d+|\d+)(?:,\d+[—–-]\d+|,\d+)*(?:;\s?\d+(?::(?:\d+[—–-]\d+|\d+)(?:,\d+[—–-]\d+|,\d+)*|;))*)?)/i';
        $reg3 = '/((?:([123]|I{1,3})\s?)?[A-Z][A-Za-zá-ùÁ-Ù]+|Ct|Cc)(\s?\d+(?:[:.](?:\d+[—–-]\d+|\d+)(?:,\d+[—–-]\d+|,\d+)*(?:;\s?\d+(?::(?:\d+[—–-]\d+|\d+)(?:,\d+[—–-]\d+|,\d+)*|;))*)?)/';
        
        $content = preg_replace_callback( $reg3, array( $this, 'output_html' ), $content );

        return $content;

    }

    public function output_html( $references ) {
        $patterns = array();
        $patterns[0] = '/\./';
        $replacements = array();
        $replacements[0] = ':';
        return '<cite rel="tooltip">' . preg_replace("/\./",":", $references[0] )  . '</cite>';
    }

    // Create Shortcode tooltip
    // Shortcode: [tooltip tip=""]Content[/tooltip]
    function create_tooltip_shortcode($atts, $content = null) {

        $atts = shortcode_atts(
            array(
                'tip' => 'Loading...',
            ),
            $atts,
            'tooltip'
        );

        $tip = $atts['tip'];

        $output = '<cite rel="tooltip" title="'. $tip .'">' . str_replace(".",":", $content )  . '</cite><span class="note"></span>';
        return $content;
    }

    // Temp deactivate
    public function text( $ref ){
        $translation = get_option( 'bible_verses_refs_settings' )['translations'];
        $url = "https://bible-api.com/" . $ref . "?translation=" . $translation;

        $args = array(
                    'timeout' => 120,
                    'httpversion' => '1.1',
                    'sslverify' => false
                );

        $request = wp_remote_get( $url, $args );

        if( is_wp_error( $request ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $request );

        $data = json_decode( $body );

        if ( ! isset( $data->text ) ) {
            return false;
        }

        return $data->text;

    }

    /**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'bible-verses-refs',
            false,
            basename( dirname( __FILE__ ) ) . '/languages/'
        );

	}

}

Bible_Verses_References::get_instance();
