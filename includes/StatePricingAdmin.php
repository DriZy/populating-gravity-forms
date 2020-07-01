<?php


namespace state_pricing;

class StatePricingAdmin {
    public function __construct() {
        // silence is goldern
        $this->run();
    }

    private function run() {
        $this->add_actions();
    }

    private function add_actions() {
        add_action( 'admin_menu', [$this,'create_plugin_settings_page'] );
        add_action( 'admin_init', [$this, 'state_pricing_sections'] );
        add_action( 'admin_init', [$this, 'state_pricing_fields'] );
    }

    public function create_plugin_settings_page() {
        // Add the menu item and page
        $page_title = 'State Pricing Settings Page';
        $menu_title = 'State Pricing';
        $capability = 'manage_options';
        $slug = 'state_pricing';
        $callback = [$this, 'plugin_settings_page_content'];
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
    }

    public function plugin_settings_page_content() { ?>
        <div class="wrap">
            <h2>State Pricing Settings Page</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'state_pricing' );
                do_settings_sections( 'state_pricing' );
                submit_button();
                ?>
            </form>
        </div> <?php
    }

    public function state_pricing_sections() {
        add_settings_section( 'state_pricing_form_id', 'Select Gravity Form to use', [$this, 'section_callback'], 'state_pricing' );
        add_settings_section( 'state_pricing_form_fields_ids', 'Get Selected form field IDs', [$this, 'section_callback'], 'state_pricing' );
        add_settings_section( 'state_pricing_xlsx_file_link', 'Upload file containing data', [$this, 'section_callback'], 'state_pricing' );
    }


    public function section_callback( $arguments ){
        switch( $arguments['id'] ){
            case 'state_pricing_form_id':
                echo 'Get the form ID from list of forms page on gravity forms.';
                break;
            case 'state_pricing_form_fields_ids':
                echo 'Get the form field IDs by hovering over each field in the form  with the above ID in gravity forms';
                break;
            case 'state_pricing_xlsx_file_link':
                echo 'Upload the .xlsx file via media, copy the path to the file and past here';
                break;
        }
    }



    public function state_pricing_fields() {
        $fields = array(
            array(
                'uid' => 'form_id_field',
                'label' => 'Form ID',
                'section' => 'state_pricing_form_id',
                'type' => 'text',
                'options' => false,
                'placeholder' => ''
            ),
            array(
                'uid' => 'state_field_id',
                'label' => 'State Field ID',
                'section' => 'state_pricing_form_fields_ids',
                'type' => 'text',
                'options' => false,
                'placeholder' => ''
            ),
            array(
                'uid' => 'l_type_field_id',
                'label' => 'License Type Field ID',
                'section' => 'state_pricing_form_fields_ids',
                'type' => 'text',
                'options' => false,
                'placeholder' => ''
            ),
            array(
                'uid' => 'bus_type_field_id',
                'label' => 'Business Type Field ID',
                'section' => 'state_pricing_form_fields_ids',
                'type' => 'text',
                'options' => false,
                'placeholder' => ''
            ),
            array(
                'uid' => 'fee_field_id',
                'label' => 'Fee Field ID',
                'section' => 'state_pricing_form_fields_ids',
                'type' => 'text',
                'options' => false,
                'placeholder' => ''
            ),
            array(
                'uid' => 'file_url',
                'label' => 'XLSX File Link',
                'section' => 'state_pricing_xlsx_file_link',
                'type' => 'button',
                'options' => false,
                'class' => 'sp_file_upload'
            ),
            array(
                'uid' => 'file_url_ip',
//                'label' => 'XLSX File Link',
                'section' => 'state_pricing_xlsx_file_link',
                'type' => 'text',
                'options' => false,
                'class' => 'sp_file_upload'
            )
        );
        foreach( $fields as $field ){
            add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'state_pricing', $field['section'], $field );
            register_setting( 'state_pricing', $field['uid'] );
        }
    }

    public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
        if( ! $value ) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }

        // Check which type of field we want
        switch( $arguments['type'] ){
            case 'text': // If it is a text field
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" required  />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'button':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" value="Upload File"  class="state-pricing-upload-file"  />', $arguments['uid'], $arguments['type'],  $value );
                break ;
        }
    }
}




