<?php

/**
 * Plugin Name: Custom Posts
 * Description: This is a plugin to add and manage custom posts
 * Version: 1.0.0
 * Author: Hasin Hayder
 * Author URI: http://hasin.me
 * Plugin URI: http://google.com
 */

class Custom_Posts
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'activate']);
    }

    function activate(){
      flush_rewrite_rules();
    }

    public function init()
    {
        $this->register_book_cpt();
        $this->register_chapter_cpt();
        $this->register_genre_taxonomy();
        $this->rewrite_chapter_url();
        // add_action('admin_menu', [$this, 'add_chapter_menu_inside_books']);
    }

    public function rewrite_chapter_url()
    {
        add_rewrite_rule(
            '^book/([^/]*)/chapter/([^/]*)/?',
            'index.php?post_type=chapter&name=$matches[2]',
            'top'
        );
    }

    public function add_chapter_menu_inside_books()
    {
        add_submenu_page(
            'edit.php?post_type=book',
            'Chapters',
            'Chapters',
            'manage_options',
            'edit.php?post_type=chapter',
        );

        remove_menu_page('edit.php?post_type=chapter');
    }

    public function register_genre_taxonomy()
    {
        register_taxonomy('genre', 'book', [
            'label' => __('Genre'),
            'hierarchical' => true,
            'rewrite' => ['slug' => 'genre'],
        ]);
    }

    public function register_book_cpt()
    {

        /**
         * Post Type: Books.
         */

        $labels = [
            "name" => esc_html__("Books", "twentytwentyfour"),
            "singular_name" => esc_html__("Book", "twentytwentyfour"),
            "add_new" => esc_html__("Add New Book", "twentytwentyfour"),
            "name_admin_bar" => esc_html__("Storybooks", "twentytwentyfour"),
        ];

        $args = [
            "label" => esc_html__("Books", "twentytwentyfour"),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => "books",
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => ["slug" => "book", "with_front" => true],
            "query_var" => true,
            "supports" => ["title", "editor", "thumbnail", "excerpt"],
            "show_in_graphql" => false,
        ];
        register_post_type("book", $args);

        register_post_type('chapter', [
            'labels' => [
                'name' => __('Chapters'),
                'singular_name' => __('Chapter'),
            ],
            'public' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        ]);
    }

    public function register_chapter_cpt()
    {
        register_post_type('chapter', [
            'labels' => [
                'name' => __('Chapters'),
                'singular_name' => __('Chapter'),
            ],
            'public' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        ]);
    }

}

new Custom_Posts();
