# GLPI Validation Lock Plugin

Este plugin bloquea que un ticket pueda pasar a estado **Resuelto** o **Cerrado**, y que se añadan **Soluciones**, si el ticket tiene alguna validación pendiente (`status = WAITING`).

## Requisitos
- GLPI 11.0.4 o superior.

## Instalación
1. Descarga o clona el repositorio en `glpi/plugins/validationlock`.
2. Ve a la consola de administración de GLPI -> Configuración -> Plugins.
3. Instala y activa el plugin.

## Funcionalidad
- Intercepta la actualización de tickets (`pre_item_update`).
- Intercepta la creación de soluciones (`pre_item_add` de `ITILSolution`).
- Muestra un mensaje de error claro al usuario si existen validaciones pendientes.
