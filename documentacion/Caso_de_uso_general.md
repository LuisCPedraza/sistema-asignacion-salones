\## Diagrama de Casos de Uso



El Diagrama de Casos de Uso representa los actores del sistema y los casos de uso principales, derivados de las épicas y historias de usuario (HU1-HU19) del documento. Los actores incluyen roles como Administrador, Coordinador, Profesor, y Coordinador de Infraestructura (integrado en Coordinador para simplicidad, ya que comparte funcionalidades en HU5-HU6). Los casos de uso cubren las funcionalidades clave: autenticación, gestión de recursos, asignaciones, reportes, conflictos, auditoría, y configuración.

Para compatibilidad con herramientas como Mermaid Live Editor, he utilizado la sintaxis graph TD para simular el diagrama de casos de uso (ya que la sintaxis usecaseDiagram es beta y no siempre compatible). Actores se representan como rectángulos (\[Actor]), casos de uso como óvalos ((Caso de Uso)), y asociaciones como líneas.



diagrama\_casos\_de\_uso.mmdmermaid



---



\## Diagrama General Casos de Uso

```mermaid

graph TD

&nbsp;   %% Actores (rectángulos)

&nbsp;   A\[Administrador]:::actor

&nbsp;   B\[Coordinador]:::actor

&nbsp;   C\[Profesor]:::actor



&nbsp;   %% Casos de Uso (óvalos)

&nbsp;   UC1((Iniciar Sesión)):::usecase

&nbsp;   UC2((Gestionar Usuarios)):::usecase

&nbsp;   UC3((Gestionar Grupos)):::usecase

&nbsp;   UC4((Gestionar Salones)):::usecase

&nbsp;   UC5((Gestionar Profesores)):::usecase

&nbsp;   UC6((Ejecutar Asignación Automática)):::usecase

&nbsp;   UC7((Realizar Asignación Manual)):::usecase

&nbsp;   UC8((Visualizar Horarios)):::usecase

&nbsp;   UC9((Generar Reportes)):::usecase

&nbsp;   UC10((Gestionar Conflictos)):::usecase

&nbsp;   UC11((Visualizar Historial)):::usecase

&nbsp;   UC12((Configurar Sistema)):::usecase



&nbsp;   %% Asociaciones

&nbsp;   A --> UC1

&nbsp;   A --> UC2

&nbsp;   A --> UC9

&nbsp;   A --> UC11

&nbsp;   A --> UC12

&nbsp;   B --> UC1

&nbsp;   B --> UC3

&nbsp;   B --> UC4

&nbsp;   B --> UC5

&nbsp;   B --> UC6

&nbsp;   B --> UC7

&nbsp;   B --> UC8

&nbsp;   B --> UC9

&nbsp;   B --> UC10

&nbsp;   C --> UC1

&nbsp;   C --> UC5

&nbsp;   C --> UC8



&nbsp;   %% Estilos

&nbsp;   classDef actor fill:#f9f,stroke:#333,stroke-width:2px

&nbsp;   classDef usecase fill:#bbf,stroke:#333,stroke-width:2px,stroke-dasharray: 5 5

```

---





•Descripciones de Casos de Uso



A continuación, detallo los Casos de Uso principales, basados en las épicas y historias de usuario (HU) del backlog del producto. Cada caso de uso incluye: nombre, actor(es), descripción, precondiciones, postcondiciones, flujo principal, flujos alternativos, y referencias a la base de datos (tablas relevantes). Esto cumple con los requisitos de la primera entrega (clase 9) del documento, enfocándose en el análisis y diseño, y alineado con la base de datos (e.g., tablas como usuario, asignacion, restriccion para soportar las operaciones).



Caso de Uso: Iniciar Sesión (HU2)



Actor: Administrador, Coordinador, Profesor.

Descripción: El usuario ingresa sus credenciales para acceder al sistema según su rol.

Precondiciones: El usuario tiene una cuenta registrada en el sistema.

Postcondiciones: El usuario está autenticado y redirigido a su interfaz según rol; se registra en auditoría.

Flujo Principal:



El usuario ingresa email y contraseña.

El sistema valida las credenciales contra usuario (comparando password\_hash).

Si válido, se inicia sesión y se registra en auditoria.

