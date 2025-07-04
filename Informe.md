# Informe detallado del desarrollo

---

## 1. Estructura general

El proyecto está dividido en dos grandes partes:

- **framework/**: Contiene el framework MVC propio, que provee la infraestructura base para la aplicación.
- **src/**: Contiene la aplicación específica del carrito de compras, implementando la lógica de negocio, controladores, modelos y vistas.

---

## 2. Contenido de la carpeta framework

El framework implementa los componentes esenciales para soportar una arquitectura MVC moderna y desacoplada. Sus principales namespaces y responsabilidades son:

- **Assets**: Gestión y envío de archivos estáticos (CSS, JS, imágenes) a la aplicación pública.
- **Concerns**: Traits reutilizables para compartir lógica entre clases.
- **Configuration**: Manejo de la configuración global de la aplicación (por ejemplo, conexión a base de datos, variables de entorno).
- **Core**: Núcleo del framework, incluyendo la clase principal de la aplicación, el ciclo de vida de la request y el despachador de rutas.
- **Data**: Abstracción y acceso a los datos, modelos base, y helpers para consultas a la base de datos.
- **Http**: Manejo de rutas, peticiones y respuestas HTTP, middlewares y helpers para la interacción web.
- **Mail**: Envío de correos electrónicos, integrando PHPMailer para notificaciones y confirmaciones.
- **Security**: Autenticación, autorización y gestión de usuarios de sesión.
- **Support**: Funciones y utilidades auxiliares para el framework.
- **View**: Motor de vistas, compilación y renderizado de plantillas.

**¿Cómo funciona?**  
El framework abstrae las tareas repetitivas y complejas, permitiendo que la aplicación en src se enfoque en la lógica de negocio. Provee clases base para controladores, modelos y vistas, así como helpers para la gestión de rutas, sesiones, validaciones y más.

---

## 3. Contenido de la carpeta src

Esta carpeta contiene la aplicación concreta del carrito de compras, organizada siguiendo el patrón MVC:

- **Controllers**:  
  Controladores como `ProductController`, `CartController`, `UserController`, `SalesController`, etc. Cada uno extiende del controlador base del framework y maneja la lógica de negocio para cada recurso (productos, usuarios, ventas, etc.).

- **Models**:  
  Modelos como `Producto`, `Usuario`, `Compra`, etc. que representan las entidades de la base de datos y extienden del modelo base del framework. Incluyen métodos para consultas, relaciones y lógica de negocio asociada a los datos.

- **Views**:  
  Plantillas Blade o PHP que definen la interfaz de usuario. Están organizadas por módulos (admin, auth, cart, my_purchases, etc.) y utilizan componentes y layouts definidos en el framework.

- **Routes**:  
  Archivo de rutas donde se definen las URLs y se asocian a controladores y métodos específicos.

- **Public**:  
  Archivos accesibles públicamente, como el punto de entrada `index.php` y los assets.

---

## 4. Interacción entre framework y src

### a) Ciclo de vida de una petición

1. **Request entrante:**  
   El usuario accede a una URL. El archivo `public/index.php` inicializa el framework.

2. **Routing:**  
   El framework lee las rutas definidas en src y determina qué controlador y método deben manejar la petición.

3. **Controlador:**  
   El framework instancia el controlador correspondiente de Controllers y ejecuta el método solicitado, pasando la request y otros datos necesarios.

4. **Modelo:**  
   Si el controlador necesita datos, utiliza los modelos de Models, que a su vez heredan del modelo base del framework para acceder a la base de datos.

5. **Vista:**  
   El controlador retorna una vista, que es procesada por el motor de vistas del framework (View). Se renderiza la plantilla correspondiente en Views.

6. **Respuesta:**  
   El framework envía la respuesta HTTP al navegador del usuario.

### b) Ejemplo de interacción

- El usuario accede a `/productos`.
- El framework busca la ruta y llama a `ProductController@index`.
- `ProductController` usa el modelo `Producto` para obtener los productos.
- Los datos se pasan a la vista `products/index.view.php`.
- El motor de vistas del framework renderiza la vista y la muestra al usuario.

### c) Utilidades compartidas

- **Autenticación y sesión:**  
  Los controladores de src usan helpers y clases de Security para verificar usuarios y permisos.
- **Envío de correos:**  
  Cuando se realiza una compra, el controlador usa Mail para enviar confirmaciones.
- **Validaciones y helpers:**  
  Los formularios y controladores aprovechan las utilidades de validación y helpers del framework.

---

## 5. Ventajas de esta arquitectura

- **Separación de responsabilidades:**  
  El framework se encarga de la infraestructura, mientras que src se enfoca en la lógica de negocio.
- **Reutilización y mantenibilidad:**  
  El código común está centralizado en el framework, facilitando actualizaciones y mejoras.
- **Escalabilidad:**  
  Permite agregar nuevas funcionalidades o módulos en src sin modificar el núcleo del framework.

---

## 6. Resumen

- **framework/**: Provee la base, utilidades y abstracciones para el desarrollo rápido y ordenado de aplicaciones web.
- **src/**: Implementa la aplicación concreta del carrito de compras, usando y extendiendo las capacidades del framework.
- **Interacción:**  
  El flujo de trabajo sigue el patrón MVC, donde el framework orquesta el ciclo de vida de la petición y la aplicación define la lógica específica de cada recurso.