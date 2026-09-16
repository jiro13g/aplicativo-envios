# Aplicativo de Gestión de Envíos

Aplicativo web en **PHP + MySQL** para gestionar envíos.

## Campos de un envío

- `id`
- `destinatario`
- `direccion`
- `descripcion`
- `fecha_creacion`

## Instalación

1. Sube todos los archivos a tu hosting PHP.
2. Verifica que PHP tenga habilitada la extensión `mysqli`.
3. Abre `index.php` desde el navegador.
4. `config.php` crea la base de datos y la tabla automáticamente si no existen.
5. Si la tabla está vacía, se cargan automáticamente 10 envíos de ejemplo.

## Archivos

- `config.php`: conexión y creación automática de base de datos, tabla y datos iniciales.
- `index.php`: interfaz web para crear, consultar, editar y eliminar.
- `api.php`: API REST básica.
- `README.md`: documentación.

## API

### Listar envíos

`GET /api.php`

### Consultar un envío

`GET /api.php?id=1`

### Crear

`POST /api.php`

JSON:

```json
{
  "destinatario": "Pedro López",
  "direccion": "Calle 10 # 20-30, Cali",
  "descripcion": "Paquete de documentos"
}
```

### Actualizar

`PUT /api.php`

JSON:

```json
{
  "id": 1,
  "destinatario": "Pedro López",
  "direccion": "Calle 10 # 20-30, Cali",
  "descripcion": "Documentos actualizados"
}
```

### Eliminar

`DELETE /api.php?id=1`

## Base de datos

El proyecto está configurado con las credenciales proporcionadas para este ejercicio:

- Host: `mysql-jairoapi.alwaysdata.net`
- Usuario: `jairoapi`
- Base de datos: `jairoapi_envios_repositorio`

**Recomendación:** en un proyecto real, evita publicar contraseñas de producción dentro del código o repositorios públicos.
