<?php
/*
Plugin Name: CoolClock
Plugin URI: http://status301.net/wordpress-plugins/coolclock/
Description: Add an analog clock to your sidebar.
Text Domain: coolclock
Domain Path: languages
Version: 0.1
Author: RavanH
Author URI: http://status301.net/
*/

/**
 * CoolClock Widget Class
 */
class CoolClock_Widget extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function CoolClock_Widget() {
        parent::WP_Widget( 
        		   'coolclock-widget', 
        		   __('Analog Clock', 'coolclock'), 
        		   array( 
        		   	  'classname' => 'coolclock', 
        		   	  'description' => __('Add an analog clock to your sidebar.', 'coolclock') 
        		   	  ), 
        		   array( 
        		   	  'width' => 300, 
        		   	  /*'height' => 350, */
        		   	  'id_base' => 'coolclock-widget' 
        		   	  ) 
        		   );  	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $skin = ( isset($instance['skin']) ) ? $instance['skin'] : 'swissRail';
        $radius = ( isset($instance['radius']) ) ? $instance['radius'] : 85 ; 
        $clock_height = ( $radius ) ? 2*$radius.'px' : '170px'; 
        $clock_width = ( $radius ) ? 2*$radius.'px' : '170px'; 

        $background_height = ( isset($instance['background_height']) && $instance['background_height'] != '' ) ? $instance['background_height'].'px' : $clock_height; 
        $background_width = ( isset($instance['background_width']) && $instance['background_width'] != '' ) ? $instance['background_width'].'px' : '100%';
        //$background_color = ( isset($instance['background_color']) ) ? $instance['background_color'] : 'transparent'; 
        $vertical_position_dist =  ( isset($instance['vertical_position_dist']) ) ? $instance['vertical_position_dist'].'px' : '0'; 
        $horizontal_position_dist =  ( isset($instance['horizontal_position_dist']) ) ? $instance['horizontal_position_dist'].'px' : '0'; 
        
        // add custom skin parameters to the plugin skins array
        if ( 'custom_'.$this->number == $skin )
        	CoolClock::$advanced_skins_config[$skin] = $instance['custom_skin'];

        // set footer script flags
        CoolClock::$add_script = true;
        if ( in_array( $skin, CoolClock::$more_skins ) )
        	CoolClock::$add_moreskins = true;
        if ( isset( CoolClock::$advanced_skins_config[$skin] ) )
        	CoolClock::$add_customskins = true;
        	
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
<div style="<?php if ( isset($instance['background_image']) && $instance['background_image'] != '' ) { echo 'background-image:url(\''.$instance['background_image'].'\');'; ?><?php if ( isset($instance['background_position']) ) echo 'background-position:'.$instance['background_position'].';'; ?><?php if ( isset($instance['background_repeat']) && $instance['background_repeat'] == false ) echo 'background-repeat:no-repeat;'; } ?>height:<?php echo $background_height; ?>;width:<?php echo $background_width; ?>;position:relative<?php //if ( isset($instance['background_color']) ) echo ';background-color:'.$instance['background_color']; ?>">
<div style="position:absolute;<?php if ( isset($instance['vertical_position_from']) ) echo $instance['vertical_position_from'] . ':' . $vertical_position_dist . ';'; ?><?php if ( isset($instance['horizontal_position_from']) ) echo $instance['horizontal_position_from'] . ':' . $horizontal_position_dist . ';'; ?>height:<?php echo $clock_height; ?>;width:<?php echo $clock_width; ?>">
<canvas class="CoolClock:<?php echo $skin; ?>:<?php echo $radius; ?>:<?php if ( $instance['noseconds'] ) echo 'noSeconds'; ?>:<?php if ( $instance['gmtoffset'] ) echo $instance['gmtoffset']; ?>:<?php if ( $instance['showdigital'] ) echo 'showDigital'; ?>"></canvas>
</div>
</div>

              <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		

	$instance = $old_instance;

        return CoolClock::update($instance, $new_instance);
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {

	CoolClock::form($this, $instance, $defaults);
    }

} // end class example_widget

/**
 * CoolClock Class
 */
class CoolClock {
	static $add_script;

	static $add_moreskins;

	static $add_customskins;
	
	static $defaults = array ( 
			'skin' => 'swissRail',
			'radius' => 100,
			'noseconds' => false,			// Hide seconds
			'gmtoffset' => '',			// GMT offset
			'showdigital' => false,			// Show digital time
		);

