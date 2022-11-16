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
    echo "Hello, this is our custom field";
}

add_action('admin_init', 'CF_Course_Main');
