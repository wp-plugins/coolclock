<?php
/*
Plugin Name: CoolClock
Plugin URI: http://status301.net/wordpress-plugins/coolclock/
Description: Add an analog clock to your sidebar.
Text Domain: coolclock
Domain Path: languages
Version: 2.9
Author: RavanH
Author URI: http://status301.net/
*/

/**
 * CoolClock Class
 */
class CoolClock {

	static $plugin_version = '2.9';

	static $script_version = '3.0.0-pre';

	static $add_script;

	static $add_moreskins;

	static $add_customskins;
	
	static $done_excanvas = false;

	static $defaults = array (
			'skin' => 'swissRail',
			'radius' => 100,
			'noseconds' => false,			// Hide seconds
			'gmtoffset' => '',			// GMT offset
			'showdigital' => false,			// Show digital time
			'scale' => 'linear'			// Define type of clock linear/logarithmic/log reversed
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

	static $clock_types = array (
	    		'linear',
	    		'logClock',
	    		'logClockRev'
	    	);

	// FUNCTIONS //

	function widget( $args, $instance ) {

		$skin = ( isset( $instance['skin'] ) ) 
			? $instance['skin'] : 'swissRail';

		if ( isset($instance['align']) )
			$align = $instance['align'];
		else
			$align = false;

		if ( isset($instance['subtext']) )
			$subtext =  '<div style="width:100%;text-align:center;padding-bottom:10px">' . apply_filters('widget_text', $instance['subtext'], $instance) . '</div>';
		else
			$subtext = '';

		// add custom skin parameters to the plugin skins array
		if ( 'custom_'.$this->number == $skin )
			self::$advanced_skins_config[$skin] = $instance['custom_skin'];

		// set footer script flags
		self::$add_script = true;

		if ( in_array( $skin, self::$more_skins ) )
			self::$add_moreskins = true;
		if ( isset( self::$advanced_skins_config[$skin] ) )
			self::$add_customskins = true;

		$output = self::canvas( array(
					'skin' => $skin,
					'radius' => $instance['radius'],
					'noseconds' => $instance['noseconds'],
					'gmtoffset' => $instance['gmtoffset'],
					'showdigital' => $instance['showdigital'],
					'scale' => $instance['scale']
					), $align, $subtext );
		
		return apply_filters( 'coolclock_widget_advanced', $output, $args, $instance );

	}

	static function update( $new_instance, $instance ) {

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['skin'] = strip_tags( $new_instance['skin'] );
		$instance['custom_skin'] = strip_tags( $new_instance['custom_skin'] );
		$instance['radius'] = ( (int) $new_instance['radius'] < 5 ) ? 5 : (int) $new_instance['radius'];
		$instance['noseconds'] = (bool) $new_instance['noseconds'];
		$instance['gmtoffset'] = ( $new_instance['gmtoffset'] == '' ) ? '' : (float) $new_instance['gmtoffset'];
		$instance['showdigital'] = (bool) $new_instance['showdigital'];
		$instance['scale'] = strip_tags( $new_instance['scale'] );
		$instance['align'] = strip_tags( $new_instance['align'] );

		if ( current_user_can('unfiltered_html') )
			$instance['subtext'] =  $new_instance['subtext'];
		else 
			// wp_filter_post_kses() expects slashed
			$instance['subtext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['subtext']) ) ); 
		

    		return apply_filters( 'coolclock_widget_update_advanced', $instance, $new_instance );

	}

	static function form( $obj, $instance, $defaults ) {

		$defaults = array ( 
				'title' => '',
				'custom_skin' => '',
			);
		
		$defaults = array_merge( $defaults, self::$defaults, self::$advanced_defaults );
	
		$instance = wp_parse_args( (array) $instance, $defaults );
	 
		$title = esc_attr( $instance['title'] );
		$subtext = esc_attr( $instance['subtext'] );
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
		    
	    	$skins = array_merge( self::$default_skins, self::$more_skins, self::$advanced_skins );

		// Translatable type names go here
		$type_names = array (
		    		'linear' => __('Linear','coolclock'),
		    		'logClock' => __('Logarithmic','coolclock'),
		    		'logClockRev' => __('Logarithmic reversed','coolclock')
		    	);
		
		// Title
		$output = '<p><label for="' . $obj->get_field_id('title') . '">' . __('Title:') . '</label> ';
		$output .= '<input class="widefat" id="' . $obj->get_field_id('title') . '" name="' . $obj->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
		
		// Clock settings
		$output .= '<p><strong>' . __('Clock', 'coolclock') . '</strong></p>';
		$output .= '<p><label for="' . $obj->get_field_id('skin') . '">' . __('Skin:', 'coolclock') . '</label> ';
		$output .= '<select class="select" id="' . $obj->get_field_id('skin') . '" name="' . $obj->get_field_name('skin') . '">';
		foreach ($skins as $value) {
			$output .= '<option value="' . $value . '"';
			$output .= ( $value == $instance['skin'] ) ? ' selected="selected">' : '>';
			$output .= ( isset($skin_names[$value]) ) ? $skin_names[$value] : $value;
			$output .= '</option>';
		} unset($value);
		$output .= '<option value="custom_' . $obj->number . '"';
		$output .= ( 'custom_'.$obj->number == $instance['skin'] ) ? ' selected="selected">' : '>';
		$output .= __('Custom (define below)', 'coolclock') . '</option></select></p>';
		
		// Custom skin field
		$output .= '<p><label for="' . $obj->get_field_id('custom_skin') . '">' . __('Custom skin parameters:', 'coolclock') . '</label> ';
		$output .= '<textarea class="widefat" id="' . $obj->get_field_id('custom_skin') . '" name="' . $obj->get_field_name('custom_skin') . '">' . $custom_skin . '</textarea></p>';
		
		// Radius
		$output .= '<p><label for="' . $obj->get_field_id('radius') . '">' . __('Radius:', 'coolclock') . '</label> ';
		$output .= '<input class="small-text" id="' . $obj->get_field_id('radius') . '" name="' . $obj->get_field_name('radius') . '" type="number" min="10" value="' . $instance['radius'] . '" /></p>';
		
		// Second hand
		$output .= '<p><input id="' . $obj->get_field_id('noseconds') . '" name="' . $obj->get_field_name('noseconds') . '" type="checkbox" value=';
		$output .= ( $instance['noseconds'] ) ? '"true" checked="checked" />' : '"false" />';
		$output .= ' <label for="' . $obj->get_field_id('noseconds') . '">' .  __('Hide second hand', 'coolclock') . '</label></p>';

		// Show digital
		$output .= '<p><input id="' . $obj->get_field_id('showdigital') . '" name="' . $obj->get_field_name('showdigital') . '" type="checkbox" value=';
		$output .= ( $instance['showdigital'] ) ? '"true"  checked="checked" />' : '"false" />';
		$output .= ' <label for="' . $obj->get_field_id('showdigital') . '">' . __('Show digital time', 'coolclock') . '</label></p>';
		
		// Show digital
		$output .= '<p><label for="' . $obj->get_field_id('gmtoffset') . '">' . __('GMT offset:', 'coolclock') . '</label> ';
		$output .= '<input class="small-text" id="' . $obj->get_field_id('gmtoffset') . '" name="' . $obj->get_field_name('gmtoffset') . '" type="number" step="0.5" value="' . $instance['gmtoffset'] . '" /> ' . __('(leave blank for local time)', 'coolclock') . '</p>';
		
		// Scale
		$output .= '<p><label for="' . $obj->get_field_id('scale') . '">' . __('Scale:', 'coolclock') . '</label> ';
		$output .= '<select class="select" id="' . $obj->get_field_id('scale') . '" name="' . $obj->get_field_name('scale') . '">';
		foreach (self::$clock_types as $value) {
			$output .= '<option value="' . $value . '"';
			$output .= ( $value == $instance['scale'] ) ? ' selected="selected">' : '>';
			$output .= ( isset($type_names[$value]) ) ? $type_names[$value] : $value;
			$output .= '</option>';
		} unset($value);
		$output .= '</select></p>';

		// Align
		$output .= '<p><label for="' . $obj->get_field_id('align') . '">' . __('Align:', 'coolclock') . '</label> ';
		$output .= '<select class="select" id="' . $obj->get_field_id('align') . '" name="' . $obj->get_field_name('align') . '">';
		$output .= '<option value=""';
		$output .= ( $instance['align'] == '' ) ? ' selected="selected">' : '>';
		$output .= __('none', 'coolclock') . '</option>';		
		$output .= '<option value="left"';
		$output .= ( $instance['align'] == 'left' ) ? ' selected="selected">' : '>';
		$output .= __('left', 'coolclock') . '</option>';
		$output .= '<option value="right"';
		$output .= ( $instance['align'] == 'right' ) ? ' selected="selected">' : '>';
		$output .= __('right', 'coolclock') . '</option>';
		$output .= '<option value="center"';
		$output .= ( $instance['align'] == 'center' ) ? ' selected="selected">' : '>';
		$output .= __('center', 'coolclock') . '</option>';
		$output .= '</select></p>';

		// Subtext
		$output .= '<p><label for="' . $obj->get_field_id('subtext') . '">' . __('Subtext:') . '</label> ';
		$output .= '<input class="widefat" id="' . $obj->get_field_id('subtext') . '" name="' . $obj->get_field_name('subtext') . '" type="text" value="' . $subtext . '" /> ' . __('(basic HTML allowed)', 'coolclock') . '</p>';

		// Advanced filter
		if ( class_exists( 'CoolClockAdvanced' ) ) // add an upgrade notice
			$advanced_form .= '<p><strong>' . __('Background') . '</strong></p><p><strong>' . __('Pease upgrade the CoolClock - Pro extension.', 'coolclock') . '</strong> ' . __('You can download the new version using the remaining download credits and the link that you have received in the confirmation email after your first purchase.', 'coolclock') . ' <a href="http://status301.net/contact-en/">' . __('If you do not have that email anymore, please contact us.', 'coolclock') . '</a></p>' . '<p><strong>' . __('Do NOT resave widget settings before upgrading the pro extension or your advanced settings will be lost!', 'coolclock') . '</strong>';
		else
	    		$advanced_form = '<p><strong>' . __('Background') . '</strong></p><p><a href="http://status301.net/wordpress-plugins/coolclock-pro/">' . __('Available in the Pro extension &raquo;', 'coolclock') . '</a></p>';
		
		$output .= apply_filters( 'coolclock_widget_form_advanced', $advanced_form, $obj, $instance, $defaults );

		return $output;
	}

	static function go() {

		add_action('plugins_loaded', create_function( '', "return load_plugin_textdomain( 'coolclock', false, dirname(plugin_basename( __FILE__ )).'/languages' );" ) );

		add_action( 'init', array(__CLASS__, 'init' ) );
	
		add_action( 'widgets_init', create_function( '', 'return register_widget("CoolClock_Widget");' ) );

	}
 
	static function init() {	

		add_shortcode( 'coolclock', array( __CLASS__, 'handle_shortcode' ) );
		// allow shortcode in text widgets
		add_filter('widget_text', 'do_shortcode', 11);

		wp_register_script( 'coolclock', plugins_url('/js/coolclock.min.js', __FILE__), array('jquery'), self::$script_version, true );
		wp_register_script( 'coolclock-moreskins', plugins_url('/js/moreskins.min.js', __FILE__), array('coolclock'), self::$script_version, true );
		// could use http://cdnjs.cloudflare.com/ajax/libs/flot/0.7/excanvas.min.js here...
		wp_register_script( 'excanvas', plugins_url( '/js/excanvas.compiled.js', __FILE__ ), array(), '3', true );

		add_action( 'wp_footer', array( __CLASS__, 'print_scripts' ) );

	}

	static function handle_shortcode( $atts ) {

		if ( isset( $atts['align'] ) )
			$align = $atts['align'];
		else
			$align = false;

		if ( isset( $atts['subtext'] ) )
			$subtext = '<div style="width:100%;text-align:center;padding-bottom:10px">' . $atts['subtext'] . '</div>';
		else
			$subtext = '';

		$atts = shortcode_atts( self::$defaults, $atts );

		// set footer script flags
		self::$add_script = true;
		if ( in_array( $atts['skin'], self::$more_skins ) )
			self::$add_moreskins = true;
		if ( isset( self::$advanced_skins_config[$atts['skin']] ) )
			self::$add_customskins = true;

		// return the clock unless it's a feed
		if ( !is_feed() ) {
			return self::canvas( $atts, $align, $subtext );
		} else {
			return '';	
		}

	}
	
	static function canvas( $atts, $align = false, $subtext = '' ) {

		$atts = shortcode_atts( self::$defaults, $atts );

		extract( $atts );

		$clock_width = 2 * $radius . 'px'; 

		$output = '<div';
		
		// align class
		$output .= ( $align ) ? ' class="align' . $align . '"' : '';
		$output .= ' style="width:' . $clock_width . ';height:auto"><canvas class="CoolClock:' . $skin . ':' . $radius . ':';
		// parameters
		$output .= ( $noseconds == 'true' ||  $noseconds == '1' ) ? 'noSeconds:' : ':';
		$output .= $gmtoffset.':';
		$output .= ( $showdigital == 'true' || $showdigital == '1' ) ? 'showDigital' : '';

		// set type
		switch ( $scale ) {
			case 'linear':
			default:
				break;
			case 'logClock':
				$output .= ':logClock';
				break;
			case 'logClockRev':
				$output .= ':logClockRev';
		}

		$output .= '"></canvas>'.$subtext.'</div>';
		
		// before returning, try including excanvas which needs to be there before the first canvas...
		self::print_excanvas();

		return $output;

	}
 
	static function print_excanvas() {
		
		if ( self::$done_excanvas )
			return;

		echo '<!--[if IE]>';
		wp_print_scripts( 'excanvas' );
		echo '<![endif]-->
';
		self::$done_excanvas = true;

	}

	static function print_scripts() {

		if ( ! self::$add_script )
			return;
 
		wp_print_scripts( 'coolclock' );

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

	}

}
 
CoolClock::go();

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
					'id_base' => 'coolclock-widget' 
					) 
				);  	
	}
 
	/** @see WP_Widget::widget -- do not rename this */
	function widget( $args, $instance ) {

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		// Print output
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo CoolClock::widget( $args, $instance );

		echo $after_widget;

	}

	/** @see WP_Widget::update -- do not rename this */
	function update( $new_instance, $old_instance ) {

		return CoolClock::update( $new_instance, $old_instance );

	}

	/** @see WP_Widget::form -- do not rename this */
	function form( $instance ) {

		// Print output
		echo CoolClock::form( $this, $instance, $defaults );

	}

}

