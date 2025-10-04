## Paso 1: Crear una Rama de Trabajo (Branching)
Es una buena práctica trabajar siempre en ramas separadas para aislar el desarrollo y evitar afectar la rama principal. Se utiliza la rama main (o master) como base para la nueva
rama.
1. Crear y cambiar a la nueva rama (ej: feature/login):
```bash
git checkout -b feature/nombre_de_la_rama
```
- (-b crea la rama si no existe).
	- Opcional: Si el flujo de trabajo fuera Git Flow, podría iniciar la rama con:
	```bash
	git flow feature start login.
	```
---
## Paso 2: Realizar Cambios y Confirmaciones
Implementa la funcionalidad y confirma sus cambios.
- Crea y modifica archivos (ej: añade nombre_del_archivo.md).
	```bash
	notepad.exe nombre_del_archivo.md
	```
	- Se abrirá el Blog de notas de Windows, ahí guardara el texto en formato Markdown de la tarea documentacion
- Ver historial de logs:
	```bash
	git log
	```
- Ver las ramas:
	```bash
	git Branch
	```
- Ver el estado:
	```bash
	git status
	```
- Añade los cambios:
	```bash
	git add nombre_del_archivo.md
	```
- Confirma los cambios:
	```bash
	git commit -m "Comenta lo que realizo"
	```
- Si modifico o borro el archivo accidentalmente, lo puedo restaurar:
	```bash
	git restore nombre_del_archivo.md
	```
## Paso 3: Enviar Cambios a GitHub
Sube la nueva rama de funcionalidad a su repositorio remoto (su fork).
- Empujar la rama:
	```bash
	git push origin feature/nombre_de_la_rama
	```
---
## Paso 4: Crear una Pull Request (Solicitud de Extracción)
Desde su repositorio en GitHub, debe solicitar al repositorio principal de LuisCPedraza que extraiga sus cambios.
- En la página de su GitHub del fork, haz clic en "Pull request".
- GitHub mostrará la diferencia entre la rama feature/nombre_de_la_rama de su repositorio y la rama main del repositorio del administrador.
- Crea la Pull Request, proporcionando un mensaje descriptivo sobre lo que se ha hecho.
---
## Paso 5: Revisar y Fusionar la Pull Request (Merge)
El repositorio del administrador (LuisCPedraza) recibe la solicitud.
- El repositorio del administrador (LuisCPedraza) revisa el código de la PR. Si hay comentarios, puede solicitar cambios (lo cual reinicia el ciclo para su repositorio).
- Si el código es correcto, el repositorio del administrador (LuisCPedraza) hace clic en "Merge pull request" para fusionar los cambios de la rama feature/login de su repositorio en la rama main del repositorio
principal.
- La fusión (merge) crea un nuevo merge commit en la rama de destino.
---
### Paso 6: Actualizar el Repositorio Local del repositorio del administrador (Mantenedor)
Aunque el repositorio del administrador (LuisCPedraza) el merge en GitHub, es posible que su repositorio local necesite actualizarse.
	- Asegúrate de estar en la rama principal:
	```bash 
	git checkout main
	```
	- Descarga y fusiona los cambios remotos:
	```bash 
	git pull
	``` 
		- (git pull es equivalente a git fetch (descargar historial) seguido de git merge (fusionar cambios)).
---
### Paso 7: Sincronizar el Fork de su repositorio (Colaborador)
Debe asegurarse de que su fork esté al día con el repositorio original (Repositorio administrador).
- Añadir el repositorio original (LuisCPedraza) como remoto upstream:
	```bash
	git remote add upstream git@github.com:LuisCPedraza/sistema-asignacion-salones.git
	```
- Descargar el historial de upstream: 
	```bash
	git fetch upstream
	```
- Fusionar los cambios de upstream en la rama main local:
	```bash
	git merge upstream/main
	```
- Subir los cambios a su propio fork (origin):
	```bash
	git push origin main
	```
---
## Paso 8: Eliminar Ramas Innecesarias
Una vez que la funcionalidad ha sido fusionada en main, la rama de trabajo ya no es necesaria y se puede eliminar.
	- Eliminar localmente (asumiendo que ya está en main):
	```bash
	git branch -d feature/login
	```
		- Si la rama no se ha fusionado completamente, Git te pedirá que uses -D (mayúscula) para forzar la eliminación.
	- Eliminar remotamente (en el fork de tu repositorio):
	```bash
	git push origin --delete feature/login
	```