Se muestra interfaz basada en rol.





Flujos Alternativos:



Credenciales inválidas: Mostrar error y registrar intento en auditoria.





Referencias a BD: Tablas usuario (para autenticación), auditoria (para registro).

Excepciones: Cuenta inactiva (activo = 0).





Caso de Uso: Gestionar Usuarios (HU1)



Actor: Administrador.

Descripción: Crear, editar, desactivar o visualizar cuentas de usuarios con roles.

Precondiciones: Administrador autenticado.

Postcondiciones: Cuenta actualizada o creada; cambios registrados en auditoría.

Flujo Principal:



Administrador selecciona operación (crear/editar/desactivar).

Ingresa datos (nombre, email, rol, contraseña).

Sistema valida y guarda en usuario (hashing contraseña).

Registra cambio en auditoria.





Flujos Alternativos:



Email duplicado: Error (por UNIQUE en email).





Referencias a BD: Tabla usuario (para CRUD), auditoria (para registro).

Excepciones: Rol inválido (fuera de ENUM).





Caso de Uso: Gestionar Grupos (HU3, HU4)



Actor: Coordinador.

Descripción: Registrar, editar, desactivar o visualizar grupos de estudiantes.

Precondiciones: Coordinador autenticado.

Postcondiciones: Grupo actualizado; cambios en auditoría.

Flujo Principal:



Coordinador ingresa datos (nombre, nivel, num\_estudiantes, características).

Sistema valida (num\_estudiantes > 0) y guarda en grupo.

Registra cambio en auditoria.





Flujos Alternativos:



Desactivar grupo: Set activo = 0.





Referencias a BD: Tabla grupo (para CRUD), auditoria.

Excepciones: Número de estudiantes inválido.





Caso de Uso: Gestionar Salones (HU5, HU6)



Actor: Coordinador (o Coordinador de Infraestructura).

Descripción: Registrar salones con capacidad, recursos y disponibilidad.

Precondiciones: Coordinador autenticado.

Postcondiciones: Salón y recursos actualizados; cambios en auditoría.

Flujo Principal:



Coordinador ingresa datos (código, capacidad, ubicación, recursos).

Sistema valida (capacidad > 0) y guarda en salon, salon\_recurso, disp\_salon.

Registra cambio en auditoria.





Flujos Alternativos:



Gestionar disponibilidad: Actualizar disp\_salon con bloque\_horario.





Referencias a BD: Tablas salon, recurso, salon\_recurso, disp\_salon, bloque\_horario, auditoria.

Excepciones: Código duplicado (UNIQUE en codigo).





Caso de Uso: Gestionar Profesores (HU7, HU8)



Actor: Coordinador, Profesor (para disponibilidad propia).

Descripción: Registrar profesores con especialidades, hoja de vida y disponibilidad.

Precondiciones: Actor autenticado.

Postcondiciones: Profesor y disponibilidad actualizados; cambios en auditoría.

Flujo Principal:



Coordinador ingresa datos (especialidades, hoja\_vida\_url).

Sistema guarda en profesor, vinculando a usuario.

Profesor/Coordinador actualiza disponibilidad en disp\_profesor con bloque\_horario.

Registra cambio en auditoria.





Flujos Alternativos:



Profesor actualiza disponibilidad propia.





Referencias a BD: Tablas profesor, usuario, disp\_profesor, bloque\_horario, auditoria.

Excepciones: Usuario no asociado a profesor.





Caso de Uso: Ejecutar Asignación Automática (HU9, HU10)



Actor: Coordinador.

Descripción: Ejecutar algoritmo de asignación considerando disponibilidades, capacidades y restricciones.

Precondiciones: Recursos y restricciones configurados.

Postcondiciones: Asignaciones generadas y propuestas; cambios en auditoría.

Flujo Principal:



Coordinador configura parámetros (parametro\_sistema, restriccion).

Sistema ejecuta algoritmo, generando asignaciones en asignacion (origen = 'Automatica', calculando score).

Valida conflictos usando vistas vista\_conflictos\_salon, vista\_conflictos\_profesor.

Registra en auditoria.





Flujos Alternativos:



Conflictos detectados: Sugerir alternativas.





