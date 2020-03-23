### Sección 1: Introducción al curso!! 1 / 1|7 min
### [1. Bienvenidos a este curso! Algunas recomendaciones antes de empezar 7 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17450464#questions/9295602)
- ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/a1a623aba60aed0a7ecb57a28f31fc2a/image.png)
- Necesitas saber:
  - Haber trabajado con algún framework (laravel o symf)
  - Unit testing
    - Unitario y funcional
  - Conocimientos básicos de docker
  - xdebug
- Recomienda revisar todos los videos de la *Sección 5: Autenticación y registro 0 / 3|1 h 41 min* y despues realizarlo

### Sección2: Creación del proyecto
### [Repo original - bitbucket](https://bitbucket.org/juanwilde/sf5-expenses-api/src/master/)
- `composer create-project symfony/skeleton expenses_api`
  - Installing symfony/skeleton (v5.0.99)
- Abrir phpstorm para configurar la version de php 7.4
- Instala plugin en phpstorm que no hay en vscode (Symfony support by Daniel Espandiller)
- Configura `composer.json` la version de php a 7.4 y la descripcion del proyecto
- En phpstorm, preferences le indica la version de php que va a usar (phplint de sintaxis)
- Crear repo en bitbucket
- Configuración de gitignore
  - Hay que incluir en este fichero, archivos de certificados, contraseñas o que tengan datos sensibles que no se deben publicar
  - Agrego .vscode, no sabia que hay un pseudo lenguaje de marcado `##>tag y <##tag`
- Configuracion de .env
  - solo se deja esto:
  ```js
  ###> symfony/framework-bundle ###
  APP_ENV=dev
  APP_SECRET=8d38c09d701d0a11b3185428eb30d08b
  ###< symfony/framework-bundle ###
  ```
  - se crea una copia de lo anterior en .env.local
### Sección 3: Configuración de Docker 3 / 3|45 min
### [3. Configuración de Docker en Mac 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451272#questions/9295602)
- [Rama](https://bitbucket.org/juanwilde/sf5-expenses-api/src/b953a0090139df2d90c8eea1e7f8e11912315136/?at=section2%2Fvideo1-docker-config)
- Se incluyen ficheros:
  - docker-compose.linux.yml
  - docker-compose.macos.yml
  - docker-sync.yml
  - Makefile
  - docker/nginx/
    - default.conf
    - Dockerfile
  - docker/php/
    - Dockerfile
    - php.ini
    - xdebug.ini
    - xdebug-linux.ini
### [3. Configuración de Docker en Mac 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451272#questions/9295602)
- 
### [4. Configuración de Docker en Linux 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451450#questions/9295602)
-
### [5. Configuración de Docker en Windows (Bonus)](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451456#questions/9295602)
- 
### [6. Instalación de librerías adicionales 5 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451528#questions/9295602)
- 
### Sección 4: Instalación de librerías adicionales 1 / 1|5 min
### [6. Instalación de librerías adicionales 5 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17450464#questions)
- 

### Sección 5: Autenticación y registro 0 / 3|1 h 41 min
### [7. Instalar librería para usar JSON Web Tokens 7 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451540#questions/9295602)
- 
### [8. Configurar sistema de autenticación 56 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451544#questions/9295602)
- 
### [9. Custom endpoint para registrar usuarios 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451550#questions/9295602)
- 

### Sección 6: Instalar y configurar API Platform 0 / 3|3 h 4 min
### [10. Instalar y configurar API Platform y recurso para usuarios 1 h 17 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451554#questions/9295602)
- 
### [11. Configurar recurso y seguridad de User y tests funcionales 1 h 9 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451568#questions/9295602)
- 
### [12. Tests unitarios para Register y Validators 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451578#questions/9295602)
- 

### Sección 7: Grupos 0 / 5|3 h 20 min
### [13. Entidad para grupos y actualización de la configuración de API Platform 20 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451596#questions/9295602)
- 
### [14. Configurar seguridad para grupos 52 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451602#questions/9295602)
- 
### [15. Crear endpoint para añadir usuarios a un grupo 1 h 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451610#questions/9295602)
- 
### [16. Crear endpoint para eliminar usuarios de un grupo 34 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451618#questions/9295602)
- 
### [17. Tests funcionales para grupo 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451626#questions/9295602)
- 

### Sección 8: Categorías 0 / 2|49 min
### [18. Crear Categorías y migración 15 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451640#questions/9295602)
- 
### [19. Seguridad y tests para categorías 34 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451648#questions/9295602)
- 

### Sección 9: Gastos 0 / 3|1 h 14 min
### [20. Crear entidades para gastos y migración 19 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451664#questions/9295602)
- 
### [21. Configurar seguridad para gastos 21 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451672#questions/9295602)
- 
### [22. Tests funcionales para gastos 33 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451678#questions/9295602)
- 

### Sección 10: Refactoring y bugfix 0 / 1|4 min
### [23. Refactoring y bugfix 4 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451694#questions/9295602)
- 

### Sección 11: Filtros 0 / 2|45 min
### [24. Filtros por defecto de API Platform 29 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451702#questions/9295602)
- 
### [25. Filtros avanzados de API Platform 16 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451712#questions/9295602)
- 
### Sección 12: Personalizar respuestas de la API 0 / 1|9 min
### [26. Personalizar respuestas de la API 9 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451718#questions/9295602)
- 