	static $advanced;
		
	static $advanced_defaults = array ();

    	static $default_skins = array (
	    		'swissRail',
	    		'chunkySwiss',
	    		'chunkySwissOnBlack'
    		);

	static $more_skins = array (
	    		'fancy',
	    		'machine',
	    		'simonbaird_com',
	    		'classic',
	    		'modern',
	    		'simple',
	    		'securephp',
	    		'Tes2',
	    		'Lev',
	    		'Sand',
	    		'Sun',
	    		'Tor',
	    		'Cold',
	    		'Babosa',
	    		'Tumb',
	    		'Stone',
	    		'Disc',
	    		'watermelon'
	    	);

	static $advanced_skins = array ();
    	
    	static $advanced_skins_config = array ();
    	
    	static function update($instance, $new_instance) {
    		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['skin'] = strip_tags($new_instance['skin']);
		$instance['custom_skin'] = strip_tags($new_instance['custom_skin']);
		$instance['radius'] = ( (int) $new_instance['radius'] < 5 ) ? 5 : (int) $new_instance['radius'];
		$instance['noseconds'] = (bool) $new_instance['noseconds'];
		$instance['gmtoffset'] = ( !$new_instance['gmtoffset'] ) ? '' : (float) $new_instance['gmtoffset'];
		$instance['showdigital'] = (bool) $new_instance['showdigital'];

    		if ( class_exists('CoolClockAdvanced') )
    			$instance = CoolClockAdvanced::update($instance, $new_instance);
    	
        	return $instance;
        }
        
    	static function form($obj, $instance, $defaults) {
    		
	$defaults = array ( 
			'title' => '',
			'custom_skin' => '',
		);
		
	$defaults = array_merge($defaults, CoolClock::$defaults, CoolClock::$advanced_defaults);
	
	$instance = wp_parse_args( (array) $instance, $defaults );
 
        $title = esc_attr( $instance['title'] );
        $custom_skin = esc_attr( $instance['custom_skin'] );
	
	// Translatable skin names go here
	$skin_names = array (
	    		'swissRail' => __('Swiss Rail','coolclock'),
	    		'chunkySwiss' => __('Chunky Swiss','coolclock'),
	    		'chunkySwissOnBlack' => __('Chunky Swiss Black','coolclock'),
	    		'fancy' => __('Fancy','coolclock'),
	    		'machine' => __('Machine','coolclock'),
	    		'simonbaird_com' => __('SimonBaird.com','coolclock'),
	    		'classic' => __('Classic by Bonstio','coolclock'),
	    		'modern' => __('Modern by Bonstio','coolclock'),
	    		'simple' => __('Simple by Bonstio','coolclock'),
	    		'securephp' => __('SecurePHP','coolclock'),
	    		'Tes2' => __('Tes2','coolclock'),
	    		'Lev' => __('Lev','coolclock'),
	    		'Sand' => __('Sand','coolclock'),
	    		'Sun' => __('Sun','coolclock'),
	    		'Tor' => __('Tor','coolclock'),
	    		'Cold' => __('Cold','coolclock'),
	    		'Babosa' => __('Babosa','coolclock'),
	    		'Tumb' => __('Tumb','coolclock'),
	    		'Stone' => __('Stone','coolclock'),
	    		'Disc' => __('Disc','coolclock'),
	    		'watermelon' => __('Watermelon by Yoo Nhe','coolclock'),
	    		'minimal' => __('Minimal','coolclock')
	    	);
	    
    	$skins = array_merge(CoolClock::$default_skins,CoolClock::$more_skins,CoolClock::$advanced_skins);

        ?>
        <p>
          <label for="<?php echo $obj->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label> 
          <input class="widefat" id="<?php echo $obj->get_field_id('title'); ?>" name="<?php echo $obj->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p><strong><?php _e('Clock', 'coolclock'); ?></strong></p>

	<p><label for="<?php echo $obj->get_field_id('skin'); ?>"><?php _e('Skin:', 'coolclock'); ?> </label> 
		<select class="select" id="<?php echo $obj->get_field_id('skin'); ?>" name="<?php echo $obj->get_field_name('skin'); ?>"><?php foreach ($skins as $value) { echo "<option value=\"$value\""; if ($value == $instance['skin']) echo ' selected="selected"'; echo '>'; if ( isset($skin_names[$value]) ) echo $skin_names[$value]; else echo $value; echo'</option>'; } unset($value); ?><option value="custom_<?php echo $obj->number ?>"<?php if ( 'custom_'.$obj->number == $instance['skin']) echo ' selected="selected"'; ?>><?php _e('Custom (define below)', 'coolclock') ?></option></select></p>
		
        <p><label for="<?php echo $obj->get_field_id('custom_skin'); ?>"><?php _e('Custom skin parameters:', 'coolclock'); ?> </label>
          <textarea class="widefat" id="<?php echo $obj->get_field_id('custom_skin'); ?>" name="<?php echo $obj->get_field_name('custom_skin'); ?>"><?php echo $custom_skin; ?></textarea></p>

	<p><label for="<?php echo $obj->get_field_id('radius'); ?>"><?php _e('Radius:', 'coolclock'); ?></label>
          <input class="small-text" id="<?php echo $obj->get_field_id('radius'); ?>" name="<?php echo $obj->get_field_name('radius'); ?>" type="number" min="10" value="<?php echo $instance['radius']; ?>" /></p>

	<p><input id="<?php echo $obj->get_field_id('noseconds'); ?>" name="<?php echo $obj->get_field_name('noseconds'); ?>" type="checkbox" value=<?php echo ( $instance['noseconds'] ) ? '"true"  checked="checked"' : '"false"'; ?> />
	<label for="<?php echo $obj->get_field_id('noseconds'); ?>"><?php _e('Hide second hand', 'coolclock'); ?></label></p>

 	<p><input id="<?php echo $obj->get_field_id('showdigital'); ?>" name="<?php echo $obj->get_field_name('showdigital'); ?>" type="checkbox" value=<?php echo ( $instance['showdigital'] ) ? '"true"  checked="checked"' : '"false"'; ?> />
	<label for="<?php echo $obj->get_field_id('showdigital'); ?>"><?php _e('Show digital time', 'coolclock'); ?></label></p>

	<p><label for="<?php echo $obj->get_field_id('gmtoffset'); ?>"><?php _e('GMT offset:', 'coolclock'); ?></label>
          <input class="small-text" id="<?php echo $obj->get_field_id('gmtoffset'); ?>" name="<?php echo $obj->get_field_name('gmtoffset'); ?>" type="number" step="0.5" value="<?php echo $instance['gmtoffset']; ?>" /> <?php _e('(leave blank for local time)', 'coolclock'); ?></p>

	<?php

    		if ( class_exists('CoolClockAdvanced') ) {
    			CoolClockAdvanced::form($obj, $instance, $defaults);
    		} else {
	?>
       <p><strong><?php _e('Background'); ?></strong></p>
        
       <p><a href="http://status301.net/wordpress-plugins/coolclock-pro/"><?php _e('Available in the Pro extension &raquo;', 'coolclock'); ?></a></p>
<?php 
		}
	}

