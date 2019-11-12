## Sistema Inteligente de Recomendación de Productos   
*Documento de Análisis* 
**Versión 1.0**

<div style="page-break-after: always;"></div>

## Historial de Revisiones
| Fecha      | Versión | Descripción                        | Elaborado por            |
| ---        | ---     | ---                                | ---                      |
| 07/11/2019 | 1.0     | Creación del documento de análisis | Rodas Pacco Angelo       |

<div style="page-break-after: always;"></div>

## Tabla de Contenido

<div style="page-break-after: always;"></div>

## Documento de Análisis  
### 1.  Paquetes de Análisis del Negocio
![](i101.png)
 
Cada paquete corresponde a un proceso de negocio, Los 3 paquetes básicos que se muestran en el diagrama son los siguientes:  
- Gestión de Productos.
- Gestión de Ventas.
- Administración.

Finalmente se tiene un paquete de seguridad, el cual contendrá los CUS que proveerán los medios identificativos para el sistema. En este caso, Autentificar usuario 
### 2.   Proceso del Negocio: Gestión de Productos
#### 2.1 Paquetes de Análisis
•   Paquete del proceso del negocio: 

![](i201.png)
 


•   Paquetes para los casos de uso análisis: 
![](i202.png)
 

####Clases de Análisis
##### 2.1.1   Clases Entidad
 
![](i203.png)

##### 2.1.2   Clases Interfaz
![](i204.png)
 

##### 2.1.3   Clases Control
![](i205.png)
 
#### 2.2 Diagrama de Clases Análisis
##### 2.2.1   Caso de Uso: Registrar Marca

| ID:           | CUS-01                                                                                                                                                    |
| ---           | ---                                                                                                                                                       |
| Caso de Uso:  | Registrar Marca                                                                                                                                           |
| Actor:        | Cajero o Administrador (Usuario)                                                                                                                          |
| Descripción:  | El Cajero o Administrador podrán registrar las marcas para posterior registro de productos.                                                               |
| Objetivo:     | El objetivo del caso de uso “Registrar Marca” es registrar la marca en sistema y mantener la información el base de datos del sistema para uso posterior. |
| Precondición: | El usuario debe haber iniciado sesión correctamente.                                                                                                      |

###### Flujo Principal
1.  El CUS inicia cuando el usuario ha iniciado sesión correctamente en la aplicación.
2.  El sistema muestra la información del usuario y una sección con una lista de marcas registradas y un botón para registrar marca.
3.  El usuario presiona el botón de registrar marca donde le aparecerá un input en un popup.
4.  El usuario escribe la nueva marca y da clic en el botón de registrar marca.
5.  Se muestra el resumen de la marca a registrar, un botón para confirmar su creación y un botón para volver atrás.
6.  El usuario presiona el botón de confirmación.
7.  El sistema registra la marca.
8.  Fin del CUS  

**Post-condición:** El sistema debe tener registrada la marca para un uso posterior.

###### Flujo Alterno:
9.  En el paso 4, si el usuario escribe una marca ya existente le saldrá un popup advirtiéndole sobre una marca repetida.
10.  En el paso 4, debe marcarse con rojo en caso no haya escrito nada o tenga espacios en blanco el input de registro.
11.  En el paso 5, si el usuario presiona el botón “volver atrás”, la aplicación retorna a la pantalla registrar marca y permite editar la información anteriormente escrita.

###### Requerimientos Especiales:
1.  El tamaño de letra lo suficientemente grande para que no tenga impedimentos visuales de realizar sus actividades.
2.  El diseño de la interfaz debe ser legible para el usuario.
3.  No debe permitir registrar si está vacío el input de marca o si hay solo espacios en blanco

###### 2.2.1.1 Realización de Caso de Uso Análisis
![](i206.png)

###### 2.2.1.2 Diagrama de secuencia y Flujo de Eventos
•   Diagrama de Secuencia
![](i207.png)

