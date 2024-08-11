# YouTube Stats

**YouTube Stats** es un plugin para WordPress que te permite mostrar información sobre un canal de YouTube en tu sitio web mediante shortcodes. Puedes mostrar el número de suscriptores, el número de visualizaciones y otros datos del canal directamente en tus páginas y entradas.

## Características

- Muestra el número de suscriptores del canal.
- Muestra el número de visualizaciones del canal.
- Muestra el número de vídeos publicados en el canal.
- Configuración sencilla a través del panel de administración de WordPress.
- Shortcodes fáciles de usar para integrar en páginas y entradas.

## Instalación

1. **Descargar el plugin**: Descarga el archivo ZIP del plugin o clona este repositorio en tu computadora.

2. **Subir el plugin a WordPress**:
   - Si descargaste el archivo ZIP, ve a **Plugins > Añadir nuevo > Subir plugin** en tu panel de administración de WordPress y selecciona el archivo ZIP.
   - Si clonaste el repositorio, copia la carpeta `youtube-stats` a `wp-content/plugins` en tu instalación de WordPress.

3. **Activar el plugin**:
   - Ve a **Plugins** en el panel de administración de WordPress.
   - Busca "YouTube Stats" y haz clic en **Activar**.

## Configuración

1. **Obtener una API Key de YouTube**:
   - Ve a la [Google Developers Console](https://console.developers.google.com/).
   - Crea un nuevo proyecto o selecciona uno existente.
   - En el menú de la izquierda, selecciona **Credenciales**.
   - Haz clic en **Crear credenciales** y selecciona **Clave de API**.
   - Copia la API Key generada.

2. **Obtener el ID del Canal de YouTube**:
   - Ve a tu canal de YouTube.
   - En la URL del navegador, copia la parte después de "channel/" (por ejemplo, UC_x5XG1OV2P6uZZ5FSM9Ttw).

3. **Configurar el plugin en WordPress**:
   - Ve a **WPnovatos > YouTube Stats** en el menú de administración de WordPress.
   - Ingresa la API Key y el ID del Canal en los campos correspondientes y guarda los cambios.

   Si los datos son válidos, verás un mensaje de confirmación en verde. Si hay algún error, se mostrará un mensaje en rojo indicándote qué datos deben ser revisados.

## Uso

Una vez que hayas configurado el plugin, puedes utilizar los siguientes shortcodes en tus páginas o entradas para mostrar la información del canal:

- **Número de suscriptores**: `[youtube_suscribers]`
- **Número de visualizaciones**: `[youtube_views]`
- **Número de vídeos**: `[youtube_videos]`

## Ejemplo

Para mostrar el número de suscriptores de tu canal en una entrada o página, simplemente agrega el shortcode `[youtube_suscribers]` en el editor de WordPress.

## Contribuciones

¡Las contribuciones son bienvenidas! Si encuentras un error o deseas agregar una nueva característica, no dudes en abrir un **issue** o enviar un **pull request**.

## Licencia

Este plugin está licenciado bajo la [Licencia GPL 2.0](https://opensource.org/licenses/GPL-2.0).

## Contacto

Para cualquier pregunta o soporte, puedes contactarme a través de [tu correo electrónico o red social].

---

¡Gracias por usar el plugin YouTube Stats!
