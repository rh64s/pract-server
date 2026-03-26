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
    id   serial primary key,
    name varchar(255) not null
);

-- make that users can have only one division

create table users
(
    id         serial PRIMARY KEY,
    name       varchar(255) not null,
    surname    varchar(255) not null,
    patronymic varchar(255),
    email      varchar(255) not null,
    phone      varchar(13)  not null,
    password   varchar(255) not null,
    login      varchar(255),
    role_id    bigint unsigned         not null,
    foreign key (role_id) REFERENCES roles (id)
);
create table divisions
(
    id      serial primary key,
    name    varchar(255) not null,
    user_id bigint unsigned,
    foreign key (user_id) REFERENCES users (id) on delete no action on update cascade
);

create table unit_types
(
    id   serial primary key,
    name varchar(255) not null
);

create table products
(
    id           serial primary key,
    name         varchar(255) not null,
    articul      varchar(255) not null,
    unit_type_id bigint unsigned,
    foreign key (unit_type_id) REFERENCES unit_types (id) on delete set null on update cascade
);

create table orders
(
    id          serial primary key,
    division_id bigint unsigned not null,
    product_id  bigint unsigned not null,
    count       int not null,
    created_at  TIMESTAMP not null default now(),
    updated_at  TIMESTAMP,

    foreign key (division_id) REFERENCES divisions (id),
    foreign key (product_id) REFERENCES products (id)
);

create table divisions_products
(
    division_id bigint unsigned not null,
    product_id  bigint unsigned not null,
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