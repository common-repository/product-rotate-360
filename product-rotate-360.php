<?php
/**
 * Plugin Name: Product rotate 360
 * Plugin URI: https://demo1.debdesk.com/product-rotate-360-demo/
 * Description: 360 degree rotation of your product
 * Version: 1.0.0
 * Author: deb17276
 * Author URI: https://www.fiverr.com/deb17278
 */

class ProductRotate360
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action('wp_enqueue_scripts', array( $this, 'product_rotate_js' ), 100);
		add_shortcode('product_rotate_360', array($this, 'debdesk_product_rotate_360'));
    }

	public function product_rotate_js() {
		wp_enqueue_script( 'product-rotate', plugins_url( '/assets/js/rotate360-min.js', __FILE__),"jquery-core","1.0", false);
	}

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'ProductRotate 360', 
            'manage_options', 
            'pr360-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'pr360_option_name' );
        ?>
        <div class="wrap">
            <h1>Product Rotate 360 Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'pr360_option_group' );
                do_settings_sections( 'pr360-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'pr360_option_group', // Option group
            'pr360_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'General Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'pr360-setting-admin' // Page
        );    

        add_settings_field(
            'imgdir', 
            'Image Location', 
            array( $this, 'imgdir_callback' ), 
            'pr360-setting-admin', 
            'setting_section_id'
        );   

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'pr360-setting-admin', // Page
            'setting_section_id' // Section           
        );    

        add_settings_field(
            'width',
            'Canvas Width', 
            array( $this, 'width_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );    

        add_settings_field(
            'height',
            'Canvas Height', 
            array( $this, 'height_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );    

        add_settings_field(
            'prefix',
            'Image Name Prefix', 
            array( $this, 'prefix_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );    

        add_settings_field(
            'digits',
            'Image Name Digits', 
            array( $this, 'digits_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );    

        add_settings_field(
            'ext',
            'Image Extension', 
            array( $this, 'ext_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );    

        add_settings_field(
            'count',
            'Image Count', 
            array( $this, 'count_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );     

        add_settings_field(
            'frametime',
            'Frame Time', 
            array( $this, 'frametime_callback' ),
            'pr360-setting-admin',
            'setting_section_id'        
        );      
    }

	public function debdesk_product_rotate_360 () {
		$a = get_option( 'pr360_option_name' );
		ob_start();
	?>
		
		<div id="webrorate-<?php echo esc_attr($a['id_number']); ?>" class="webrorate_wrapper" style="width:<?php echo esc_attr($a['width']); ?>px;">
			<div class="spritespin"></div>
		</div>

		<script>
		jQuery(function($) {
		  var viewId = "<?php echo esc_attr($a['id_number']); ?>";
		  var frametime ="<?php echo esc_attr($a['frametime']); ?>"; 
		  var width ="<?php echo esc_attr($a['width']); ?>"; 
		  var height ="<?php echo esc_attr($a['height']); ?>";
		  var imgDir ="<?php echo esc_url($a['imgdir']); ?>";
		  var prefix ="<?php echo esc_attr($a['prefix']); ?>";
		  var ext ="<?php echo esc_attr($a['ext']); ?>";
		  var digits ="<?php echo esc_attr($a['digits']); ?>";
		  var count ="<?php echo esc_attr($a['count']); ?>";
		  webRotateFree($, viewId, width, height, frametime, imgDir,prefix, ext, digits, count);
		});
		</script>

  
	  <?php
		return ob_get_clean();
		
	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['width'] ) )
            $new_input['width'] = absint( $input['width'] );

        if( isset( $input['height'] ) )
            $new_input['height'] = absint( $input['height'] );

        if( isset( $input['count'] ) )
            $new_input['count'] = absint( $input['count'] );

        if( isset( $input['digits'] ) )
            $new_input['digits'] = absint( $input['digits'] );

        if( isset( $input['frametime'] ) )
            $new_input['frametime'] = absint( $input['frametime'] );

        if( isset( $input['imgdir'] ) )
            $new_input['imgdir'] = sanitize_text_field( $input['imgdir'] );

        if( isset( $input['prefix'] ) )
            $new_input['prefix'] = sanitize_text_field( $input['prefix'] );

        if( isset( $input['ext'] ) )
            $new_input['ext'] = sanitize_text_field( $input['ext'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="pr360_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : '1'
        );
    }

	/** 
     * Get the settings option array and print one of its values
     */
    public function width_callback()
    {
        printf(
            '<input type="text" id="width" name="pr360_option_name[width]" value="%s" />',
            isset( $this->options['width'] ) ? esc_attr( $this->options['width']) : '300'
        );
    }

	/** 
     * Get the settings option array and print one of its values
     */
    public function height_callback()
    {
        printf(
            '<input type="text" id="height" name="pr360_option_name[height]" value="%s" />',
            isset( $this->options['height'] ) ? esc_attr( $this->options['height']) : '300'
        );
    }

	/** 
     * Get the settings option array and print one of its values
     */
    public function digits_callback()
    {
        printf(
            '<input type="text" id="digits" name="pr360_option_name[digits]" value="%s" />',
            isset( $this->options['digits'] ) ? esc_attr( $this->options['digits']) : '2'
        );
    }

	/** 
     * Get the settings option array and print one of its values
     */
    public function count_callback()
    {
        printf(
            '<input type="text" id="count" name="pr360_option_name[count]" value="%s" />',
            isset( $this->options['count'] ) ? esc_attr( $this->options['count']) : '33'
        );
    }

	/** 
     * Get the settings option array and print one of its values
     */
    public function frametime_callback()
    {
        printf(
            '<input type="text" id="frametime" name="pr360_option_name[frametime]" value="%s" />',
            isset( $this->options['frametime'] ) ? esc_attr( $this->options['frametime']) : '100'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function imgdir_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="imgdir" name="pr360_option_name[imgdir]" value="%s" />',
            isset( $this->options['imgdir'] ) ? esc_attr( $this->options['imgdir']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function prefix_callback()
    {
        printf(
            '<input type="text" id="prefix" name="pr360_option_name[prefix]" value="%s" />',
            isset( $this->options['prefix'] ) ? esc_attr( $this->options['prefix']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function ext_callback()
    {
        printf(
            '<input type="text" id="ext" name="pr360_option_name[ext]" value="%s" />',
            isset( $this->options['ext'] ) ? esc_attr( $this->options['ext']) : ''
        );
    }
}


$product_rotate_360 = new ProductRotate360();