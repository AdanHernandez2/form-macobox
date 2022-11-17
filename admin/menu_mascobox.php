<?php

if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

    function masco_form_menu() 
    {
        add_menu_page("Formulario mascobox", "mascobox", "manage_options",
            "masco_form_menu", "masco_formulario_admin", "dashicons-feedback", 75);
    }
    
    function masco_formulario_admin()
    {
        global $wpdb;
        $masco_tabla = $wpdb->prefix . 'canastillas';
        $usuariosMascobox = $wpdb->get_results("SELECT * FROM $masco_tabla");
        echo '<div class="wrap"><h1>Lista de usuarios</h1>';
        $ajax_url = admin_url('admin-ajax.php?action=csv_pull');
        echo "<div><a href='$ajax_url'>Exportar datos</a></div>";
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th >Nombre</th><th >Apellidos</th>';
        echo '<th>Nombre Mascota</th><th >correo</th><th>tipo</th><th>Raza</th><th>Fecha de nacimiento</th><th>veterinario</th>';
        echo '</tr></thead>';
        echo '<tbody id="the-list">';
        foreach ($usuariosMascobox as $usuarioMasco) {
            $nombre = esc_textarea($usuarioMasco->nombre);
            $apellidos = esc_textarea($usuarioMasco->apellidos);
            $nombre_mascota = esc_textarea($usuarioMasco->nombre_mascota);
            $correo = esc_textarea( $usuarioMasco-> correo);
            $tipo_mascota = (int) $usuarioMasco->tipo_mascota;
            $raza = (int) $usuarioMasco->raza;
            $fecha_nacimiento = date($usuarioMasco->fecha_nacimiento);
            echo "<tr><td>$nombre</td>";
            echo "<td>$apellidos</td><td>$nombre_mascota</td><td>$correo</td><td>$tipo_mascota</td>";
            echo "<td>$raza</td><td>$fecha_nacimiento</td>";
            $url_borrar = admin_url('admin-post.php') . '?action=borra_mascobox&id='
                . $usuarioMasco->id;
            echo "<td><a href='$url_borrar'>Borrar</a></td>";
            echo "</tr>";
        }
        echo '</tbody></table></div>';
    }
    
    // Vincula la función de borrado con un hook de admin_post
    add_action('admin_post_borra_mascobox', 'masco_Borra_usuario');
    /**
     * Borra un registro de usuario usando admin-post.php
     * 
     * @return void
     */
    function masco_Borra_usuario()
    {
        global $wpdb;
        $url_origen = admin_url('admin.php') . '?page=masco_form_menu';
        // && current_user_can('manage_options')
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $masco_tabla = $wpdb->prefix . 'canastillas';
            $wpdb->delete($masco_tabla, array('id' => $id));
            $status = 'success';
        } else {
            $status = 'error';
        }
        wp_safe_redirect(
            esc_url_raw(
                add_query_arg( 'masco_usuario_status', $status, $url_origen )
            )
        );
    }
    // action de exportacion de tabla
    add_action('wp_ajax_csv_pull', 'masco_usuarios_csv_pull');
    /**
     *exportar tabla a csv
     * 
     * @return void
     */
    function masco_usuarios_csv_pull() {
    
        global $wpdb;
    
        $table = 'canastillas';// nombre de tabla
        $file = 'masco_canastillas_csv'; // nombre de csv
        $results = $wpdb->get_results("SELECT * FROM $wpdb->prefix$table",ARRAY_A );
        
        $casillas = array('clave', 'Nombre', 'Apellidos', 'correo', 'tipo', 'Raza', 'Fecha de nacimiento'); 
        // Display column names as first row 
        $csv_output = implode(", ", array_values($casillas)) . "\n"; 
    
        if(count($results) > 0){
           foreach($results as $result){
           $result = array_values($result);
           $result = implode(", ", $result);
           $csv_output .= $result."\n";
         } 
       }
    
       $filename = $file."_".date("Y-m-d_H-i",time());
       header("Content-type: application/vnd.ms-excel");
       header("Content-disposition: csv" . date("Y-m-d") . ".csv");
       header( "Content-disposition: filename=".$filename.".csv");
       print $csv_output;
       exit;
    
     }