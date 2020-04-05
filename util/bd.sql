DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca;
USE biblioteca;

CREATE TABLE autor(
	id INT AUTO_INCREMENT,
	nombreA VARCHAR(100),
	PRIMARY KEY(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE editorial(
	idE INT AUTO_INCREMENT,
	nomE VARCHAR(100),
	PRIMARY KEY(idE)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE libro(
	folio VARCHAR(10) PRIMARY KEY,
	titulo VARCHAR(100),
	paginas INT,
	anho INT(4),
	editorial INT,
	FOREIGN KEY(editorial) REFERENCES editorial(idE)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE escribe(
	libro VARCHAR(10),
	autor INT,
	PRIMARY KEY(libro,autor),
	FOREIGN KEY(libro) REFERENCES libro(folio) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY(autor) REFERENCES autor(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE usuario(
	idUs INT AUTO_INCREMENT,
	us VARCHAR(20),
	pass VARCHAR(255),
	nombreUs VARCHAR(100),
	status BIT,
	PRIMARY KEY(idUs),
	UNIQUE(us),
	token VARCHAR(255)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE permiso(
	clvP VARCHAR(10) PRIMARY KEY,
	nombreP VARCHAR(255)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE permite(
	permiso VARCHAR(10),
	usuario INT,
	FOREIGN KEY(permiso) REFERENCES permiso(clvP) ON UPDATE CASCADE,
	FOREIGN KEY(usuario) REFERENCES usuario(idUs),
	PRIMARY KEY(permiso,usuario)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT usuario VALUE (0,"admin","$2y$10$sJUu7DZ2VgfF8FMqdJ6UYOAkfBcyS/1jxuJNuvs71tVD8EIx4wwP2","Juan Perez",1,"");
INSERT editorial VALUES (1,"Editorial 1"),(2,"Editorial 2");
INSERT autor VALUES (1,"Autor 1"), (2,"Autor 2");

INSERT permiso VALUES("i-lib","Insertar libro"),("u-lib","Editar Libro");

INSERT permite VALUES ("i-lib",0);