# Sistema de Reservaciones de Mesas con OpenTable

Sistema completo de gestión de reservaciones para restaurantes con integración OpenTable, desarrollado en PHP puro con arquitectura MVC.

## 🚀 Características

### Módulos Principales

- **Gestión de Restaurantes**: Registro completo de restaurantes con información general, horarios, configuración de mesas y sincronización con OpenTable.
- **Gestión de Mesas**: Control de mesas por área (salón, terraza, VIP, barra), capacidad y disponibilidad en tiempo real.
- **Sistema de Reservaciones**: Búsqueda de disponibilidad, creación, modificación y cancelación de reservaciones.
- **Panel de Administración**: Dashboard con estadísticas, calendario visual, gestión de estados y check-in de clientes.
- **Portal de Clientes**: Interfaz pública para buscar disponibilidad y realizar reservaciones.
- **Módulo de Configuración**: Personalización del sistema, colores, correo, PayPal y más.

### Funcionalidades

- ✅ Autenticación con sesiones y `password_hash()`
- ✅ URLs amigables con `.htaccess`
- ✅ Diseño responsivo con Tailwind CSS
- ✅ Gráficas con Chart.js
- ✅ Calendario interactivo con FullCalendar.js
- ✅ API REST para disponibilidad
- ✅ Historial de reservaciones por cliente
- ✅ Gestión de estados (Pendiente, Confirmada, En espera, Sentado, Completada, Cancelada, No show)
- ✅ Generación automática de códigos de confirmación
- ✅ Validación de conflictos de horario

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, PDO_MySQL, JSON, mbstring, session

## 🛠️ Instalación

### 1. Clonar o descargar el repositorio

```bash
git clone https://github.com/danjohn007/API-Open-Table.git
```

### 2. Configurar la base de datos

1. Crear una base de datos MySQL:
```sql
CREATE DATABASE opentable_reservations CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importar el esquema de la base de datos:
```bash
mysql -u root -p opentable_reservations < database/schema.sql
```

### 3. Configurar credenciales

Editar el archivo `config/config.php` y actualizar:

```php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'opentable_reservations');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar permisos

```bash
chmod 755 logs/
chmod 755 public/uploads/
```

### 5. Configurar Apache

Asegúrate de que tu virtual host apunte al directorio raíz del proyecto:

```apache
<VirtualHost *:80>
    ServerName reservaciones.local
    DocumentRoot /ruta/al/proyecto
    
    <Directory /ruta/al/proyecto>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Verificar instalación

Accede a `http://tu-dominio/test.php` para verificar que todo esté configurado correctamente.

## 🔐 Credenciales de Acceso

### Administrador por defecto
- **Usuario**: admin
- **Contraseña**: password

> ⚠️ **Importante**: Cambia estas credenciales inmediatamente después de la instalación.

## 📁 Estructura del Proyecto

```
API-Open-Table/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   └── views/           # Vistas (plantillas PHP)
│       ├── admin/       # Vistas del panel de administración
│       ├── auth/        # Vistas de autenticación
│       ├── client/      # Vistas del portal público
│       └── layouts/     # Plantillas base
├── config/
│   └── config.php       # Configuración principal
├── core/
│   ├── Controller.php   # Controlador base
│   ├── Database.php     # Clase de conexión a BD
│   ├── Model.php        # Modelo base
│   └── Router.php       # Enrutador
├── database/
│   └── schema.sql       # Esquema de la base de datos
├── logs/                # Logs del sistema
├── public/
│   ├── css/             # Estilos CSS
│   ├── js/              # Scripts JavaScript
│   ├── img/             # Imágenes
│   └── uploads/         # Archivos subidos
├── .htaccess            # Configuración Apache
├── index.php            # Punto de entrada
├── test.php             # Test de conexión
└── README.md            # Este archivo
```

## 🗄️ Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema |
| `restaurants` | Restaurantes registrados |
| `restaurant_areas` | Áreas/zonas de cada restaurante |
| `restaurant_schedules` | Horarios por día de la semana |
| `tables` | Mesas de los restaurantes |
| `customers` | Clientes registrados |
| `reservations` | Reservaciones |
| `reservation_history` | Historial de cambios |
| `settings` | Configuraciones del sistema |
| `notifications` | Notificaciones enviadas |
| `opentable_logs` | Logs de integración OpenTable |

### Datos de Ejemplo

El archivo `schema.sql` incluye datos de ejemplo para el estado de Querétaro:
- 4 restaurantes en diferentes ciudades
- Áreas y mesas configuradas
- 5 clientes de ejemplo
- Reservaciones de muestra

## 🎨 Personalización

### Colores del Sistema

Editar en `Configuración > Apariencia`:
- Color primario
- Color secundario
- Color de acento

### Logo y Nombre

Editar en `Configuración > General`:
- Nombre del sitio
- Logo del sistema

## 📧 Configuración de Correo

En `Configuración > Correo`:
1. Servidor SMTP
2. Puerto
3. Usuario y contraseña
4. Correo de envío

## 🔗 Integración con OpenTable

En `Configuración > OpenTable`:
1. API Key
2. API Secret
3. Habilitar sincronización por restaurante

## 💳 Configuración de PayPal

En `Configuración > Pagos`:
1. Client ID
2. Secret
3. Modo (Sandbox/Live)

## 📱 URLs del Sistema

| Ruta | Descripción |
|------|-------------|
| `/` | Página principal |
| `/reservar` | Buscar disponibilidad |
| `/reservar/consultar` | Consultar reservación |
| `/login` | Inicio de sesión |
| `/admin/dashboard` | Panel de control |
| `/admin/reservations` | Lista de reservaciones |
| `/admin/reservations/calendar` | Calendario |
| `/admin/restaurants` | Gestión de restaurantes |
| `/admin/tables` | Gestión de mesas |
| `/admin/customers` | Gestión de clientes |
| `/admin/settings` | Configuración |

## 🔧 Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (sin framework)
- **Base de datos**: MySQL 5.7
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Tailwind CSS
- **Gráficas**: Chart.js
- **Calendario**: FullCalendar.js
- **Interactividad**: Alpine.js

## 📄 Licencia

Este proyecto es de código abierto.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor, abre un issue primero para discutir los cambios que te gustaría hacer.

---

Desarrollado con ❤️ para la gestión de reservaciones de restaurantes.
