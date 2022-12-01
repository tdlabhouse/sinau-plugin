<?php
/*
* Plugin Name: Sinau Plugin
* Description: Ini adalah Plugin Learning Management System 
* Version: 1.0
* Author: Teguh Dumadi
* Author URI: https://sinau.com
*/


// Register custom post
/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function couse_cpt()
{
    $labels = array(
        'name'                  => _x('Courses', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Course', 'Post type singular name', 'textdomain'),

    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'Course'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'menu_icon'          => 'dashicons-database-view',
    );

    register_post_type('courses', $args);
}

add_action('init', 'couse_cpt');


// Add custom field
function CF_Course_Main()
{
    // 
    add_meta_box(
        "cf_courses_id",            //ID
        "Course Custom Fields",     //Title of the custom field
        "CF_Courses",               //Function down below
        "courses",                  //Custom Post Type
        "normal",                   //Priority
        "low",                      //Position Up/Down
    );
}

function CF_Courses()
{
    echo "Halo, ini adalah custom field";

    wp_head();
    global $wpdb;
    $ID = get_the_id();
    $ourdb = $wpdb->prefix . "sn_course_detail";
    $bahasa = $wpdb->get_var("SELECT `subtitle` FROM `$ourdb` WHERE `ID` = '" . $ID . "'");
    $harga = $wpdb->get_var("SELECT `price` FROM `$ourdb` WHERE `ID` = '" . $ID . "'");
    $trailer = $wpdb->get_var("SELECT `video` FROM `$ourdb` WHERE `ID` = '" . $ID . "'");
    $curiculum = $wpdb->get_var("SELECT `content` FROM `$ourdb` WHERE `ID` = '" . $ID . "'");

?>

    <div class="container">
        <br>
        <div class="box1">
            <h3>Bahasa</h3>
            <input type="text" name="bahasa" value="<?php echo  $bahasa; ?>">
        </div>
        <br>
        <div class="box1">
            <h3>Harga Kelas</h3>
            <input type="text" name="harga" value="<?php echo  $harga; ?>">
        </div>
        <br>
        <div class="box1">
            <h3>Video Trailer</h3>
            <input type="text" name="trailer" value="<?php echo  $trailer; ?>">
        </div>
        <br>
        <div class="box1">
            <h3>Curriculum</h3>
            <input type="text" name="curiculum" value="<?php echo  $curiculum; ?>">
        </div>
        <br>
    </div>

<?php
}

add_action('admin_init', 'CF_Course_Main');


// Include css file
function add_style()
{
    // 
    wp_register_style('style', plugin_dir_url(__FILE__) . 'scripts/style.css');
    wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'scripts/style.css');
}

add_action('wp_enqueue_scripts', 'add_style');


/* Load Course Template */
function template_courses($template)
{
    global $post;
    if ('courses' === $post->post_type && locate_template(array('template_courses')) !== $template) {
        // 
        return plugin_dir_path(__FILE__) . 'templates/template_courses.php';
    }

    return $template;
}

add_filter('single_template', 'template_courses');


/* Create a Database Table - Courses Details */

function database_table()
{
    // 
    global $wpdb;
    $database_table_name = $wpdb->prefix . "sn_course_detail";
    $charset = $wpdb->get_charset_collate;
    $course_det = "CREATE TABLE $database_table_name (
        ID int(9) NOT NULL,
        tittle text(100) NOT NULL,
        subtitle text(500) NOT NULL,
        video varchar(100) NOT NULL,
        price int(9) NOT NULL,
        thumnbail text NOT NULL,
        content text NOT NULL,
        PRIMARY KEY (ID)

    )$charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($course_det);
}

register_activation_hook(__FILE__, 'database_table');


/* Save Courses Details to database */

function save_custom_fields()
{
    // 
    global $wpdb;
    $id         = get_the_id();
    $title      = get_the_title();
    $bahasa     = $_POST['bahasa'];
    $harga      =  $_POST['harga'];
    $trailer    = $_POST['trailer'];
    $curiculum  = $_POST['curiculum'];

    $wpdb->insert(
        $wpdb->prefix . "sn_course_detail", //Table Name 
        [
            'ID'        => $id,
            'tittle'    => $title,
            'subtitle'  => $bahasa,
            'video'     => $trailer,
            'price'     => $harga,
            'content'   => $curiculum,
        ]
    );

    $wpdb->update(
        $wpdb->prefix . "sn_course_detail", //Table Name 
        [
            'tittle'    => $title,
            'subtitle'  => $bahasa,
            'video'     => $trailer,
            'price'     => $harga,
            'content'   => $curiculum,
        ],
        ['ID'        => $id,],
    );
}

add_action('save_post', 'save_custom_fields');
