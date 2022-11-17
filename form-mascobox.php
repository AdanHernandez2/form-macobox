<?php
/*
Plugin Name: formulario mascobox
Author: ADN
Plugin URI: progresiconsultores.com
Description: Formulario mascobox shortcode [form-mascobox]
Version: 0.0.1
*/

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

register_activation_hook(__FILE__, 'masco_tabla_init');
function masco_tabla_init() {
    global $wpdb;
    $masco_tabla = $wpdb->prefix . 'canastillas';
    $charset_collate = $wpdb->get_charset_collate();

    $query = "CREATE TABLE IF NOT EXISTS $masco_tabla (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(40) NOT NULL,
        apellidos varchar(40) NOT NULL,
        nombre_mascota varchar(40) NOT NULL,
        correo varchar(100) NOT NULL,
        tipo_mascota varchar(2) NOT NULL,
        raza smallint(2) NOT NULL,
        fecha_nacimiento datetime NOT NULL,
        codigo_postal smallint(10) NOT NULL,
        aceptacion smallint(4) NOT NULL,
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate;";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);
}

//shortcode de formulario
include_once plugin_dir_path(__FILE__) . '/frontend/formulario_display.php';

add_action("admin_menu", "masco_form_menu");
/**
 * Agrega el menú del plugin al formulario de WordPress
 *
 * @return void
 */
include_once plugin_dir_path(__FILE__) . '/admin/menu_mascobox.php';