# Plantilla vue 3

## Instalación

Instalar dependencias
````
npm install
````

Configurar environments
````
cp .env.example .env.local
````

Iniciar servidor
````
npm run dev
````

## Convenciones y datos generales de plantilla

### Componentes generales
Los componentes generales se deben crear dentro de ***src/components***, con un nombre genérico en formato **PascalCase**, éstos se cargan automáticamente, por lo cual ya no se deben importar en ningún lugar adicional, por ejemplo ***CardComponent*** se llamará como ***Card*** a la hora de implementarlo.

### Rutas   
El menú de rutas y componentes viene desde el archivo ***src/router/system-routes.js*** en donde también se registra el componente que renderiza, en el mismo documento se señalan las instrucciones de llenado.

Los ***name*** y ***paths*** se deben establecer en ***kebap-case***, ejemplo "***/proyectos-asignados***".

### Service
Todos los services se almacenan en ***src/services*** en formato ***[kebap-case].services.js***.

### Store
El store es un archivo que almecena el estado y las acciones que manipulan el estado, luego esto se importa en cada componente para poder usarse, las actions se destructura y los states se pasan a ref a través del método ***storeToRefs*** de ***Pinia***.

### Utils

Los utils son funciones generales que se crearán según su naturaleza y se deben importar solo donde se van a usar.

### Views
Se debe crear views a partir de carpetas de módulo, por ejemplo el módulo sería **proyectos**, luego dentro de esa carpeta creamos otra llamada **components** para los componentes de ese módulo, y adicional cualquier otro submodulo necesario, mientras que los stores siempre se almacenarán en la carpeta ***src/store***, igualmente las rutas en ***src/router***, en dónde también se puede crear otros archivos y luegos importarlos a ***src/router/system-routes***.