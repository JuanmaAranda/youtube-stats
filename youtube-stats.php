<?php
/**
 * Plugin Name: YouTube Stats
 * Description: Muestra el número de suscriptores, visualizaciones y vídeos publicados de un canal de YouTube mediante shortcodes.
 * Version: 1.4
 * Author: Juanma Aranda
 * Author URI: https://wpnovatos.com
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

// Agregar la opción de configuración al menú de administración
add_action('admin_menu', 'ys_add_admin_menu');
add_action('admin_init', 'ys_settings_init');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ys_add_settings_link');

function ys_add_admin_menu() {
    // URL del icono personalizado
    $icon_url = 'https://juanmaaranda.com/wp-content/uploads/2024/08/wpnovatos-byn-20.png';
    
    // Verificar si el menú "WPnovatos" ya existe
    global $menu;
    $wpnovatos_exists = false;
    
    foreach ($menu as $menu_item) {
        if ($menu_item[2] === 'wpnovatos') {
            $wpnovatos_exists = true;
            break;
        }
    }
    
    // Si no existe, crear el menú "WPnovatos"
    if (!$wpnovatos_exists) {
        add_menu_page(
            '', // Título de la página (vacío para que no sea clicable)
            'WPnovatos', // Título del menú
            'manage_options', // Capacidad
            'wpnovatos', // Slug del menú
            '__return_null', // Función de contenido (null para que no sea clicable)
            $icon_url, // Icono del menú
            3 // Posición
        );
    }
    
    // Añadir el submenú "YouTube Stats" bajo "WPnovatos"
    add_submenu_page(
        'wpnovatos',               // Slug del menú principal
        'YouTube Stats',       // Título de la página
        'YouTube Stats',       // Título del submenú
        'manage_options',          // Capacidad requerida
        'youtube_stats', // Slug del submenú
        'ys_options_page' // Función que muestra la página de ajustes
    );
 
    
  // Remover el enlace duplicado "WPnovatos" del submenú
    remove_submenu_page('wpnovatos', 'wpnovatos');
}

function ys_settings_init() {
    register_setting('pluginPage', 'ys_settings', 'ys_settings_validate');

    add_settings_section(
        'ys_pluginPage_section',
        __('Configuraciones del plugin', 'wordpress'),
        'ys_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'ys_api_key',
        __('API Key de YouTube', 'wordpress'),
        'ys_api_key_render',
        'pluginPage',
        'ys_pluginPage_section'
    );

    add_settings_field(
        'ys_channel_id',
        __('ID del Canal de YouTube', 'wordpress'),
        'ys_channel_id_render',
        'pluginPage',
        'ys_pluginPage_section'
    );
}

function ys_api_key_render() {
    $options = get_option('ys_settings');
    ?>
    <input type='text' name='ys_settings[ys_api_key]' value='<?php echo isset($options['ys_api_key']) ? esc_attr($options['ys_api_key']) : ''; ?>'>
    <?php
}

function ys_channel_id_render() {
    $options = get_option('ys_settings');
    ?>
    <input type='text' name='ys_settings[ys_channel_id]' value='<?php echo isset($options['ys_channel_id']) ? esc_attr($options['ys_channel_id']) : ''; ?>'>
    <?php
}

function ys_settings_section_callback() {
    echo __('Introduce tu API Key de YouTube y el ID del Canal para obtener el número de suscriptores, visualizaciones y vídeos publicados.', 'wordpress');
}

function ys_options_page() {
    ?>
    <div class="wrap">
        <h1>YouTube Stats</h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
            <?php
            // Mostrar mensajes de validación
            $options = get_option('ys_settings');
            if (isset($options['validation_message'])) {
                if ($options['validation_status'] == 'valid') {
                    echo '<div style="border: 1px solid green; padding: 10px; background-color: #e7f9e7;">' . esc_html($options['validation_message']) . '</div>';
                } else {
                    echo '<div style="border: 1px solid red; padding: 10px; background-color: #f9e7e7;">' . esc_html($options['validation_message']) . '</div>';
                }
            }
            ?>
            <h3>Instrucciones</h3>
            <p><strong>Cómo obtener la API Key de YouTube:</strong></p>
            <ol>
                <li>Ve a la <a href="https://console.developers.google.com/" target="_blank">Google Developers Console</a>.</li>
                <li>Crea un nuevo proyecto o selecciona uno existente.</li>
                <li>En el menú de la izquierda, selecciona "Credenciales".</li>
                <li>Haz clic en "Crear credenciales" y selecciona "Clave de API".</li>
                <li>Copia la API Key generada y pégala en el campo correspondiente de este plugin.</li>
            </ol>
            <p><strong>Cómo obtener el ID del Canal de YouTube:</strong></p>
            <ol>
                <li>Ve a tu canal de YouTube.</li>
                <li>En la URL del navegador, copia la parte después de "channel/" (por ejemplo, UC_x5XG1OV2P6uZZ5FSM9Ttw).</li>
                <li>Pega el ID del Canal en el campo correspondiente de este plugin.</li>
            </ol>
            <p><strong>Cómo usar los shortcodes:</strong></p>
            <p>Para mostrar el número de suscriptores en cualquier página o entrada, añade el siguiente shortcode:</p>
            <p><code>[youtube_suscribers]</code></p>
            <p>Para mostrar el número de visualizaciones en cualquier página o entrada, añade el siguiente shortcode:</p>
            <p><code>[youtube_views]</code></p>
            <p>Para mostrar el número de vídeos publicados en cualquier página o entrada, añade el siguiente shortcode:</p>
            <p><code>[youtube_videos]</code></p>
        </form>
    </div>
    <?php
}

function ys_settings_validate($input) {
    $api_key = $input['ys_api_key'];
    $channel_id = $input['ys_channel_id'];

    $response = wp_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}");

    if (is_wp_error($response)) {
        $input['validation_status'] = 'invalid';
        $input['validation_message'] = 'Error al validar la API Key o el ID del Canal. Por favor revise los datos.';
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (isset($data['items'][0]['statistics']['subscriberCount'])) {
            $input['validation_status'] = 'valid';
            $input['validation_message'] = 'API Key e ID del Canal válidos.';
        } else {
            $input['validation_status'] = 'invalid';
            $input['validation_message'] = 'No se pudo validar la API Key o el ID del Canal. Por favor revise los datos.';
        }
    }

    return $input;
}

// Shortcode para mostrar el número de suscriptores
add_shortcode('youtube_suscribers', 'ys_show_suscribers');

function ys_show_suscribers() {
    $options = get_option('ys_settings');
    $api_key = $options['ys_api_key'];
    $channel_id = $options['ys_channel_id'];

    if (!$api_key || !$channel_id) {
        return "Por favor configure su API Key y ID del Canal en la página de ajustes del plugin.";
    }

    $response = wp_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}");

    if (is_wp_error($response)) {
        return "Error al obtener los datos del canal.";
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['items'][0]['statistics']['subscriberCount'])) {
        $subscriber_count = $data['items'][0]['statistics']['subscriberCount'];
        return number_format($subscriber_count, 0, '', '.');
    } else {
        return "No se pudo obtener el número de suscriptores.";
    }
}

// Shortcode para mostrar el número de visualizaciones
add_shortcode('youtube_views', 'ys_show_views');

function ys_show_views() {
    $options = get_option('ys_settings');
    $api_key = $options['ys_api_key'];
    $channel_id = $options['ys_channel_id'];

    if (!$api_key || !$channel_id) {
        return "Por favor configure su API Key y ID del Canal en la página de ajustes del plugin.";
    }

    $response = wp_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}");

    if (is_wp_error($response)) {
        return "Error al obtener los datos del canal.";
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['items'][0]['statistics']['viewCount'])) {
        $view_count = $data['items'][0]['statistics']['viewCount'];
        return number_format($view_count, 0, '', '.');
    } else {
        return "No se pudo obtener el número de visualizaciones.";
    }
}

// Shortcode para mostrar el número de vídeos publicados
add_shortcode('youtube_videos', 'ys_show_videos');

function ys_show_videos() {
    $options = get_option('ys_settings');
    $api_key = $options['ys_api_key'];
    $channel_id = $options['ys_channel_id'];

    if (!$api_key || !$channel_id) {
        return "Por favor configure su API Key y ID del Canal en la página de ajustes del plugin.";
    }

    $response = wp_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}");

    if (is_wp_error($response)) {
        return "Error al obtener los datos del canal.";
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['items'][0]['statistics']['videoCount'])) {
        $video_count = $data['items'][0]['statistics']['videoCount'];
        return number_format($video_count, 0, '', '.');
    } else {
        return "No se pudo obtener el número de vídeos publicados.";
    }
}

// Añadir enlace de "Ajustes" en la lista de plugins
function ys_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=youtube_stats">' . __('Ajustes') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
?>
