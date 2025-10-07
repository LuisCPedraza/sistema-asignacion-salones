---

## Caso de Uso: Iniciar Sesión (HU2)
secuencia_iniciar_sesion.mmdmermaid

```mermaid
sequenceDiagram
    actor Usuario
    participant Sistema
    participant BD as Base de Datos
    Usuario->>Sistema: Ingresar email y contraseña
    Sistema->>BD: Validar credenciales (consultar usuario)
    BD-->>Sistema: Resultado (hash coincide, rol)
    alt Credenciales válidas
        Sistema->>BD: Registrar inicio de sesión (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Usuario: Sesión iniciada, mostrar interfaz por rol
    else Credenciales inválidas
        Sistema->>BD: Registrar intento fallido (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Usuario: Error de autenticación
    end
```
---

---

## Caso de Uso: Gestionar Usuarios (HU1)
secuencia_gestionar_usuarios.mmdmermaid

```mermaid
sequenceDiagram
    actor Administrador
    participant Sistema
    participant BD as Base de Datos
    Administrador->>Sistema: Seleccionar operación (crear/editar/desactivar)
    Administrador->>Sistema: Ingresar datos (nombre, email, rol, contraseña)
    Sistema->>BD: Validar datos (email único)
    BD-->>Sistema: Resultado validación
    alt Datos válidos
        Sistema->>BD: Guardar/Actualizar usuario (hash contraseña)
        BD-->>Sistema: Confirmación
        Sistema->>BD: Registrar cambio (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Administrador: Operación exitosa
    else Datos inválidos
        Sistema->>Administrador: Error (email duplicado)
    end
```
---

---