	static function init() {
		load_plugin_textdomain('coolclock', false, dirname(plugin_basename( __FILE__ )).'/languages');
		add_action('widgets_init', create_function('', 'return register_widget("CoolClock_Widget");'));

		//add_shortcode('myshortcode', array(__CLASS__, 'handle_shortcode'));
 
		add_action('init', array(__CLASS__, 'register_script'));
		add_action('wp_footer', array(__CLASS__, 'print_script'));
	}
 
	static function handle_shortcode($atts) {
		self::$add_script = true;
 
		// actual shortcode handling here
	}
 
	static function register_script() {
		wp_register_script('coolclock', 'http://randomibis.com/coolclock/coolclock.js', array('jquery'), '2.1.4', true);
		wp_register_script('coolclock-moreskins', 'http://randomibis.com/coolclock/moreskins.js', array('coolclock'), '2.1.4', true);
	}
 
	static function print_script() {
		if ( ! self::$add_script )
			return;
 
		wp_print_scripts('coolclock');

		if ( self::$add_moreskins )
			wp_print_scripts('coolclock-moreskins');
			
		if ( self::$add_customskins ) {
				echo '<script type="text/javascript">jQuery.extend(CoolClock.config.skins, {
';
				// loop through plugin custom skins
				foreach (self::$advanced_skins_config as $key => $value)
					echo $key.':{'.$value.'},
';
				echo '});</script>
';
		}
		
		echo '<!--[if lt IE 9]><script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/flot/0.7/excanvas.min.js"></script><![endif]-->
';
	}
}
 
CoolClock::init();

