<?php
/**
 * Plugin Name: Custom Caldera Forms Field Validator Example
 */
add_filter('caldera_forms_get_form_processors', 'my_custom_cf_validator_processor');

/**
 * Add a custom processor for field validation
 *
 * @uses 'my_custom_cf_validator_processor'
 *
 * @param array $processors Processor configs
 *
 * @return array
 */
function my_custom_cf_validator_processor($processors){
    $processors['my_custom_cf_validator'] = array(
        'name' => __('Custom Validator', 'my-text-domain' ),
        'description' => '',
        'pre_processor' => 'my_custom_validator',
        'template' => dirname(__FILE__) . '/custom-validator-config.php'

    );

    return $processors;


}

/**
 * Run field validation
 *
 * @param array $config Processor config
 * @param array $form Form config
 *
 * @return array|void Error array if needed, else void.
 */
function my_custom_validator( array $config, array $form ){

    //Processor data object
    $data = new Caldera_Forms_Processor_Get_Data( $config, $form, my_custom_cf_validator_fields() );

    //Value of field to be validated
    $value = $data->get_value( 'field-to-validate' );

    //if not valid, return an error
    if( ! in_array( $value, my_custom_cf_validator_valid_values() ) ){

        //get ID of field to put error on
        $fields = $data->get_fields();
        $field_id = $fields[ 'field-to-validate' ][ 'config_field' ];

        //Get label of field to use in error message above form
        $field = $form[ 'fields' ][ $field_id ];
        $label = $field[ 'label' ];

        //this is error data to send back
        return array(
            'type' => 'error',
            //this message will be shown above form
            'note' => sprintf( 'Please Correct %s', $label ),
            //Add error messages for any form field
            'fields' => array(
                //This error message will be shown below the field that we are validating
                $field_id => __( 'This field is invalid', 'text-domain' )
            )
        );
    }

    //If everything is good, don't return anything!

}


/**
 * Get an array of valid values
 *
 * UPDATE THIS! Use your array of values, or query the database here.
 *
 * @return array
 */
function my_custom_cf_validator_valid_values(){
    return array(
        'Han Solo',
        'Chewbacca',
        'Rey'
    );
}


function my_custom_cf_validator_fields(){
    return array(
        array(
            'id' => 'field-to-validate',
            'type' => 'text',
            'required' => true,
            'magic' => true,
            'label' => __( 'Magic tag for field to validate.', 'my-text-domain' )
        ),
    );
}