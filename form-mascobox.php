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
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required />
            </div>
        </form>    
    <?php
    return ob_get_clean();
}
