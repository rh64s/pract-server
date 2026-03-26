DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS divisions_products;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS unit_types;
DROP TABLE IF EXISTS divisions;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS posts;

create table roles
(
    id   int primary key auto_increment,
    name varchar(255) not null
);
create table users
(
    id         int PRIMARY KEY AUTO_INCREMENT,
    name       varchar(255) not null,
    surname    varchar(255) not null,
    patronymic varchar(255),
    email      varchar(255) not null,
    phone      varchar(13)  not null,
    password   varchar(255) not null,
    login      varchar(255),
    role_id    int          not null,
    foreign key (role_id) REFERENCES roles (id)
);

create table divisions
(
    id      int primary key auto_increment,
    name    varchar(255) not null,
    user_id int,
    foreign key (user_id) REFERENCES users (id) on delete no action on update cascade
);

create table unit_types
(
    id   int primary key auto_increment,
    name varchar(255) not null
);

create table products
(
    id           int primary key auto_increment,
    name         varchar(255) not null,
    articul      varchar(255) not null,
    unit_type_id int          not null,
    foreign key (unit_type_id) REFERENCES unit_types (id) on delete no action on update cascade
);

create table orders
(
    id          int primary key auto_increment,
    division_id int not null,
    product_id  int not null,
    count       int not null,
    created_at  TIMESTAMP not null default now(),
    updated_at  TIMESTAMP,

    foreign key (division_id) REFERENCES divisions (id),
    foreign key (product_id) REFERENCES products (id)
);

create table divisions_products
(
    division_id int not null,
    product_id  int not null,
    count       int not null,
    foreign key (division_id) REFERENCES divisions (id) on delete cascade,
    foreign key (product_id) REFERENCES products (id) on delete cascade,
    min_value   int not null default 1,
    primary key (division_id, product_id)
);

insert into roles (id, name)
VALUES (1, 'superadmin'),
       (2, 'admin'),
       (3, 'storekeeper');

CREATE TABLE posts
(
    id    int PRIMARY KEY auto_increment,
    title varchar(255) NOT NULL,
    text  varchar(255)
);


