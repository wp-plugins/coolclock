=== CoolClock - a Javascript Analog Clock ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=CoolClock%20for%20WordPress&item_number=1%2e3%2e4%2e9&no_shipping=0&tax=0&charset=UTF%2d8&currency_code=EUR
Tags: fancybox, lightbox, gallery, image, photo, video, flash, nextgen, overlay, youtube, vimeo, dailymotion, pdf, iframe, swf, jquery
Requires at least: 2.9
Tested up to: 3.4.2
Stable tag: 0.1

Show an analog clock on your WordPress site.

== Description ==

This plugin integrates [CoolClock - The Javascript Analog Clock](http://randomibis.com/coolclock/) into your WordPress site. Currently you can add it as a Widget to your sidebar. A shortcode to insert a clock into your posts and pages and a PHP function to your custom theme is planned for the next release.

There are 22 different preset skins to choose from plus a Custom skin option to create your own. See [the FAQ's](http://wordpress.org/extend/plugins/coolclock/faq/) to learn how to configure your own skin settings.

CoolClock is writen in javascript (Prototype and a little jQuery) and uses no flash, so it is compatible with pads and other mobile devices.

**Recommended:**
For increased site performance, simply install and activate the plugin [Use Google Libraries](http://wordpress.org/extend/plugins/use-google-libraries/) to load jQuery from Googles CDN.

= Translators =

- **Dutch** * Author: [R.A. van Hagen](http://phareo.eu) (version 0.1)

Please contact me to submit your translation and get mentioned here :)

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


= What options does the widget have? =

First of all, you can pick a preset skin. There are 21 skins made by other users and one Minimal skin that only shows the clock arms, that can be useful for placing over a custom background image. Then there are:

- Custom skin parameters - see question below
- Radius - changes the clock size
- Hide second hand
- Show digital time
- GMT Offset - use this if you want one or more clocks to show the time for other timezones. 

Then there are some background options. You can ignore these if you are happy with one of the preset clock skins. But if you want to create your own clock then these can serve you well:

- Background image - Define the full URL or path to an image to serve as background
- Repeat image
- Background position - center, top, right, bottom or left of the wrapping div (define div size below)
- Width and height - define the size of the wrapping div that carries the background image
- Clock position relative to background - here you can position the clock relative to top or bottom and left or right border of the wrapping div (as defined above)


= How can I create a custom skin? =

Select **Skin: Custom** and copie the following code to a text document on your computer.

`
outerBorder: { lineWidth: 1, radius:95, color: "black", alpha: 1 },
smallIndicator: { lineWidth: 2, startAt: 89, endAt: 93, color: "black", alpha: 1 },
largeIndicator: { lineWidth: 4, startAt: 80, endAt: 93, color: "black", alpha: 1 },
hourHand: { lineWidth: 8, startAt: -15, endAt: 50, color: "black", alpha: 1 },
minuteHand: { lineWidth: 7, startAt: -15, endAt: 75, color: "black", alpha: 1 },
secondHand: { lineWidth: 1, startAt: -20, endAt: 85, color: "red", alpha: 1 },
secondDecoration: { lineWidth: 1, startAt: 70, radius: 4, fillColor: "red", color: "red", alpha: 1 }
`

These parameters are the ones from the swissRail skin. Now go and change some parameter values like lineWidth or start/endAt points. The numbers refer to a percentage of the radius, so startAt: 0, endAt: 50 means a line from the center to 50% of the way to the edge. Alpha means the transparency of the element where alpha: 1 means solid. For example alpha: 0.5 means 50% transparent.

No copy your modified parameters into the field **Custom skin parameters:** and save the widget. Reload your website front page (or wherever the clock is visible) to see the result of your work.

See for more examples [moreskins.js](http://randomibis.com/coolclock/moreskins.js).


= Can I share this fantastic skin I created? =

If you make a nice skin and would like to share it, then send it to the script creator at simon dot baird at gmail dot com or paste the custom parameters into a new ticket (mark it as 'not a support question') on the Support tab. 

Thanks for sharing! :)


== Known Issues ==

Please report any issues on the Support tab.


== Screenshots ==

1. Example analog clock in sidebar.

2. Widget settings.

== Upgrade Notice ==

= 0.1 =
Hello world! 

== Changelog ==

= 0.1 =
* First implementation of CoolClock in sidebar widget

