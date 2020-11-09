## php-admin-dashboard

### Instalación

#### Paso 1

```
git clone https://github.com/edgarjaviertec/php-admin-dashboard.git
```

#### Paso 2

```
cd php-admin-dashboard 
```

#### Paso 3

```
composer install
```

#### Paso 4

Crea una base de datos con phpMyAdmin o con tu programa favorito e importa db.sql

#### Paso 5
Localiza el php-admin-dashboard/app/.env y agrega los datos de tu base de datos

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_dashboard
DB_USERNAME=root
DB_PASSWORD=root
```

#### Paso 6

Configura y ejecuta tu servidor web apuntando a la raíz

### Datos de acceso

| Primer encabezado | Segundo encabezado |
| ------------- | ------------- |
| `admin@yopmail.com` | admin|
| `user1@yopmail.com`  | user1  |


### Capturas

Aquí hay algunas capturas de como se ve el panel de administración 

![Captura 1](https://raw.githubusercontent.com/edgarjaviertec/php-admin-dashboard/master/capturas-de-pantalla/Inicia%20sesi%C3%B3n.png)

![Captura 2](https://raw.githubusercontent.com/edgarjaviertec/php-admin-dashboard/master/capturas-de-pantalla/Dashboard.png)

![Captura 3](https://raw.githubusercontent.com/edgarjaviertec/php-admin-dashboard/master/capturas-de-pantalla/Usuarios.png)

![Captura 4](https://raw.githubusercontent.com/edgarjaviertec/php-admin-dashboard/master/capturas-de-pantalla/Roles.png)

![Captura 5](https://github.com/edgarjaviertec/php-admin-dashboard/blob/master/capturas-de-pantalla/Permisos.png)

![Captura 6](https://raw.githubusercontent.com/edgarjaviertec/php-admin-dashboard/master/capturas-de-pantalla/Asignar%20permisos.png)
