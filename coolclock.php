<?php
/*
Plugin Name: CoolClock - The Javascript Analog Clock for WordPress
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
        $skin = ( $instance['skin'] ) ? $instance['skin'] : 'swissRail';
        $radius = $instance['radius']; 
        $clock_height = ( $radius ) ? 2*$radius.'px' : '170px'; 
        $clock_width = ( $radius ) ? 2*$radius.'px' : '170px'; 
        $background_height = ( $instance['background_height'] ) ? $instance['background_height'].'px' : $clock_height; 
        $background_width = ( $instance['background_width'] ) ? $instance['background_width'].'px' : '100%';
        //$background_color = ( $instance['background_color'] ) ? $instance['background_color'] : 'transparent'; 
        $vertical_position_dist =  ( $instance['vertical_position_dist'] ) ? $instance['vertical_position_dist'].'px' : '0'; 
        $horizontal_position_dist =  ( $instance['horizontal_position_dist'] ) ? $instance['horizontal_position_dist'].'px' : '0'; 
        
        // add custom skin parameters to the plugin skins array
        if ( 'custom_'.$this->number == $skin ) {
        	//CoolClock::$plugin_skins_config[] = $skin;
        	CoolClock::$plugin_skins_config[$skin] = $instance['custom_skin'];
        }

        // set print scripts flags
        CoolClock::$add_script = true;
        if ( in_array( $skin, CoolClock::$more_skins ) )
        	CoolClock::$add_moreskins = true;
        if ( isset( CoolClock::$plugin_skins_config[$skin] ) )
        	CoolClock::$add_customskins = true;
        	
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
<div style="<?php if ( $instance['background_image'] ) echo 'background-image:url(\''.$instance['background_image'].'\');'; ?><?php if ( $instance['background_position'] ) echo 'background-position:'.$instance['background_position'].';'; ?><?php if ( !$instance['background_repeat'] ) echo 'background-repeat:no-repeat;'; ?>height:<?php echo $background_height; ?>;width:<?php echo $background_width; ?>;position:relative<?php //if ( $instance['background_color'] ) echo ';background-color:'.$instance['background_color']; ?>">
<div style="position:absolute;<?php echo $instance['vertical_position_from']; ?>:<?php echo $vertical_position_dist; ?>;<?php echo $instance['horizontal_position_from']; ?>:<?php echo $horizontal_position_dist; ?>;height:<?php echo $clock_height; ?>;width:<?php echo $clock_width; ?>">
<canvas class="CoolClock:<?php echo $skin; ?>:<?php echo $radius; ?>:<?php if ( $instance['noseconds'] ) echo 'noSeconds';
/*if ( $instance['noseconds'] ) { echo ':noSeconds';  if ( $instance['gmtoffset'] ) echo ':'.$instance['gmtoffset']; } else { if ( $instance['gmtoffset'] ) echo '::'.$instance['gmtoffset']; } */
?>:<?php if ( $instance['gmtoffset'] ) echo $instance['gmtoffset']; ?>:<?php if ( $instance['showdigital'] ) echo 'showDigital'; ?>"></canvas>
</div>
</div>

              <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        $instance['skin'] = strip_tags($new_instance['skin']);
        $instance['custom_skin'] = strip_tags($new_instance['custom_skin']);
	$instance['radius'] = ( (int) $new_instance['radius'] < 5 ) ? 5 : (int) $new_instance['radius'];
	$instance['noseconds'] = (bool) $new_instance['noseconds'];
	$instance['gmtoffset'] = ( !$new_instance['gmtoffset'] ) ? '' : (float) $new_instance['gmtoffset'];
	$instance['showdigital'] = (bool) $new_instance['showdigital'];
	$instance['background_image'] = strip_tags($new_instance['background_image']); // TODO callback for 'valid' URL to prevent injection ?
	$instance['background_position'] = strip_tags($new_instance['background_position']);
	$instance['background_repeat'] = (bool) $new_instance['background_repeat'];
	$instance['background_width'] = ( !$new_instance['background_width'] || (int) $new_instance['background_width'] < 1 ) ? '' : (int) $new_instance['background_width'];
	$instance['background_height'] = ( !$new_instance['background_height'] || (int) $new_instance['background_height'] < 1 ) ? '' : (int) $new_instance['background_height'];
	//$instance['background_color'] = strip_tags($new_instance['background_color']); // TODO callback for hex value ?
	$instance['vertical_position_dist'] = (int) $new_instance['vertical_position_dist'];
        $instance['vertical_position_from'] = strip_tags($new_instance['vertical_position_from']);
	$instance['horizontal_position_dist'] = (int) $new_instance['horizontal_position_dist'];
        $instance['horizontal_position_from'] = strip_tags($new_instance['horizontal_position_from']);
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {

	$defaults = array ( 
		'title' => '',				// Widget title
		'skin' => 'swissRail',		// Clock skin style
		'custom_skin' => '',
		'radius' => '100',			// Clock radius
		'noseconds' => false,			// Hide seconds
		'gmtoffset' => '',			// GMT offset
		'showdigital' => false,			// Show digital time
		'background_image' => '',		// Custom background image url
		'background_position' => 'left',	
		'background_repeat' => false,	
		'background_height' => '',		// Set height for wrapping div that carries the background image
		'background_width' => '',		// Set width for wrapping div that carries the background image
		//'background_color' => '',		// Set background color
		'vertical_position_dist' => '0',		// Clock position relative to background: distance from...
		'vertical_position_from' => 'top',		// Clock position relative to background: top or bottom
		'horizontal_position_dist' => '0',		// Clock position relative to background: distance from...
		'horizontal_position_from' => 'left',		// Clock position relative to background: left or right
	);
	$instance = wp_parse_args( (array) $instance, $defaults );
 
        $title 		= esc_attr($instance['title']);
        $background_image = esc_attr($instance['background_image']);
        $custom_skin = esc_attr($instance['custom_skin']);
	
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
    	$skins = array_merge(CoolClock::$default_skins,CoolClock::$more_skins,CoolClock::$plugin_skins);

        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p><strong><?php _e('Clock', 'coolclock'); ?></strong></p>

	<p><label for="<?php echo $this->get_field_id('skin'); ?>"><?php _e('Skin:', 'coolclock'); ?> </label> 
		<select class="select" id="<?php echo $this->get_field_id('skin'); ?>" name="<?php echo $this->get_field_name('skin'); ?>"><?php foreach ($skins as $value) { echo "<option value=\"$value\""; if ($value == $instance['skin']) echo ' selected="selected"'; echo '>'; if ( isset($skin_names[$value]) ) echo $skin_names[$value]; else echo $value; echo'</option>'; } unset($value); ?><option value="custom_<?php echo $this->number ?>"<?php if ( 'custom_'.$this->number == $instance['skin']) echo ' selected="selected"'; ?>><?php _e('Custom (define below)', 'coolclock') ?></option></select></p>
		
        <p><label for="<?php echo $this->get_field_id('custom_skin'); ?>"><?php _e('Custom skin parameters:', 'coolclock'); ?> </label>
          <textarea class="widefat" id="<?php echo $this->get_field_id('custom_skin'); ?>" name="<?php echo $this->get_field_name('custom_skin'); ?>"><?php echo $custom_skin; ?></textarea></p>

	<p><label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e('Radius:', 'coolclock'); ?></label>
          <input class="small-text" id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="number" min="10" value="<?php echo $instance['radius']; ?>" /></p>

	<p><input id="<?php echo $this->get_field_id('noseconds'); ?>" name="<?php echo $this->get_field_name('noseconds'); ?>" type="checkbox" value=<?php echo ( $instance['noseconds'] ) ? '"true"  checked="checked"' : '"false"'; ?> />
	<label for="<?php echo $this->get_field_id('noseconds'); ?>"><?php _e('Hide second hand', 'coolclock'); ?></label></p>

 	<p><input id="<?php echo $this->get_field_id('showdigital'); ?>" name="<?php echo $this->get_field_name('showdigital'); ?>" type="checkbox" value=<?php echo ( $instance['showdigital'] ) ? '"true"  checked="checked"' : '"false"'; ?> />
	<label for="<?php echo $this->get_field_id('showdigital'); ?>"><?php _e('Show digital time', 'coolclock'); ?></label></p>

	<p><label for="<?php echo $this->get_field_id('gmtoffset'); ?>"><?php _e('GMT offset:', 'coolclock'); ?></label>
          <input class="small-text" id="<?php echo $this->get_field_id('gmtoffset'); ?>" name="<?php echo $this->get_field_name('gmtoffset'); ?>" type="number" step="0.5" value="<?php echo $instance['gmtoffset']; ?>" /> <?php _e('(leave blank for local time)', 'coolclock'); ?></p>

       <p><strong><?php _e('Background'); ?></strong></p>
        
        <p><label for="<?php echo $this->get_field_id('background_image'); ?>"><?php _e('Image URL:', 'coolclock'); ?> </label>
          <input class="widefat" id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="text" value="<?php echo $background_image; ?>" /></p>
          
 	<p><input id="<?php echo $this->get_field_id('background_repeat'); ?>" name="<?php echo $this->get_field_name('background_repeat'); ?>" type="checkbox" value=<?php echo ( $instance['background_repeat'] ) ? '"true"  checked="checked"' : '"false"'; ?> />
	<label for="<?php echo $this->get_field_id('background_repeat'); ?>"><?php _e('Repeat image', 'coolclock'); ?></label></p>

        <p><label for="<?php echo $this->get_field_id('background_position'); ?>"><?php _e('Position:', 'coolclock'); ?></label>
		<select class="select" id="<?php echo $this->get_field_id('background_position'); ?>" name="<?php echo $this->get_field_name('background_position'); ?>"><option value="left"<?php if ($instance['background_position'] == 'left') echo " selected=\"selected\"" ?>><?php _e('left', 'coolclock') ?></option><option value="top"<?php if ($instance['background_position'] == 'top') echo " selected=\"selected\"" ?>><?php _e('top', 'coolclock') ?></option><option value="right"<?php if ($instance['background_position'] == 'right') echo " selected=\"selected\"" ?>><?php _e('right', 'coolclock') ?></option><option value="bottom"<?php if ($instance['background_position'] == 'bottom') echo " selected=\"selected\"" ?>><?php _e('bottom', 'coolclock') ?></option><option value="center"<?php if ($instance['background_position'] == 'center') echo " selected=\"selected\"" ?>><?php _e('center', 'coolclock') ?></option></select></p>
        

        <p><label for="<?php echo $this->get_field_id('background_width'); ?>"><?php _e('Width'); ?></label>
          <input class="small-text" id="<?php echo $this->get_field_id('background_width'); ?>" name="<?php echo $this->get_field_name('background_width'); ?>" type="number" value="<?php echo $instance['background_width']; ?>" />
          <label for="<?php echo $this->get_field_id('background_height'); ?>"><?php _e('Height'); ?></label>
          <input class="small-text" id="<?php echo $this->get_field_id('background_height'); ?>" name="<?php echo $this->get_field_name('background_height'); ?>" type="number" value="<?php echo $instance['background_height']; ?>" /></p>

	<!-- <p>
          <label for="<?php //echo $this->get_field_id('background_color'); ?>"><?php //_e('Color:', 'coolclock'); ?> 
          <input class="widefat" id="<?php //echo $this->get_field_id('background_color'); ?>" name="<?php //echo $this->get_field_name('background_color'); ?>" type="text" value="<?php // echo $instance['background_color']; ?>" /> <?php //_e('(hex color value)', 'coolclock'); ?> </label>
        </p> -->

        <p><?php _e('Clock position relative to background:', 'coolclock'); ?></p>
        
	<p><input class="small-text" id="<?php echo $this->get_field_id('horizontal_position_dist'); ?>" name="<?php echo $this->get_field_name('horizontal_position_dist'); ?>" type="number" value="<?php echo $instance['horizontal_position_dist']; ?>" />
	<label for="<?php echo $this->get_field_id('horizontal_position_dist'); ?>"><?php _e('pixels', 'coolclock'); ?></label> 
	<label for="<?php echo $this->get_field_id('horizontal_position_from'); ?>"><?php _e('from', 'coolclock'); ?> </label>
		<select class="select" id="<?php echo $this->get_field_id('horizontal_position_from'); ?>" name="<?php echo $this->get_field_name('horizontal_position_from'); ?>"><option value="left"<?php if ($instance['horizontal_position_from'] == 'left') echo " selected=\"selected\"" ?>><?php _e('left', 'coolclock') ?></option><option value="right"<?php if ($instance['horizontal_position_from'] == 'right') echo " selected=\"selected\"" ?>><?php _e('right', 'coolclock') ?></option></select><br />
	<input class="small-text" id="<?php echo $this->get_field_id('vertical_position_dist'); ?>" name="<?php echo $this->get_field_name('vertical_position_dist'); ?>" type="number" value="<?php echo $instance['vertical_position_dist']; ?>" />
	<label for="<?php echo $this->get_field_id('vertical_position_dist'); ?>"><?php _e('pixels', 'coolclock'); ?></label> 
	<label for="<?php echo $this->get_field_id('vertical_position_from'); ?>"><?php _e('from', 'coolclock'); ?></label>
		<select class="select" id="<?php echo $this->get_field_id('vertical_position_from'); ?>" name="<?php echo $this->get_field_name('vertical_position_from'); ?>"><option value="top"<?php if ($instance['vertical_position_from'] == 'top') echo " selected=\"selected\"" ?>><?php _e('top', 'coolclock') ?></option><option value="bottom"<?php if ($instance['vertical_position_from'] == 'bottom') echo " selected=\"selected\"" ?>><?php _e('bottom', 'coolclock') ?></option></select></p>

        <?php 
    }

} // end class example_widget


class CoolClock {
	static $add_script;

	static $add_moreskins;

	static $add_customskins;

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

	static $plugin_skins = array (
    		'minimal'
    	);
    	
    	static $plugin_skins_config = array (
    		'minimal' => 'outerBorder:{lineWidth:0,radius:0,color:"black",alpha:0},smallIndicator:{lineWidth:0,startAt:0,endAt:0,color:"black",alpha:0},largeIndicator:{lineWidth:0,startAt:0,endAt:0,color:"black",alpha:0},hourHand:{lineWidth:5,startAt:-15,endAt:50,color:"black",alpha:1},minuteHand:{lineWidth:3,startAt:-15,endAt:65,color:"black",alpha:1},secondHand:{lineWidth:1,startAt:-20,endAt:75,color:"red",alpha:1},secondDecoration:{lineWidth:1,startAt:0,radius:4,fillColor:"red",color:"red",alpha:1}'    	
    	);

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
				foreach (self::$plugin_skins_config as $key => $value)
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