## Caso de Uso: Gestionar Grupos (HU3, HU4)
secuencia_gestionar_grupos.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    participant Sistema
    participant BD as Base de Datos
    Coordinador->>Sistema: Seleccionar operación (registrar/editar/desactivar)
    Coordinador->>Sistema: Ingresar datos (nombre, nivel, num_estudiantes, características)
    Sistema->>BD: Validar datos (num_estudiantes > 0)
    BD-->>Sistema: Resultado validación
    alt Datos válidos
        Sistema->>BD: Guardar/Actualizar grupo
        BD-->>Sistema: Confirmación
        Sistema->>BD: Registrar cambio (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Coordinador: Operación exitosa
    else Datos inválidos
        Sistema->>Coordinador: Error (num_estudiantes inválido)
    end
```
---

---

## Caso de Uso: Gestionar Salones (HU5, HU6)
secuencia_gestionar_salones.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    participant Sistema
    participant BD as Base de Datos
    Coordinador->>Sistema: Seleccionar operación (registrar/editar)
    Coordinador->>Sistema: Ingresar datos (código, capacidad, ubicación, recursos, disponibilidad)
    Sistema->>BD: Validar datos (capacidad > 0, código único)
    BD-->>Sistema: Resultado validación
    alt Datos válidos
        Sistema->>BD: Guardar/Actualizar salón, salón_recurso, disp_salon
        BD-->>Sistema: Confirmación
        Sistema->>BD: Registrar cambio (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Coordinador: Operación exitosa
    else Datos inválidos
        Sistema->>Coordinador: Error (código duplicado o capacidad inválida)
    end
```
---

---

## Caso de Uso: Gestionar Profesores (HU7, HU8)
secuencia_gestionar_profesores.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    actor Profesor
    participant Sistema
    participant BD as Base de Datos
    Coordinador->>Sistema: Ingresar datos profesor (especialidades, hoja_vida_url)
    Sistema->>BD: Validar y guardar profesor (vinculado a usuario)
    BD-->>Sistema: Confirmación
    Profesor->>Sistema: Actualizar disponibilidad
    Sistema->>BD: Guardar disp_profesor con bloque_horario
    BD-->>Sistema: Confirmación
    Sistema->>BD: Registrar cambio (auditoria)
    BD-->>Sistema: Confirmación
    Sistema->>Coordinador: Operación exitosa
    Sistema->>Profesor: Disponibilidad actualizada
```
---

---

## Caso de Uso: Ejecutar Asignación Automática (HU9, HU10)
secuencia_asignacion_automatica.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    participant Sistema
    participant BD as Base de Datos
    Coordinador->>Sistema: Configurar parámetros y restricciones
    Sistema->>BD: Guardar en parametro_sistema, restriccion
    BD-->>Sistema: Confirmación
    Coordinador->>Sistema: Ejecutar asignación automática
    Sistema->>BD: Consultar disponibilidades (disp_profesor, disp_salon, recurso_disponibilidad)
    BD-->>Sistema: Datos disponibilidades
    Sistema->>BD: Validar restricciones (trigger trg_valida_restriccion)
    BD-->>Sistema: Resultado validación
    Sistema->>BD: Generar asignaciones (asignacion con origen 'Automatica', score)
    BD-->>Sistema: Confirmación
    Sistema->>BD: Registrar cambio (auditoria)
    BD-->>Sistema: Confirmación
    Sistema->>Coordinador: Asignaciones generadas
```
---

---

## Caso de Uso: Realizar Asignación Manual (HU11, HU12)
secuencia_asignacion_manual.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    participant Sistema
    participant BD as Base de Datos
    Coordinador->>Sistema: Arrastrar y soltar (grupo a salón/profesor)
    Sistema->>BD: Consultar vistas (vista_conflictos_salon, vista_conflictos_profesor)
    BD-->>Sistema: Conflictos detectados
    alt Sin conflictos
        Sistema->>BD: Guardar asignacion (origen 'Manual')
        BD-->>Sistema: Confirmación
        Sistema->>BD: Registrar cambio (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Coordinador: Asignación confirmada
    else Con conflictos
        Sistema->>Coordinador: Mostrar conflictos y sugerencias
    end
```
---

---

## Caso de Uso: Visualizar Horarios (HU13, HU14)
secuencia_visualizar_horarios.mmdmermaid

```mermaid
sequenceDiagram
    actor Actor as Coordinador/Profesor
    participant Sistema
    participant BD as Base de Datos
    Actor->>Sistema: Seleccionar tipo de horario (completo/personal)
    Sistema->>BD: Consultar asignacion, bloque_horario, periodo_academico
    BD-->>Sistema: Datos horarios
    Sistema->>Actor: Mostrar horario
```
---

---

## Caso de Uso: Generar Reportes (HU15)
secuencia_generar_reportes.mmdmermaid

```mermaid
sequenceDiagram
    actor Actor as Administrador/Coordinador
    participant Sistema
    participant BD as Base de Datos
    Actor->>Sistema: Seleccionar tipo de reporte
    Sistema->>BD: Consultar reporte_ocupacion, vistas conflictos
    BD-->>Sistema: Datos reportes
    Sistema->>Actor: Generar y mostrar reporte
```
---

---

## Caso de Uso: Gestionar Conflictos (HU16, HU17)
secuencia_gestionar_conflictos.mmdmermaid

```mermaid
sequenceDiagram
    actor Coordinador
    participant Sistema
    participant BD as Base de Datos
    Sistema->>BD: Detectar conflictos (vistas conflictos)
    BD-->>Sistema: Conflictos encontrados
    Sistema->>Coordinador: Notificar conflictos y sugerencias
    Coordinador->>Sistema: Ajustar asignación o restricción
    Sistema->>BD: Validar (trigger trg_valida_restriccion)
    BD-->>Sistema: Resultado validación
    Sistema->>BD: Guardar cambios (restriccion, asignacion)
    BD-->>Sistema: Confirmación
    Sistema->>BD: Registrar (auditoria)
    BD-->>Sistema: Confirmación
    Sistema->>Coordinador: Conflictos resueltos
```
---

---

## Caso de Uso: Visualizar Historial (HU18)
secuencia_visualizar_historial.mmdmermaid

```mermaid
sequenceDiagram
    actor Administrador
    participant Sistema
    participant BD as Base de Datos
    Administrador->>Sistema: Filtrar por entidad/usuario
    Sistema->>BD: Consultar auditoria
    BD-->>Sistema: Datos cambios (cambios_json)
    Sistema->>Administrador: Mostrar historial
```
---

---

## Caso de Uso: Configurar Sistema (HU19)
secuencia_configurar_sistema.mmdmermaid

```mermaid
sequenceDiagram
    actor Administrador
    participant Sistema
    participant BD as Base de Datos
    Administrador->>Sistema: Ingresar clave y valor JSON
    Sistema->>BD: Validar (clave única)
    BD-->>Sistema: Resultado validación
    alt Válido
        Sistema->>BD: Guardar parametro_sistema
        BD-->>Sistema: Confirmación
        Sistema->>BD: Registrar cambio (auditoria)
        BD-->>Sistema: Confirmación
        Sistema->>Administrador: Configuración exitosa
    else Inválido
        Sistema->>Administrador: Error (clave duplicada)
    end
```
---