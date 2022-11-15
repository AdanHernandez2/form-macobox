<?php
/*
Plugin Name: formulario mascobox
Author: ADN
Plugin URI: progresiconsultores.com
Description: Formulario mascobox shortcode [form-mascobox]
Version: 0.0.1
*/

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

add_shortcode('form-mascobox', 'form_plugin_mascobox');
function form_plugin_mascobox() {

    global $wpdb;
    $masco_tabla = $wpdb->prefix . 'canastillas';

    if( !empty($_POST)
    AND$_POST['nombre'] != '' 
    AND $_POST['apellidos'] != ''
    AND $_POST['nombre_mascota'] != ''
    AND is_email($_POST['correo'])
    AND $_POST['tipo_mascota'] != ''
    AND $_POST['raza'] != ''
    AND $_POST['fecha_nacimiento'] != ''
    AND $_POST['codigo_postal'] != ''      
    AND $_POST['aceptacion'] == '1') {

        $nombre = sanitize_text_field($_POST['nombre']);
        $apellidos = sanitize_text_field($_POST['apellidos']);
        $nombre_mascota = sanitize_text_field($_POST['nombre_mascota']);
        $correo = sanitize_email($_POST['correo']);
        $tipo_mascota = (int)$_POST['tipo_mascota'];
        $raza = (int)$_POST['raza'];
        $fecha_nacimiento = date($_POST['fecha_nacimiento']);
        $codigo_postal = (int)$_POST['codigo_postal'];
        $aceptacion = (int)$_POST['aceptacion'];
        $created_at = date('Y-m-d H:i:s');
        $wpdb->insert(
            $masco_tabla , 
            array(
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'nombre_mascota' => $nombre_mascota,
                'correo' => $correo,
                'tipo_mascota' => $tipo_mascota,
                'raza' => $raza,
                'fecha_nacimiento' => $fecha_nacimiento,
                'codigo_postal' => $codigo_postal,
                'aceptacion' => $aceptacion,
                'created_at' => $created_at,
            )
            );
    }

    wp_enqueue_style('css_mascobox', plugins_url('/public/frondend/style.css', __FILE__));

    ob_start();
    ?>
        <form action="<?php get_the_permalink();?>" method="post" class="cuestionario">
        <?php wp_nonce_field('graba_aspirante', 'aspirante_nonce');?>
            <div class="form-input">
                <label for='correo'>Direccion de e-mail</label>
                <input type="email" name="correo" id="correo" required>
            </div>
            <div class="form-input">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required />
            </div>
            <div class="form-input">
                <label for="apellidos">Apellidos</label>
                <input type="text" name="apellidos" required />
            </div>
            <div class="form-input">
                <label for="nombre_mascota">Nombre de la mascota</label>
                <input type="text" name="nombre_mascota" required />
            </div>
            <div class="form-input">
                <label for="tipo_mascota">Tipo</label>
                <select name="tipo_mascota" id="tipo_mascota">
                    <option value="">Tipo de mascota</option>
                    <option value="01">Perro</option>
                    <option value="02">Gato</option>
                </select>
            </div>
            <div class="form-input">
                <label for="raza">Raza</label>
                <select name="raza" id="raza" required>
                    <option value="golden">Golden</option>
                    <option value="02">Caniche</option>
                </select>
            </div>
            <div class="form-input">
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" required>
            </div>
            <div class="form-input">
                <label for="codigo_postal">Centro veterinario</label>
                <input type="zip" name="codigo_postal" required />
            </div>
            <div class="form-input">
                <label for="aceptacion">La información facilitada se tratará con respeto y admiración.</label>
                <input type="checkbox" id="aceptacion" name="aceptacion" value="1" required> Entiendo y acepto las condiciones
            </div>
            <div class="form-input">
                <input type="submit" value="Enviar">
            </div>
    </form>    
    <?php
    return ob_get_clean();
}

add_action("admin_menu", "masco_form_menu");
/**
 * Agrega el menú del plugin al formulario de WordPress
 *
 * @return void
 */
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
		$url_borrar = admin_url('admin-post.php') . '?action=borra_usuario&id='
			. $usuarioMasco->id;
		echo "<td><a href='$url_borrar'>Borrar</a></td>";
		echo "</tr>";
    }
    echo '</tbody></table></div>';
}
