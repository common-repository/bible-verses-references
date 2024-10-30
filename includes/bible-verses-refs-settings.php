<?php

	if ( ! defined( 'ABSPATH' ) ) exit;
class Bible_Verses_References_Settings {
    public function __construct() {
	    // Hook into the admin menu
	    add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
	    add_action( 'admin_init', array( $this, 'setup_sections' ) );
	    add_action( 'admin_init', array( $this, 'setup_fields' ) );
	}

	public function create_plugin_settings_page() {
    	// Add the menu item and page
		add_options_page(
            __('Bible Verses References', 'bible-verses-refs'),
            __('Bible Verses Refs', 'bible-verses-refs'),
            'manage_options',
            'bible_verses_references',
            array( $this, 'plugin_settings_page_content' )
        );
	}

	public function plugin_settings_page_content() { ?>
	    	<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'bible_verses_refs_fields' );
					do_settings_sections( 'bible_verses_refs_fields' );
					submit_button();
				?>
			</form>
    		</div>
	<?php
	}

	/* AQUI COMEÇA A ADIÇÃO DOS CAMPOS */
	public function setup_sections() {
    	
		add_settings_section(
			'bible_verses_refs_section',
			__( 'Settings Bible Verses References Plugins', 'bible-verses-refs' ),
			array( $this, 'section_callback' ),
			'bible_verses_refs_fields'
		);

    	add_settings_section(
			'bible_verses_refs_section',
			__( 'Translation settings', 'bible-verses-refs' ), 
			array( $this, 'section_callback' ),
			'bible_verses_refs_fields'
		);
	}

	public function section_callback( $arguments ) {
		switch( $arguments['id'] ){
			case 'bible_verses_refs_section':
				echo __( 'Set the translation of the biblical text according to your language.', 'bible-verses-refs');
				break;
		}
	}

	public function setup_fields() {
	    $fields = array(
			array(
        		'uid' => 'translations',
        		'label' => __( 'Translations', 'bible-verses-refs' ),
        		'section' => 'bible_verses_refs_section',
        		'type' => 'select',
        		'options' => array(
					'cherokee' => __( 'Cherokee New Testament (Cherokee)', 'bible-verses-refs' ),
					'bbe' => __( 'Bible in Basic English (English)', 'bible-verses-refs' ),
					'kjv' => __( 'King James Version (English)', 'bible-verses-refs' ),
					'web' => __( 'World English Bible (English)', 'bible-verses-refs' ),
					'oeb-cw' => __( 'Open English Bible Commonwealth Edition (English - UK)', 'bible-verses-refs' ),
					'webbe' => __( 'World English Bible British Edition (English - UK)', 'bible-verses-refs' ),
					'oeb-us' => __( 'Open English Bible US Edition (English - US))', 'bible-verses-refs' ),
					'clementine' => __( 'Clementine Latin Vulgate (Latin)', 'bible-verses-refs' ),
					'almeida' => __( 'João Ferreira de Almeida (Portuguese)', 'bible-verses-refs' ),
					'rccv' => __( 'Protestant Romanian Corrected Cornilescu Version (Romanian)', 'bible-verses-refs' ),
        		),
				'default' => 'web',
				'helper' => __('helper', 'bible-verses-refs' ),
		        'supplemental' => __('supplemental', 'bible-verses-refs' ),
			)
		);
		
	    foreach( $fields as $field ){
	        add_settings_field(
				$field['uid'],
				$field['label'],
				array( $this, 'field_callback' ),
				'bible_verses_refs_fields',
				$field['section'],
				$field
			);
		}
		
		register_setting( 'bible_verses_refs_fields', 'bible_verses_refs_settings' );
	}

	
	public function field_callback( $arguments ) {

		$value = get_option( 'bible_verses_refs_settings' )[$arguments['uid']]; // Get the current value, if there is one
		if( ! $value ) { // If no value exists
			$value = $arguments['default']; // Set to our default
		}

	    // Check which type of field we want
	    switch( $arguments['type'] ){

			case 'select':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( 
							'<option value="%s" %s>%s</option>', 
							$key,
							$value == $key ? 'selected' : '',
							$label
						);
                    }
                    printf( '<select name="bible_verses_refs_settings[%1$s]" id="bible_verses_refs_settings[%1$s]" %2$s>%3$s</select>', 
						$arguments['uid'], 
						$attributes, 
						$options_markup
					);
                }
                break;
			
			case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
                        $iterator++;
                        $options_markup .= sprintf( '<label for="bible_verses_refs_settings[%1$s_%6$s]"><input id="bible_verses_refs_settings[%1$s_%6$s]" name="bible_verses_refs_settings[%1$s]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
		}


	    // If there is help text
	    if( $helper = $arguments['helper'] ){
	        printf( '<span class="helper"> %s</span>', $helper ); // Show it
	    }

	    // If there is supplemental text
	    if( $supplimental = $arguments['supplemental'] ){
	        printf( '<p class="description">%s</p>', $supplimental ); // Show it
	    }
	}
}
new Bible_Verses_References_Settings();