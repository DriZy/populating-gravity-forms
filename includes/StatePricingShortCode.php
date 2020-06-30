<?php


namespace state_pricing;

class StatePricingShortCode {
	public function __construct() {
		// silence is goldern
		$this->run();
	}

	private function run() {
		$this->add_actions();
	}

	private function add_actions() {
	    $form_id = get_option('form_id_field');
        add_shortcode( 'state_pricing', [ $this, 'state_pricing_shorcode_callback' ] );
        add_action( 'init', [$this,'state_pricing_iniatilise_array'] ,70);
        add_filter( "gform_pre_render_$form_id", [$this,'populate_posts'] );
        add_filter( "gform_pre_validation_$form_id", [$this,'populate_posts'] );
        add_filter( "gform_pre_submission_filter_$form_id", [$this, 'populate_posts'] );
        add_filter( "gform_admin_pre_render_$form_id", [$this, 'populate_posts' ]);
        add_action("wp_ajax_state_pricing_get_values", [$this,'state_pricing_get_values']);
        add_action("wp_ajax_nopriv_state_pricing_get_values", [$this,'state_pricing_get_values']);
        add_action("wp_ajax_license_pricing_get_values", [$this,'license_pricing_get_values']);
        add_action("wp_ajax_nopriv_license_pricing_get_values", [$this,'license_pricing_get_values']);
        add_action("wp_ajax_company_pricing_get_values", [$this,'company_pricing_get_values']);
        add_action("wp_ajax_nopriv_company_pricing_get_values", [$this,'company_pricing_get_values']);
        
    }

	public function state_pricing_shorcode_callback( $atts )
    {
        global $datas;
        global $count;
        gravity_form( 19, false, false, false, false, false);
    }

    public function state_pricing_iniatilise_array()
    {
        
        global $datas;
        global $count;
        $datas = array();
        $file_path = plugin_dir_path( __FILE__ ).'../state-fee.xlsx';
        if ( $xlsx = SimpleXLSX::parse($file_path) ) {
            if(count($xlsx->rows())< 2 ){
                echo 'The exel file has no data <br>';
                return;
            }else{
                $count = $xlsx->rows() ;

                foreach ($xlsx->rows() as $key=>$column) {

                    if($key !=0){
                        if(array_key_exists($column[0], $datas)){
                            array_push($datas[$column[0]], [$column[0],$column[1], $column[2], $column[3]]); 
                        }else{
                            $datas[$column[0]] = array();
                            array_push($datas[$column[0]], [$column[0],$column[1], $column[2], $column[3]]); 
                        }
                    }
                }
            }
        }
        else {
            echo SimpleXLSX::parseError();
        }
    }


    public function populate_posts( $form ) {

	    $state_field_id = get_option('state_field_id');
	    $file_path = get_option('file_url');

        global $datas;
        
        foreach ( $form['fields'] as & $field ) {

            if ( $field->type != 'select' || strpos( $field->id, "$state_field_id" ) === false ) {
                continue;
            }
            $choices = array();
    
            foreach ( $datas as $key=>$data ) {
                $choices[] = array( 'text' => $key, 'value' => $key );
            }
    
            // update 'Select a Post' to whatever you'd like the instructive option to be
            $field->placeholder = 'Select a State';
            $field->choices = $choices;
    
        }
    
        return $form;
    }

    function state_pricing_get_values(){
        global $datas;
        
        $response = array();
        $state = esc_attr( $_POST['state'] );
    
        if(array_key_exists($state, $datas)){
            $datas[$state] ;
            $va = 0;
          
            foreach($datas[$state]  as $array_v){
               array_push($response,$array_v[1]);

            }
            
        }else{
        	$response['false'] = 'Sorry the state enter donot match any result';
        }
        echo json_encode(array_unique($response));
        die();
        
    
    }

    //
    function license_pricing_get_values(){
        global $datas;
        
        $response = array();
        $state = esc_attr( $_POST['state'] );
        $license = esc_attr( $_POST['license'] );
    
        if(array_key_exists($state, $datas)){
            $datas[$state] ;
            $va = 0;
          
            foreach($datas[$state]  as $array_v){
                if($array_v[0]== $state && $array_v[1]== $license){
                    array_push($response,$array_v[2]); 
                }
              
            }
            
        }else{
        	$response['false'] = 'Sorry the state enter donot match any result';
        }
        echo json_encode(array_unique($response));
        die();
        
    
    }
    //
    function company_pricing_get_values(){
        global $datas;
        
        $response = array();
        $state = esc_attr( $_POST['state'] );
        $license = esc_attr( $_POST['license'] );
        $company = esc_attr( $_POST['company'] );
    
        if(array_key_exists($state, $datas)){
            $datas[$state] ;
            $va = 0;
            
            foreach($datas[$state]  as $array_v){
                if($array_v[0]== $state && $array_v[1]== $license && $array_v[2]== $company){
                   $response['price'] = $array_v[3]; 
                } 
            }
            
        }else{
            $response['false'] = 'Sorry the state enter donot match any result';
        }
        echo json_encode(array_unique($response));
        die();
    }

}
