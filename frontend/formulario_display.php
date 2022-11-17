<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

add_shortcode('form-mascobox', 'form_plugin_mascobox');

function form_plugin_mascobox() {

    global $wpdb;

    if( !empty($_POST)
        && $_POST['nombre'] != '' 
        && $_POST['apellidos'] != ''
        && $_POST['nombre_mascota'] != ''
        && is_email($_POST['correo'])
        && $_POST['tipo_mascota'] != ''
        && $_POST['raza'] != ''
        && $_POST['fecha_nacimiento'] != ''
        && $_POST['codigo_postal'] != ''      
        && $_POST['aceptacion'] == '1'
    ) {

        $masco_tabla = $wpdb->prefix . 'canastillas';

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
            //colocar msj emergente despues del envio
    }

    wp_enqueue_style('css_mascobox', plugins_url('/public/frondend/style.css', __FILE__));

    ob_start();
    ?>
        <form action="<?php get_the_permalink();?>" method="post" class="cuestionario">
        <?php wp_nonce_field('graba_usuario', 'usuario_nonce');?>
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