•   Flujo de eventos  
    El cajero entrara en la interfaz de Registrar Marca (1), el sistema procederá a buscar la lista de marcas registrados(2) mediante el controlador ConexionBD que solicitara a la base de datos la lista de marcas (3), recibirá la lista (4) y las mostrar en la interfaz de registrar marcas (5), el cajero ingresaría los datos de la nueva marca a registrar (6), el sistema pasara a validar que no sea repetida (7),  se generara la creación de marca (8) mediante el controlador ConexionBD (9) para añadirlo a la base de datos (10), que retornara la confirmación de la creación (11), para mostrarla en la interfaz de usuario (12).
##### 2.2.2   Caso de Uso: Actualizar Marca

| ID: | CUS-02 |
| --- | ---    |
|Caso de Uso:|    Editar Marca|
|Actor:  |Cajero o Administrador (Usuario)|
|Descripción:  |  El Cajero o Administrador podrán actualizar las marcas disponibles.|
|Objetivo: |  El objetivo del caso de uso “Actualizar Marca” es actualizar la marca en sistema y mantener la información el base de datos del sistema para uso posterior.|
|Precondición:   |El usuario debe haber iniciado sesión correctamente. Se debe haber registrado al menos una marca.|

###### Flujo Principal: 
1.  El CUS inicia cuando el usuario ha iniciado sesión correctamente en la aplicación.
2.  El sistema muestra la información del usuario y una sección con una lista de marcas registradas.
3.  El usuario al hacer doble click sobre una marca se habilitará la edición.
4.  El usuario escribe la nueva marca y da clic en el botón de actualizar marca.
5.  Se muestra el resumen de la marca actualizada, un botón de confirmación y un botón para volver atrás.
6.  El usuario presiona el botón de confirmación.
7.  El sistema actualiza la marca.
8.  Fin del CUS
Post-condición: El sistema debe tener actualizada la lista de marcas para un uso posterior.

###### Flujo Alterno:
9.  En el paso 4, si el usuario escribe una marca ya existente le saldrá un popup advirtiéndole sobre una marca repetida.
10. En el paso 4, debe marcarse con rojo en caso no haya escrito nada o tenga espacios en blanco el input de actualizar.
11. En el paso 5, si el usuario presiona el botón “volver atrás”, la aplicación retorna a la pantalla registrar marca y permite editar la información anteriormente escrita.

###### Requerimientos Especiales:
12. El tamaño de letra lo suficientemente grande para que no tenga impedimentos visuales de realizar sus actividades.
13. El diseño de la interfaz debe ser legible para el usuario.
14. No debe permitir actualizar si está vacío el input de marca o si hay solo espacios en blanco

###### 2.2.2.1 Realización de Caso de Uso Análisis
![](i208.png)

###### 2.2.2.2 Diagramas de Secuencia y Flujo de eventos  

•   Diagrama de Secuencia
![](i209.png)

•   Flujo de eventos

El cajero entrara en la interfaz de Editar Marca (1), el sistema procederá a buscar la lista de marcas registradas(2) mediante el controlador ConexionBD que solicitara a la base de datos la lista de marcas (3), recibirá la lista (4) y las mostrar en la interfaz de editar marca(5), el cajero ingresaría los nuevos datos de la marca a actualizar (6), el sistema pasara a validar que no sea repetida (7),  se generara la actualización de marca (8) mediante el controlador ConexionBD (9) para añadirlo a la base de datos (10), que retornara la confirmación de la actualización (11), para mostrarla en la interfaz de usuario (12).

##### 2.2.3   Caso de Uso: Registrar Categoría
| ID:           | CUS-03                                                                                                                                                            |
| ---           | ---                                                                                                                                                               |
| Caso de Uso:  | Registrar Categoría                                                                                                                                               |
| Actor:        | Cajero o Administrador (Usuario)                                                                                                                                  |
| Descripción:  | El Cajero o Administrador podrán registrar las categorías para posterior registro de productos.                                                                   |
| Objetivo:     | El objetivo del caso de uso “Registrar Categoría” es registrar la categoría en sistema y mantener la información el base de datos del sistema para uso posterior. |
| Precondición: | El usuario debe haber iniciado sesión correctamente.                                                                                                              |

