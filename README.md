# Trabajo Práctico Final Programación Web Dinámica
Se implementará una aplicación de carrito de compras.

## Sistema desarrollado
Se implementó una aplicación web con un framework MVC desarrollado específicamente para esta tarea.

### Framework MVC
El framework desarrollado se puede ver en la carpeta framework y consta de los siguientes namespaces:
- Assets: maneja la generación y envío de los assets proporcionados a la aplicación pública.
- Concerns: proporciona algunos traits usados en otras clases.
- Configuration: provee clases para la configuración inicial de la aplicación.
- Core: provee la clase principal de la aplicación.
- Data: maneja el acceso a los datos.
- Http: maneja lo referido a las rutas que permiten redirigir a los controladores.
- Mail: provee una funcionalidad básica de envío de correos usando PHPMailer.
- Security: maneja la autenticación y el usuario de sesión.
- View: provee el manejo y compilación de vistas.

## Requisitos
- Composer
- Mysql 8
- PHP 8.4.7

## Inicio del sistema
Ejecutar el comando
```powershell
composer serve
```

## Imágenes
### Base de datos
![Database](images/Database.png)

### Home del Cliente
![HomeCliente](images/HomeCliente.png)

### Mis Compras
![MyPurchases](images/MyPurchases.png)

### Detalle de Mis Compras
![MyPurchasesDetail](images/MyPurchasesDetail.png)

### Carrito de Compras
![Cart](images/Cart.png)

### Home del Administrador
![HomeAdmin](images/HomeAdmin.png)

### Menues del Administrador
![AdminMenus](images/AdminMenus.png)

### Home Administración
![AdminHome](images/AdminHome.png)

### Home de Ventas
![VentasHome](images/VentasHome.png)

### Edición de Ventas
![VentaEdit](images/VentaEdit.png)

### Home de Menú
![MenuHome](images/MenuHome.png)

### Crear Menú
![MenuCreate](images/MenuCreate.png)

### Editar Menú
![MenuEdit](images/MenuEdit.png)

### Home de Productos
![ProductoHome](images/ProductoHome.png)

### Crear Producto
![ProductoCreate](images/ProductoCreate.png)

### Editar Producto
![ProductoEdit](images/ProductoEdit.png)

### Home de Roles
![RolesHome](images/RolesHome.png)

### Crear Rol
![RoleCreate](images/RoleCreate.png)

### Editar Rol
![RoleEdit](images/RoleEdit.png)

### Home de Usuarios
![UsersHome](images/UsersHome.png)

### Crear Usuario
![UserCreate](images/UserCreate.png)

### Editar Usuario
![UserEdit](images/UserEdit.png)