Referencias a BD: Tablas asignacion, restriccion, tipo\_restriccion, disp\_profesor, disp\_salon, bloque\_horario, parametro\_sistema, auditoria; vistas para conflictos.

Excepciones: Restricciones violadas (por trigger en restriccion).





Caso de Uso: Realizar Asignación Manual (HU11, HU12)



Actor: Coordinador.

Descripción: Asignar grupos a salones y profesores manualmente, visualizando conflictos en tiempo real.

Precondiciones: Recursos configurados.

Postcondiciones: Asignación confirmada; cambios en auditoría.

Flujo Principal:



Coordinador arrastra y suelta elementos en interfaz.

Sistema valida en tiempo real usando vistas vista\_conflictos\_salon, vista\_conflictos\_profesor.

Guarda en asignacion (origen = 'Manual').

Registra en auditoria.





Flujos Alternativos:



Conflicto: Mostrar sugerencias.





Referencias a BD: Tablas asignacion, disp\_profesor, disp\_salon, bloque\_horario, auditoria; vistas para conflictos.

Excepciones: Sobrecupo o superposición.





Caso de Uso: Visualizar Horarios (HU13, HU14)



Actor: Coordinador, Profesor.

Descripción: Visualizar horarios completos o personales.

Precondiciones: Asignaciones generadas.

Postcondiciones: Horario mostrado; acceso registrado si necesario.

Flujo Principal:



Actor selecciona tipo de horario (completo o personal).

Sistema consulta asignacion, bloque\_horario, periodo\_academico.

Muestra resultados.





Flujos Alternativos:



Profesor ve solo su horario.





Referencias a BD: Tablas asignacion, bloque\_horario, periodo\_academico, profesor.

Excepciones: No hay asignaciones para el periodo.





Caso de Uso: Generar Reportes (HU15)



Actor: Administrador, Coordinador.

Descripción: Generar reportes de utilización de recursos y estadísticas.

Precondiciones: Datos en el sistema.

Postcondiciones: Reporte generado; acceso registrado.

Flujo Principal:



Actor selecciona tipo de reporte.

Sistema consulta reporte\_ocupacion, vistas para conflictos.

Genera y muestra reporte.





Flujos Alternativos:



Exportar a PDF/CSV.





Referencias a BD: Tabla reporte\_ocupacion; vistas vista\_conflictos\_salon, vista\_conflictos\_profesor.

Excepciones: No hay datos para el periodo.





Caso de Uso: Gestionar Conflictos (HU16, HU17)



Actor: Coordinador.

Descripción: Notificar conflictos y sugerir alternativas, establecer restricciones.

Precondiciones: Asignaciones en proceso.

Postcondiciones: Conflictos resueltos; cambios en auditoría.

Flujo Principal:



Sistema detecta conflictos usando vistas.

Notifica y sugiere alternativas basado en restriccion.

Coordinador ajusta y guarda.

Registra en auditoria.





Flujos Alternativos:



Agregar restricción nueva.





Referencias a BD: Tablas restriccion, asignacion, auditoria; vistas para conflictos; trigger trg\_valida\_restriccion.

Excepciones: Violación de restricción dura.





Caso de Uso: Visualizar Historial (HU18)



Actor: Administrador.

Descripción: Visualizar historial de cambios y usuarios responsables.

Precondiciones: Cambios registrados.

Postcondiciones: Historial mostrado.

Flujo Principal:



Administrador filtra por entidad o usuario.

Sistema consulta auditoria.

Muestra cambios con cambios\_json.





Flujos Alternativos:



Exportar historial.





Referencias a BD: Tabla auditoria.

Excepciones: No hay cambios para el filtro.





Caso de Uso: Configurar Sistema (HU19)



Actor: Administrador.

Descripción: Configurar parámetros generales como periodos, horarios y recursos.

Precondiciones: Administrador autenticado.

Postcondiciones: Parámetros actualizados; cambios en auditoría.

Flujo Principal:



Administrador ingresa clave y valor JSON.

Sistema valida y guarda en parametro\_sistema.

Registra en auditoria.





Flujos Alternativos:



Actualizar periodos laborables.





Referencias a BD: Tabla parametro\_sistema, auditoria.

Excepciones: Clave duplicada (UNIQUE en clave).

