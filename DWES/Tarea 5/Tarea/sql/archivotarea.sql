-- Creamos la base de datos
create database practicaUnidad5;
-- La seleccionamos
use practicaUnidad5;
create user gestor@'localhost' identified by 'secreto';
-- Reutilizamos el usuario gestor que ya teniamos (Podemos crear otro)
grant all on practicaUnidad5.* to gestor@'localhost';
 -- Creamos las Tablas --
 create table jugadores(
    id int auto_increment primary key,
    nombre varchar(40) not null,
    apellidos varchar(60) not null,
    dorsal int unique,
    posicion enum('Portero', 'Defensa', 'Lateral Izquierdo', 'Lateral Derecho', 'Central', 'Delantero')
   
 );

-- ## Si no funciona fazinotto/faker, insertamos Algunos datos, descomentando las siguientes sentencias SQL   ##
-- insert into jugadores(nombre, apellidos, dorsal, posicion) values('Antonio','Gil Gil', 1, 1);
-- insert into jugadores(nombre, apellidos, dorsal, posicion) values('Ana','Hernandez Perez', 2, 2);
-- insert into jugadores(nombre, apellidos, dorsal, posicion) values('Juan','Valdemoro Gil', 3, 2);
-- insert into jugadores(nombre, apellidos, dorsal, posicion) values('Maria','Ruano Perez', 4, 2);
