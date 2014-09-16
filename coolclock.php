<?php
/*
Plugin Name: CoolClock
Plugin URI: http://status301.net/wordpress-plugins/coolclock/
Description: Add an analog clock to your sidebar.
Text Domain: coolclock
Domain Path: languages
Version: 2.9.8
Author: RavanH
Author URI: http://status301.net/
*/

/**
 * CoolClock Class
 */
class CoolClock {

	static $plugin_version = '2.9.8';

	static $script_version = '3.0.0-pre3';

	static $add_script;

	static $add_moreskins;
	
	static $done_excanvas = false;

	static $defaults = array (
			'skin' => 'swissRail',
			'radius' => 100,
			'noseconds' => false,	// Hide seconds
			'gmtoffset' => '',		// GMT offset
			'showdigital' => '',	// Show digital time or date
			'scale' => 'linear'		// Define type of clock linear/logarithmic/log reversed
		);

	static $showdigital_options = array (
				'' => '',
				'digital12' => 'showDigital'
			);

	static $advanced;

	static $advanced_defaults = array (
				'subtext' => '',
				'align' => 'center'
			);

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
	    		'watermelon',
	    		'mister'
	    	);

	static $advanced_skins = array ();

	static $advanced_skins_config = array ();

	static $clock_types = array (
	    		'linear' => '',
	    		'logClock' => 'logClock',
	    		'logClockRev' => 'logClockRev'
	    	);

	/** 
	 * MAIN 
	 */

	static function canvas( $atts )
	{
		extract( $atts );

		$output = '<div';
		
		// align class ans style
		$output .= ( $align ) ? ' class="align' . $align . '"' : '';
		$output .= ' style="width:' . 2 * $radius . 'px;height:auto">';
		// canvas parameters
		$output .= '<canvas class="CoolClock:' . $skin . ':' . $radius . ':';
		$output .= ( $noseconds == 'true' ||  $noseconds == '1' ) ? 'noSeconds:' : ':';
		$output .= $gmtoffset;
		
		// show digital
		if ( $showdigital == 'true' || $showdigital == '1' )
			$showdigital = 'digital12'; // backward compat

		if ( isset(self::$showdigital_options[$showdigital]) )
			$output .= ':'.self::$showdigital_options[$showdigital];
		else
			$output .= ':';

		// set type
		if ( isset(self::$clock_types[$scale]) )
			$output .= ':'.self::$clock_types[$scale];
		else
			$output .= ':';

/*		switch ( $scale ) {
			case 'linear':
			default:
				break;
			case 'logClock':
				$output .= ':logClock';
				break;
			case 'logClockRev':
				$output .= ':logClockRev';
		}
*/
		$output .= '"></canvas>';
		$output .= ( $subtext ) ? '<div style="width:100%;text-align:center;padding-bottom:10px">' . $subtext . '</div></div>' : '</div>';
		
		// before returning, try including excanvas which needs to be there before the first canvas...
		self::print_excanvas();

		return $output;
	}
 
	static function print_excanvas()
	{
		if ( self::$done_excanvas )
			return;

		echo '<!--[if lte IE 8]>';
		wp_print_scripts( 'excanvas' );
		echo '<![endif]-->
';
		self::$done_excanvas = true;
	}

	static function print_scripts()
	{
		if ( ! self::$add_script )
			return;
 
		wp_print_scripts( 'coolclock' );

		if ( self::$add_moreskins )
			wp_print_scripts('coolclock-moreskins');

		if ( is_array( self::$advanced_skins_config ) && !empty( self::$advanced_skins_config ) ) {
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

	/** 
	 * SHORTCODE 
	 */

	static function handle_shortcode( $atts, $content = null )
	{
		if ( is_feed() )
			return '';	

		$atts = shortcode_atts( array_merge( self::$defaults, self::$advanced_defaults ), $atts );

		// set footer script flags
		self::$add_script = true;

		$skin = ( isset( $atts['skin'] ) ) 
			? $atts['skin'] : 'swissRail';

		if ( in_array( $atts['skin'], self::$more_skins ) )
			self::$add_moreskins = true;

		$output = self::canvas( $atts );
		return apply_filters( 'coolclock_shortcode_advanced', $output, $atts, $content );
	}

	static function no_wptexturize($shortcodes)
	{
		$shortcodes[] = 'coolclock';
		return $shortcodes;
	}

	/** 
	 * WIDGET 
	 */
	
	static function widget( $args, $instance, $number )
	{
		$skin = ( isset( $instance['skin'] ) ) 
			? $instance['skin'] : 'swissRail';

		// add custom skin parameters to the plugin skins array
		if ( 'custom_'.$number == $skin )
			self::$advanced_skins_config[$skin] = wp_strip_all_tags( $instance['custom_skin'], true );

		// set footer script flags
		self::$add_script = true;

		if ( in_array( $skin, self::$more_skins ) )
			self::$add_moreskins = true;
			
		$output = self::canvas( array(
					'skin' => $skin,
					'radius' => $instance['radius'],
					'noseconds' => $instance['noseconds'],
					'gmtoffset' => $instance['gmtoffset'],
					'showdigital' => $instance['showdigital'],
					'scale' => $instance['scale'],
					'align' => $instance['align'],
					'subtext' => apply_filters('widget_text', $instance['subtext'], $instance)
					) );
		
		return apply_filters( 'coolclock_widget_advanced', $output, $args, $instance );
	}

	static function update( $new_instance, $instance )
	{
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['skin'] = strip_tags( $new_instance['skin'] );
		$instance['custom_skin'] = strip_tags( $new_instance['custom_skin'] );
		$instance['radius'] = ( (int) $new_instance['radius'] < 5 ) ? 5 : (int) $new_instance['radius'];
		$instance['noseconds'] = (bool) $new_instance['noseconds'];
		$instance['gmtoffset'] = ( $new_instance['gmtoffset'] == '' ) ? '' : (float) $new_instance['gmtoffset'];
		$instance['showdigital'] = strip_tags( $new_instance['showdigital'] );
		$instance['scale'] = strip_tags( $new_instance['scale'] );
		$instance['align'] = strip_tags( $new_instance['align'] );

		if ( current_user_can('unfiltered_html') )
			$instance['subtext'] =  $new_instance['subtext'];
		else 
			// wp_filter_post_kses() expects slashed
			$instance['subtext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['subtext']) ) ); 
		

    	return apply_filters( 'coolclock_widget_update_advanced', $instance, $new_instance );
	}

	static function form( $obj, $instance, $defaults = array('title'=>'','custom_skin'=>'') )
	{
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
		    		'mister' => __('Mister by Carl Lister','coolclock'),
		    		'minimal' => __('Minimal','coolclock')
		    	);
		    
	    	$skins = array_merge( self::$default_skins, self::$more_skins, self::$advanced_skins );

		// Translatable type names go here
		$type_names = array (
		    		'linear' => __('Linear','coolclock'),
		    		'logClock' => __('Logarithmic','coolclock'),
		    		'logClockRev' => __('Logarithmic reversed','coolclock')
		    	);
		
		// Translatable show options go here
		$showdigital_names = array (
					'' => __('none','coolclock'),
		    		'digital12' => __('time (am/pm)','coolclock'),
		    		'digital24' => __('time (24h)','coolclock'),
		    		'date' => __('date','coolclock')
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
		if ( $instance['showdigital'] == 'true' || $instance['showdigital'] == '1' )
			$instance['showdigital'] = 'digital12'; // backward compat
		
		$output .= '<p><label for="' . $obj->get_field_id('showdigital') . '">' . __('Show digital:', 'coolclock') . '</label> ';
		$output .= '<select class="select" id="' . $obj->get_field_id('showdigital') . '" name="' . $obj->get_field_name('showdigital') . '">';
		foreach (self::$showdigital_options as $key => $value) {
			$output .= '<option value="' . $key . '"';
			$output .= ( $key == $instance['showdigital'] ) ? ' selected="selected">' : '>';
			$output .= ( isset($showdigital_names[$key]) ) ? $showdigital_names[$key] : $value;
			$output .= '</option>';
		} unset($value);
		$output .= '</select></p>';

		// USe GMT offset
		$output .= '<p><label for="' . $obj->get_field_id('gmtoffset') . '">' . __('GMT offset:', 'coolclock') . '</label> ';
		$output .= '<input class="small-text" id="' . $obj->get_field_id('gmtoffset') . '" name="' . $obj->get_field_name('gmtoffset') . '" type="number" step="0.5" value="' . $instance['gmtoffset'] . '" /> ' . __('(leave blank for local time)', 'coolclock') . '</p>';
		
		// Scale
		$output .= '<p><label for="' . $obj->get_field_id('scale') . '">' . __('Scale:', 'coolclock') . '</label> ';
		$output .= '<select class="select" id="' . $obj->get_field_id('scale') . '" name="' . $obj->get_field_name('scale') . '">';
		foreach (self::$clock_types as $key => $value) {
			$output .= '<option value="' . $key . '"';
			$output .= ( $key == $instance['scale'] ) ? ' selected="selected">' : '>';
			$output .= ( isset($type_names[$key]) ) ? $type_names[$key] : $value;
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
		$output .= '<p><label for="' . $obj->get_field_id('subtext') . '">' . __('Subtext:', 'coolclock') . '</label> ';
		$output .= '<input class="widefat" id="' . $obj->get_field_id('subtext') . '" name="' . $obj->get_field_name('subtext') . '" type="text" value="' . $subtext . '" /> ' . __('(basic HTML allowed)', 'coolclock') . '</p>';

		// Advanced filter
		if ( class_exists( 'CoolClockAdvanced' ) ) // add an upgrade notice
			$advanced_form = '<p><strong>' . __('Background') . '</strong></p><p><strong>' . __('Please upgrade the CoolClock - Advanced extension.', 'coolclock') . '</strong> ' . __('You can download the new version using the remaining download credits and the link that you have received in the confirmation email after your first purchase.', 'coolclock') . ' <a href="http://status301.net/contact-en/">' . __('If you do not have that email anymore, please contact us.', 'coolclock') . '</a></p>' . '<p><strong>' . __('Do NOT resave widget settings before upgrading the Advanced extension or your advanced settings will be lost!', 'coolclock') . '</strong>';
		else
	    	$advanced_form = '<p><strong>' . __('Background') . '</strong></p><p><a href="http://status301.net/wordpress-plugins/coolclock-advanced/">' . __('Available in the Advanced extension &raquo;', 'coolclock') . '</a></p>';
		
		$output .= apply_filters( 'coolclock_widget_form_advanced', $advanced_form, $obj, $instance, $defaults );

		return $output;
	}

	/**
	 * INIT
	 */

	static function go()
	{
		add_action('plugins_loaded', create_function( '', "return load_plugin_textdomain( 'coolclock', false, dirname(plugin_basename( __FILE__ )).'/languages' );" ) );
		add_action( 'init', array(__CLASS__, 'init' ) );
		add_action( 'widgets_init', create_function( '', 'return register_widget("CoolClock_Widget");' ) );
	}
 
	static function init()
	{	
		add_shortcode( 'coolclock', array( __CLASS__, 'handle_shortcode' ) );

		// allow shortcode in text widgets
		add_filter('widget_text', 'do_shortcode', 11);

		// prevent texturizing shortcode content
		add_filter( 'no_texturize_shortcodes', array( __CLASS__, 'no_wptexturize') );

		if ( defined('WP_DEBUG') && false != WP_DEBUG ) {
			wp_register_script( 'coolclock', plugins_url('/js/coolclock.js', __FILE__), array('jquery'), self::$script_version, true );
			wp_register_script( 'coolclock-moreskins', plugins_url('/js/moreskins.js', __FILE__), array('coolclock'), self::$script_version, true );
			wp_register_script( 'excanvas', plugins_url( '/js/excanvas.js', __FILE__ ), array(), '73', true );
		} else {
			wp_register_script( 'coolclock', plugins_url('/js/coolclock.min.js', __FILE__), array('jquery'), self::$script_version, true );
			wp_register_script( 'coolclock-moreskins', plugins_url('/js/moreskins.min.js', __FILE__), array('coolclock'), self::$script_version, true );
			wp_register_script( 'excanvas', plugins_url( '/js/excanvas.min.js', __FILE__ ), array(), '73', true );
		}

		add_action( 'wp_footer', array( __CLASS__, 'print_scripts' ) );
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
		$number = $this->number;

		// Print output
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo CoolClock::widget( $args, $instance, $number );

		echo $after_widget;

	}

	/** @see WP_Widget::update -- do not rename this */
	function update( $new_instance, $old_instance ) {

		return CoolClock::update( $new_instance, $old_instance );

	}

	/** @see WP_Widget::form -- do not rename this */
	function form( $instance ) {

		// Print output
		echo CoolClock::form( $this, $instance );

	}

}
