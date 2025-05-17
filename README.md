# 🧾 EasyStock - Sistema de Gestión de Inventario

**EasyStock** es una aplicación web diseñada para facilitar el control de inventarios en pequeñas y medianas empresas. Su enfoque modular, accesible y escalable permite a los negocios locales gestionar sus productos, entradas y salidas de forma eficiente, emitir tickets de venta, generar reportes y mantener un historial completo de movimientos.

---

## 📌 Tabla de Contenidos

- [🎯 Objetivo](#objetivo)
- [🚀 Funcionalidades](#funcionalidades)
- [🛠️ Tecnologías Utilizadas](#tecnologías-utilizadas)
- [📈 Justificación del Proyecto](#justificación-del-proyecto)
- [🔒 Seguridad y Control de Acceso](#seguridad-y-control-de-acceso)
- [📱 Interfaz y Usabilidad](#interfaz-y-usabilidad)
- [🧩 Estructura Modular y Escalabilidad](#estructura-modular-y-escalabilidad)
- [💻 Instalación](#instalación)
- [📝 Licencia](#licencia)

---

## 🎯 Objetivo

El objetivo principal de **EasyStock** es ofrecer una solución tecnológica intuitiva, adaptable y de bajo costo que permita a los emprendedores y PyMEs mantener el control sobre su inventario, tomar decisiones basadas en datos y digitalizar sus procesos de gestión sin necesidad de conocimientos técnicos avanzados.

---

## 🚀 Funcionalidades

### Básicas
- Registro y administración de productos (nombre, categoría, stock, precios).
- Control de entradas (adquisiciones) y salidas (ventas).
- Historial completo de movimientos con fecha, cantidad y detalles.

### Avanzadas (Actualizaciones futuras)
- Control de acceso mediante roles diferenciados (administrador, empleado).
- Generación de tickets de venta en PDF.
- Reportes como productos más vendidos, inventario por categoría y análisis de rentabilidad.
- Validaciones en tiempo real y formularios modales dinámicos.
- Comunicación asíncrona (AJAX) para actualizar datos sin recargar la página.

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Rol |
|------------|-----|
| **PHP**    | Backend, lógica de negocio, generación de PDFs, gestión de sesiones y autenticación. |
| **MySQL**  | Base de datos relacional para productos, usuarios, ventas, movimientos, etc. |
| **JavaScript** | Interacción en tiempo real, validación de formularios, manipulación del DOM, AJAX. |
| **Bootstrap** | Diseño responsivo y moderno, interfaz amigable adaptable a cualquier dispositivo. |

---

## 📈 Justificación del Proyecto

EasyStock nace como respuesta a la falta de herramientas tecnológicas accesibles para pequeños negocios. Muchos aún dependen de métodos manuales (cuadernos, hojas de cálculo) que limitan su eficiencia. Este sistema busca empoderar a esos negocios con una solución ligera, funcional y escalable, adaptable a su realidad operativa.

---

## 🔒 Seguridad y Control de Acceso

- Autenticación segura con sesiones protegidas.
- Roles de usuario con permisos diferenciados.
- Validación de formularios y sanitización de datos para evitar vulnerabilidades.
- Registro de acciones sensibles para auditoría y trazabilidad.

---

## 📱 Interfaz y Usabilidad

- Interfaz basada en Bootstrap para una experiencia intuitiva.
- Diseño responsivo para uso desde computadoras, tablets o smartphones.
- Microinteracciones y notificaciones para una experiencia de usuario fluida.
- Formularios y tablas dinámicas con filtros, búsqueda y paginación.

---

## 🧩 Estructura Modular y Escalabilidad

Gracias al uso de tecnologías modernas y arquitectura MVC, EasyStock es fácilmente escalable:

- Agrega nuevos módulos como punto de venta, facturación electrónica o notificaciones.
- Soporte para múltiples usuarios y puntos de venta.
- Integración futura con API REST o aplicaciones móviles.

---

## 💻 Instalación

> ⚠️ Requisitos:
> - Servidor web con PHP (XAMPP, LAMP, etc.)
> - MySQL
> - Navegador web moderno

1. Clona este repositorio:

```bash
git clone https://github.com/tuusuario/easystock.git
