Tutorial de ZF2 + Doctrine
=======================

Introducción
------------
Esta es una aplicación desde cero de ZF2 a la que se le añade un nuevo modulo llamado Album para utilizarlo con Doctrine.

Creación de la base de datos
----------------------------
<pre>
CREATE DATABASE  IF NOT EXISTS `doctrine`;
USE `doctrine`;

CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `album` VALUES (1,'moniquita','de fiesta en el fabrik');

CREATE TABLE `track` (
  `track_id` int(11) NOT NULL AUTO_INCREMENT,
  `track_title` varchar(255) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`track_id`),
  KEY `fk_track_1_idx` (`album_id`),
  CONSTRAINT `fk_track_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `track` VALUES (1,'fiestonnnn',1);
</pre>


Instalación
-----------
<ol>
    <li>Crear el esqueleto de ZF2<br/>
        git clone git://github.com/zendframework/ZendSkeletonApplication.git zftutordoctrine
    </li><br/>
    <li>Añadir Doctrine al proyecto<br/>
        <pre>
        {
            "name": "susana/doctrinetuto",
            "description": "ZF2 y Doctrine",
            "keywords": [
                "framework",
                "zf2",
                "doctrine"
            ],
            "require": {
                "php": ">=5.3.3",
                "zendframework/zendframework": "~2.3",
                "doctrine/doctrine-orm-module": "0.*",
                "zendframework/zftool": "dev-master"
            }
        }
        </pre>
    </li><br/>
    <li>Crear un modulo nuevo llamado "Album"<br/>
        ./vendor/bin/zf.php create module Album
    </li>
    <li>Crear un nuevo fichero de configuración, config/autoload/doctrine.global.php<br/>
        <pre>
        //config/autoload/doctrine.global.php
            return array(
                'doctrine' => array(
                    'connection' => array(
                        'orm_default' => array(
                            'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                                'params' => array(
                                    'host' => 'localhost',
                                    'port' => '5432',
                                    'dbname' => 'zftutordoctrine',
                            ),
                        ),
                    )
            ));
        </pre>
    </li>
    <li>Crear un nuevo fichero de configuración, config/autoload/doctrine.local.php<br/>
    <pre>
    //config/autoload/doctrine.local.php
        return array(
            'doctrine' => array(
                'connection' => array(
                    'orm_default' => array(
                        'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                            'params' => array(
                                'user' => 'developer',
                                'password' => '123456',
                        ),
                    ),
                )
        ));
    </pre>
    </li>
    <li>Registrar Album\Entity en el driver de doctrine en el modulo nuevo, /Album/config/module.config.php<br/>
        <pre>
        //module/Album/config/module.config.php
            return array(
                'doctrine' => array(
                    'driver' => array(
                        'Album_driver' => array(
                            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'cache' => 'array',
                            'paths' => array(__DIR__ . '/../src/Album/Entity')
                        ),
                        'orm_default' => array(
                            'drivers' => array(
                                 'Album\Entity' =>  'Album_driver'
                            ),
                        ),
                    ),
                ),                
            );
        </pre>
    </li>
    <li>Registrar los nuevos modulos de Doctrine en config/application.config.php<br/>
        <pre>
        //config/application.config.php
            return array(
                'modules' => array(
                    'Application',
                    'DoctrineModule',
                    'DoctrineORMModule',
                    'Album'
                ),
                 
                // These are various options for the listeners attached to the ModuleManager
                'module_listener_options' => array(
                    'module_paths' => array(
                        './module',
                        './vendor',
                    ),
                    'config_glob_paths' => array(
                        'config/autoload/{,*.}{global,local}.php',
                    ),
                ),
            );
        </pre>
    </li>
    <li>Convert-mapping<br/>
        ./vendor/bin/doctrine-module orm:convert-mapping --namespace="Album\\Entity\\" --force  --from-database annotation ./module/Album/src/
    </li><br/>
    <li>Generate-entities<br/>
        ./vendor/bin/doctrine-module orm:generate-entities ./module/Album/src/ --generate-annotations=true 
    </li><br/>
    <li>Crear un nuevo controlador para el modulo Album<br/>
        ./vendor/bin/zf.php create controller Index Album
    </li><br/>
    <li> Crear la estructura de vistas para el controlador
    </li><br/>
    <li>Registrar el controlador y las vistas en el modulo en Album/config/module.config.php añadiendo<br/>
        <pre>
        'router' => array(
                    'routes' => array(
                        'album_home' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/album',
                                'defaults' => array(
                                    'controller' => 'Album\Controller\Index',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                    ),
                ),
                'controllers' => array(
                    'invokables' => array(
                        'Album\Controller\Index' => 'Album\Controller\IndexController'
                    ),
                ),
                'view_manager' => array(
                    'display_not_found_reason' => true,
                    'display_exceptions'       => true,
                    'doctype'                  => 'HTML5',
                    'not_found_template'       => 'error/404',
                    'exception_template'       => 'error/index',
                    'template_map' => array(
                        'layout/layout'           => __DIR__ . '/../view/album/layout/layout.phtml',
                        'album/index/index' => __DIR__ . '/../view/album/index/index.phtml',
                        'error/404'               => __DIR__ . '/../view/album/error/404.phtml',
                        'error/index'             => __DIR__ . '/../view/album/error/index.phtml',
                    ),
                    'template_path_stack' => array(
                        __DIR__ . '/../view',
                    ),
                ),
        </pre>
    </li>
</ol>

    
    
    

VirtualHost de Apache 2.4
-------------------------
<pre>
<VirtualHost *:80>
	ServerAdmin susana.ml82@gmail.com
	ServerName  zftutordoctrine.localhost

	SetEnv APPLICATION_ENV "development"

	DocumentRoot /home/susana/workspace/zftutordoctrine/public
	<Directory />
		Options FollowSymLinks
		AllowOverride None
	</Directory>
	<Directory /home/susana/workspace/zftutordoctrine/public>
		 DirectoryIndex index.php
		 AllowOverride All
		 Require all granted
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/zfdoctrine_error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog ${APACHE_LOG_DIR}/zfdoctrine_access.log combined
</VirtualHost>
</pre>