###### Flujo Principal: 
1.  El CUS inicia cuando el usuario ha iniciado sesión correctamente en la aplicación.
2.  El sistema muestra la información del usuario y una sección con una lista de categorías registradas y un botón para registrar categoría.
3.  El usuario presiona el botón de registrar categoría donde le aparecerá un input en un popup.
4.  El usuario escribe la nueva categoría y da clic en el botón de registrar categoría.
5.  Se muestra el resumen de la categoría a registrar, un botón para confirmar su creación y un botón para volver atrás.
6.  El usuario presiona el botón de confirmación.
7.  El sistema registra la categoría.
8.  Fin del CUS

**Post-condición:** El sistema debe tener registrada la categoría para un uso posterior.

###### Flujo Alterno:
9.  En el paso 4, si el usuario escribe una categoría ya existente le saldrá un popup advirtiéndole sobre una categoría repetida.
10. En el paso 4, debe marcarse con rojo en caso no haya escrito nada o tenga espacios en blanco el input de registro.
11. En el paso 5, si el usuario presiona el botón “volver atrás”, la aplicación retorna a la pantalla registrar categoria y permite editar la información anteriormente escrita.

###### Requerimientos Especiales:
12. El tamaño de letra lo suficientemente grande para que no tenga impedimentos visuales de realizar sus actividades.
13. El diseño de la interfaz debe ser legible para el usuario.
14. No debe permitir registrar si está vacío el input de marca o si hay solo espacios en blanco

###### 2.2.3.1 Realización de Caso de Uso Análisis
![](i210.png)

 



###### 2.2.3.2 Diagrama de secuencia y flujo de eventos
•   Diagrama de Secuencia
![](i211.png)

•   Flujo de eventos

El cajero entrara en la interfaz de Registrar Categoría (1), el sistema procederá a buscar la lista de categorías registrados(2) mediante el controlador ConexionBD que solicitara a la base de datos la lista de categorías (3), recibirá la lista (4) y las mostrar en la interfaz de registrar categoría (5), el cajero ingresaría los datos de la nueva categoría a registrar (6), el sistema pasara a validar que no sea repetida (7),  se generara la creación de categoría (8) mediante el controlador ConexionBD (9) para añadirlo a la base de datos (10), que retornara la confirmación de la creación (11), para mostrarla en la interfaz de usuario (12).

##### 2.2.4   Caso de Uso: Registrar Productos
| ID: | CUS-04 |
| --- | ---    |
| Caso de Uso:  | Registrar Productos                                                                                                                                                                    |
| Actor:        | Cajero o Administrador (Usuario)                                                                                                                                                       |
| Descripción:  | El Cajero o Administrador podrán registrar los productos del sistema.                                                                                                                  |
| Objetivo:     | El objetivo del caso de uso “Registrar Productos” es registrar los productos en el sistema y mantener la información el base de datos del sistema para su uso en el paquete de ventas. |
| Precondición: | El usuario debe haber iniciado sesión correctamente.Deben existir al menos una categoría y una marca registrada                                                                        |

###### Flujo Principal: 
1.  El CUS inicia cuando el usuario ha iniciado sesión correctamente en la aplicación.
2.  El sistema muestra la información del usuario y una sección con una lista de productos registrados y un botón para registrar producto.
3.  El usuario presiona el botón de registrar producto donde le aparecerá un formulario en un popup.
4.  El usuario llena el formulario y da clic en el botón de registrar producto.
5.  Se muestra el resumen del producto a registrar, un botón para confirmar su creación y un botón para volver atrás.
6.  El usuario presiona el botón de confirmación.
7.  El sistema registra el producto.
8.  Fin del CUS

###### Post-condición: El sistema debe tener registrado el producto para un uso posterior.
Flujo Alterno:
9.  En el paso 4, debe marcarse con rojo en caso no haya escrito nada o tenga espacios en blanco algún input del formulario.
10. En el paso 5, si el usuario presiona el botón “volver atrás”, la aplicación retorna a la pantalla registrar producto y permite editar la información anteriormente escrita. 

###### Requerimientos Especiales:
11. El tamaño de letra lo suficientemente grande para que no tenga impedimentos visuales de realizar sus actividades.
12. El diseño de la interfaz debe ser legible para el usuario.
13. No debe permitir registrar si está vacío algún campo vacío del formulario o si hay solo espacios en blanco 

