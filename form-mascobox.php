<?php
/*
Plugin Name: formulario mascobox
Author: ADN
Plugin URI: progresiconsultores.com
Description: Formulario mascobox shortcode [form-mascobox]
Version: 0.0.1
*/

add_shortcode('form-mascobox', 'form_plugin_mascobox');
function form_plugin_mascobox() {
    ob_start()   ;
    ?>
        <form action="<?php get_the_permalink();?>" method="post" class="cuestionario">
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
                <label for="nivel_html">Tipo</label>
                <input type="radio" name="nivel_html" value="1" required> Perro
                <br><input type="radio" name="nivel_html" value="2" required> Gato
            </div>
            <div class="form-input">
                <label for="nivel_css">Raza</label>
                <select name="raza" id="raza" required>
                    <option value="golden">Golden</option>
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
