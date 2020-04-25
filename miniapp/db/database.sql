DROP DATABASE IF EXISTS proyecto;
CREATE DATABASE proyecto CHARSET utf8mb4;
USE proyecto;

CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  age INT(3) NOT NULL,
  email VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE productos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(300) NOT NULL,
  img VARCHAR(100),
  precio INT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE carrito (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(300) NOT NULL,
  precio INT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users VALUES (0, 'tote', 'tote', 21, 'perturbrugal@gmail.com');
INSERT INTO users VALUES (0, 'lamp_user', 'lamp_password', 32, 'admin@admin.er');

INSERT INTO productos VALUES (0, 'Lenovo Ideapad', 'Portatil Gaming', '/images/lenovoideapad.png', 1000);
INSERT INTO productos VALUES (0, 'Huawei P20Pro', 'Movil de alta gama', '/images/p20pro.png', 900);
INSERT INTO productos VALUES (0, 'HyperX Cloud Flight', 'Auriculares inalambricos gaming', '/images/hyperx.png', 100);
INSERT INTO productos VALUES (0, 'Motorspeed keyborad', 'Keyboard switch blue', '/images/motorspeed.png', 50);
INSERT INTO productos VALUES (0, 'Sharkoon drakonia', 'Raton gaming', '/images/drakonia.png', 35);
INSERT INTO productos VALUES (0, 'Nvidia Geforce 960', 'Tarjeta grafica Gaming', '/images/960.png', 340);
INSERT INTO productos VALUES (0, 'Kingston 16GB', 'Memorian RAM', '/images/kingston.png', 120);
INSERT INTO productos VALUES (0, 'Play station 4', 'Consola', '/images/ps4.png', 400);