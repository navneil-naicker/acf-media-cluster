=== ACF Media Cluster ===
Contributors: Navneil Naicker
Tags: acf, media, word, pdf, images, documents
Requires at least: 3.6.0
Tested up to: 5.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ACF Media Cluster is an extension for Advance Custom Fields which adds the feature to add multiple media to post/pages.

== Description ==

ACF Media Cluster is an extension for Advance Custom Fields which adds the feature to add multiple media to post/pages. The plugin does come with shortcode or if you know how to write code then you can customize or write your own code and use the data provided by ACF Media Cluster as you wish.

* Visually create your Fields
* Add multiple media and you can also modify title, caption and description to anything
* Assign your fields to multiple edit pages (via custom location rules)
* Easily load data through a simple and friendly API
* Uses the native WordPress custom post type for ease of use and fast processing
* Uses the native WordPress metadata for ease of use and fast processing

= Usage =
Use the helper function below to pull data from the database. The function will be return an array. The helper function takes in 3 parameters.

`acf_media_cluster(string|required $acf_field_name, int $postID, array $options);`

= Example =
Based on the helper function above. Let say we want to pull annual reports from current page.

`acf_media_cluster('annual_reports', get_the_ID());`

The data that will be return will be an array. Now I can loop over the array and use the data anyway I want.

`$ap = acf_media_cluster('annual_reports', get_the_ID());
if( !empty($ap) ){
    foreach($ap as $item){
        var_dump($item); //Use the data as you wish
    }
}`

= Options =
The 3rd parameter of the `acf_media_cluster(string|required $acf_field_name, int $postID, array $options);` helper function is options which takes in an array. You can pass the following.

`acf_media_cluster('annual_reports', array(
    'orderby' => 'post__in',
    'order' => 'ASC'
));`

What are the values you can pass for order and orderby, please refer to <https://developer.wordpress.org/reference/functions/get_posts/>

= Shortcode =
In the text view of your editor, add the following shortcode where you want the media to appear.

`[acf-media-cluster field_name="discussions" container_id="ml-table" container_class="ml-table2" skin="yes"]`

The shortcode takes in the following parameters.

string|required $field_name - Which ACF field name should be used
string $container_id - Wrap the output with your custom CSS ID
string $container_class - Wrap the output with your custom CSS class
string $skin - Do you want default CSS styling to apply. yes|no

= Issues =
Just like any other WordPress plugin, this plugin can also cause issues with other themes and plugins. If you are facing issues making this plugin work on your WordPress site, please do ask for help in the support forum. This way we can help you out and prevent this issue from happening to someone else. If you want to talk to me directly, you can contact me via my website <http://www.navz.me/>

= Compatibility =

This ACF field type is compatible with:
* ACF 5

== Installation ==

1. Copy the `acf-media-cluster` folder into your `wp-content/plugins` folder
2. Activate the ACF Media Cluster plugin via the plugins admin page
3. Create a new field via ACF and select the Media Cluster type
4. Read the description above for usage instructions

== Changelog ==

= 1.0.0 =
* Initial Release.