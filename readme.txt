=== CoolClock - a Javascript Analog Clock ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=CoolClock%20for%20WordPress&item_number=1%2e3%2e4%2e9&no_shipping=0&tax=0&charset=UTF%2d8&currency_code=EUR
Tags: clock, analog clock, coolclock, javascript, jquery, widget, shortcode, logarithmic clock
Requires at least: 2.9
Tested up to: 3.7
Stable tag: 2.9.2

Show an analog clock on your WordPress site sidebar or in post and page content.

== Description ==

This plugin integrates [CoolClock - The Javascript Analog Clock](http://randomibis.com/coolclock/) into your WordPress site. You can add it as a **widget** to your sidebar or insert it into your posts and pages with a **shortcode**. See [the FAQ's](http://wordpress.org/extend/plugins/coolclock/faq/) for available shortcode parameters and how to build the clock into your theme.

= Features =

- Can be added via a widget, shortcode or theme function
- No flash, meaning compatible with pads and most other javascript enabled mobile devices
- 21 different skins to choose from or
- Custom skin option to create your own skin style
- Linear or logarithmic time scale

See [the FAQ's](http://wordpress.org/extend/plugins/coolclock/faq/) to learn how to configure your own skin settings.

= Pro features =

- Custom background image and/or
- Background color 
- Border radius
- One extra skin for use with background image
- Advanced positioning options

Pro features come with the [CoolClock - Pro extension](http://status301.net/wordpress-plugins/coolclock-pro/).

= Translators =

- **Dutch** * Author: [R.A. van Hagen](http://status301.net/nl/) (version 2.0)
- **French** * Author: [R.A. van Hagen](http://phareo.eu) (version 2.0)

Please [contact me](http://status301.net/contact-en/) to submit your translation and get mentioned here :)


== Installation ==

= Wordpress =

Quick installation: [Install now](http://coveredwebservices.com/wp-plugin-install/?plugin=coolclock) !

 &hellip; OR &hellip;

Search for "coolclock" and install with that slick **Plugins > Add New** back-end page.

 &hellip; OR &hellip;

Follow these steps:

 1. Download archive.

 2. Upload the zip file via the Plugins > Add New > Upload page &hellip; OR &hellip; unpack and upload the complete directory with your favourite FTP client to the /plugins/ folder.

 3. Activate the plugin on the Plug-ins page.

Now visit your Widgets admin page and add the Analog Clock widget to your sidebar. :)


== Frequently Asked Questions ==

= Where do I start? =

There is no options page. Just go to your Appearance > Widgets admin page and find the new Analog Clock widget. Add it to your sidebar and change settings if you want to see another than the default clock.

Other ways to integrate a clock into your site are ahortcodes or a theme function. See instructions below.

= What options does the widget have? =

First of all, you can pick a preset skin. There are 21 skins made by other users and one Minimal skin that only shows the clock arms, that can be useful for placing over a custom background image. Then there are:

- Custom skin parameters - see question below;
- Radius - changes the clock size;
- Hide second hand;
- Show digital time;
- GMT Offset - use this if you want one or more clocks to show the time for other timezones;
- Scale - linear is our standard clock scale, the other two show a logarithmic time scale;
- Align - left, center or right;
- Subtext - optional text, centered below the clock.

Then there are extra options availabe in the [CoolClock - Pro extension](http://status301.net/wordpress-plugins/coolclock-pro/) which allow for more customisation:

- Background image - define the full URL or path to an image to serve as background;
- Repeat image;
- Background position - center, top, right, bottom or left of the wrapping div (define div size below);
- Width and height - define the size of the wrapping div that holds the background image;
- Background color - define a color value in hex or rgb(a) format, or a css color name;
- Border radius - optional rounded corners, higher is rounder;
- Clock position relative to background - here you can position the clock relative to top and left border of the wrapping div (as defined above) that holds the background image.


= How can I create a custom skin? =

Here are your first steps into the wonderous world of CoolClock skins ;)

**Step 1.** Copy the following code to a simple unformatted text document (.txt) on your computer.

`
outerBorder: { lineWidth: 1, radius:95, color: "black", alpha: 1 },
smallIndicator: { lineWidth: 2, startAt: 89, endAt: 93, color: "black", alpha: 1 },
largeIndicator: { lineWidth: 4, startAt: 80, endAt: 93, color: "black", alpha: 1 },
hourHand: { lineWidth: 8, startAt: -15, endAt: 50, color: "black", alpha: 1 },
minuteHand: { lineWidth: 7, startAt: -15, endAt: 75, color: "black", alpha: 1 },
secondHand: { lineWidth: 1, startAt: -20, endAt: 85, color: "red", alpha: 1 },
secondDecoration: { lineWidth: 1, startAt: 70, radius: 4, fillColor: "red", color: "red", alpha: 1 }
`

**Step 2.** These parameters are the ones from the swissRail skin. Now go and change some parameter values like lineWidth or start/endAt points. The numbers refer to a percentage of the radius, so startAt: 0, endAt: 50 means a line from the center to 50% of the way to the edge. Alpha means the transparency of the element where alpha: 1 means solid. For example alpha: 0.5 means 50% transparent.

**Step 3.** Go to you the Analog Clock widget, select *Skin: Custom* and copy your modified code (all of it, not just the modified parts!) into the field **Custom skin parameters**. Then save the widget and reload your website front page (or wherever the clock is visible) to see the result of your work.

See the preset skins in [moreskins.js](http://randomibis.com/coolclock/moreskins.js) for more examples. And have fun tweaking!


= Can I share this fantastic custom skin I created? =

If you made a nice skin and would like to share it, then send it to the script creator at simon dot baird at gmail dot com or paste the custom parameters into a [Review](http://wordpress.org/support/view/plugin-reviews/coolclock). 

Thanks for sharing! :)


= Can I insert a clock in posts or pages? =

Yes, there is a shortcode available. Start with a default clock by pasting `[coolclock]` into a post.

The following parameters are available:

- **skin** -- must be one of these: 'swissRail' (default skin), 'chunkySwiss', 'chunkySwissOnBlack', 'fancy', 'machine', 'simonbaird_com', 'classic', 'modern', 'simple', 'securephp', 'Tes2', 'Lev', 'Sand', 'Sun', 'Tor', 'Cold', 'Babosa', 'Tumb', 'Stone', 'Disc' or 'watermelon'. If the Pro extension is activated, there is also 'minimal' available. Please note that these names are _case sensitive_.
- **radius** -- a number to define the clock radius. Do not add 'px' or any other measure descriptor.
- **noseconds** -- set to true (or 1) to hide the second hand
- **gmtoffset** -- a number to define a timezone relative the Greenwhich Mean Time. Do not set this parameter to default to local time.
- **showdigital** -- set to true (or 1) to show the time in digital format too
- **scale** -- must be one of these: 'linear' (default scale), 'logClock' or  'logClockRev'. Linear is our normal clock scale, the other two show a logarithmic time scale
- **subtext** -- optional text, centered below the clock
- **align** -- sets floating of the clock: 'left', 'right' or 'center'

Example: `[coolclock skin="chunkySwissOnBlack" radius="140" showdigital=true align="left"]`

Then there are extra options availabe in the [CoolClock - Pro extension](http://status301.net/wordpress-plugins/coolclock-pro/) which allow for more customisation:

- **background_image** - define full URL or path to an image to serve as background
- **background_height** - give a height in pixels (default: auto = clock plus subtext height)
- **background_width** - give a width in pixels  (default: clock width)
- **background_color** - define a CSS color value in hex, rgb(a) format, or color name
- **background_position** - CSS positioning like 'left top', 'bottom', '10% 70%' or '10px 20px' (default: 'left top')
- **background_repeat** - repeat, repeat-x, repeat-y, no-repeat (default: no-repeat)
- **background_border_radius** - optional rounded corners value, higher is rounder

Example: `[coolclock skin="minimal" radius="63" align="left" background_image="http://i35.tinypic.com/990wtx.png"]`


= I'm building my own theme. Is there a theme function available? =

Yes, you can use a built-in WordPress function that parses a shortcode. To place the same clock as in the shortcode example above, anywhere in your theme, use this:

`
<?php echo do_shortcode('[coolclock skin="chunkySwiss" radius="140" showdigital=true align="left"]'); ?>
`


== Known Issues ==

1. When IE 8 is manually put or forced (through X-UA-Compatibility meta tag or response header) into Compatibility mode, the Clock will --even though the canvas area is put in place-- remain invisible.

2. When a shortcode is not placed on its own line but on the same line with text, image or even another shortcode, then the output (div with canvas tag) will be wrapped inside a paragraph tag. While most browsers do not have a problem displaying the clock, this *will* cause a validation error.

Please report any other issues on the [Support page](http://wordpress.org/support/plugin/coolclock).


== Screenshots ==

1. Example analog clock in sidebar. The background logo is added with the [CoolClock - Pro extension](http://status301.net/wordpress-plugins/coolclock-pro/).

2. Widget settings. The background options are availabe in the [CoolClock - Pro extension](http://status301.net/wordpress-plugins/coolclock-pro/).


== Upgrade Notice ==

= 2.9.3 =
Using global tick for multiple clocks.

== Changelog ==

= 2.9.4 =
* TODO: RECONSIDER FIX: prevent shortcode canvas being wrapped in P tags TODO
* FIX: undefined index in widget form
* NEW: Use Globaltick branch script version 3.0.0-pre
* NEW: Clock skin shared by user MrCarlLister

= 2.9.2 =
* FIX: Thread between tip of the second hand and 3 o'clock in IE
* Shortcode filter

= 2.9 =
* BUGFIX: excanvas included too late
* CoolClock.js version 3.0.0-pre
* Allow shortcode in text widget
* NEW: Subtext option
* NEW: Widget align option

= 2.0 =
* NEW: logClock option

= 1.1 =
* Minified javascript

= 1.0 =
* Sidebar widget overhaul
* Class
* NEW: Shortcode

= 0.1 =
* First implementation of CoolClock in sidebar widget