###### 2.2.4.1 Realización de Caso de Uso Análisis
![](i212.png)
 
###### 2.2.4.2 Diagramas de Interacción y flujo de eventos
 
![](i213.png)



El cajero entrara en la interfaz de Registrar Producto (1), el sistema procederá a buscar la lista de productos registrados(2) mediante el controlador ConexionBD que solicitara a la base de datos la lista de productos (3), recibirá la lista (4) y las mostrar en la interfaz de registrar producto (5), el cajero ingresaría al formulario de registro de producto para llenar los datos (6), el sistema procederá a buscar la lista de categorías para seleccionar una de ellas (7),  mediante el controlador categorías registradas (8) el cual se tendrá que conectar a BD mediante el controlador ConexionBD (9) para recibir la lista de categorías (10), que retornara la lista de categorías al controlador categorías (11), para mostrar en el formulario de registro de producto como un combo box para seleccionar una (12), el sistema procederá a buscar la lista de marcas para seleccionar una de ellas (13),  mediante el controlador marcas registradas (14) el cual se tendrá que conectar a BD mediante el controlador ConexionBD (15) para recibir la lista de marcas (16), que retornara la lista de marcas al controlador marcas (17), para mostrar en el formulario de registro de producto como un combo box para seleccionar una (18), se procederá a generar el nuevo productos (19), sus datos se cargaran a BD mediante el controlador ConexionBD (20), se procederá a crear el nuevo producto (21), que retornara la confirmación de la creación (22), para luego mostrarlo en un popup (23).


### 3   Proceso del Negocio: Gestión de Ventas
#### 3.1 Paquetes de Análisis
- Paquete del proceso de negocio  
![](i301.png)
 
- Paquetes para los casos de uso de análisis  
![](i302.png)

 
#### 3.2 Clases de Análisis
##### 3.2.1   Clases Entidad
![](i303.png)
 
##### 3.2.2   Clases Interfaz
![](i304.png)
 
##### 3.2.3   Clases Control
![](i305.png)
 
#### 3.3 Diagrama de Clases Análisis

##### 3.3.1   Caso de Uso: Recomendar Productos
| ID: | CUS-05 |
| --- | ---    |

| Caso de Uso:  | Recomendar Productos                                                                                                                                                                                                        |
| Actor:        | Cajero o Administrador (Usuario)                                                                                                                                                                                            |
| Descripción:  | El Cajero o Administrador podrán recomendar productos al cliente.                                                                                                                                                           |
| Objetivo:     | El objetivo del caso de uso “Recomendar Productos” es recomendar productos en base a un análisis estadístico por IA de los productos que mayormente compran los clientes acompañado del producto el cual se está vendiendo. |
| Precondición: | El usuario debe haber iniciado sesión correctamente.Debe haberse seleccionado mínimo un producto en la venta                                                                                                                |


###### Flujo Principal: 
1.  El CUS inicia cuando el usuario ha iniciado sesión correctamente en la aplicación.
2.  El sistema muestra la información del usuario y una sección con una lista de productos seleccionados para vender y un botón para mostrar productos recomendados.
3.  El usuario presiona el botón de productos recomendados con lo que aparecerá una lista de productos para recomendar al cliente.
4.  Fin del CUS
Post-condición: El sistema debe mostrar una lista de productos recomendados.

###### Flujo Alterno:
Requerimientos Especiales:
5.  El tamaño de letra lo suficientemente grande para que no tenga impedimentos visuales de realizar sus actividades.
6.  El diseño de la interfaz debe ser legible para el usuario. 

###### 3.3.1.1 Realización de Caso de Uso Análisis
![](i306.png)
 
###### 3.3.1.2 Diagrama de Secuencia y flujo de eventos
![](i307.png)
 
El cajero entrara en la interfaz de mostrar recomendaciones una vez tenga productos seleccionados para vender y haya pulsado el botón de mostrar recomendaciones (1), el sistema procederá a buscar la lista de productos recomendados (2), mediante el controlador ConexionBD que solicitara a base de datos la lista de productos recomendados (3), recibirá la lista (4), y pintara una lista en la interfaz mostrando los productos que se recomiendan vender al cajero u administrador.
