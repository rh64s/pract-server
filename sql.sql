create table Roles(
    id int primary key auto_increment,
    name varchar(255) not null
);
create table Users(
	id int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) not null,
    surname varchar(255) not null,
    patronymic varchar(255),
    email varchar(255) not null,
    phone varchar(13) not null,
    password varchar(255) not null,
    login varchar(255),
    role_id int not null,
    foreign key (role_id) REFERENCES Roles (id)
);

create table Divisions(
	id int primary key auto_increment,
    name varchar(255) not null,
    user_id int not null,
    foreign key (user_id) REFERENCES Users(id)
);

create table UnitTypes(
	id int primary key auto_increment,
    name varchar(255) not null
);

create table Products (
	id int primary key auto_increment,
    name varchar(255) not null,
    articul varchar(255) not null,
    unit_type_id int not null,
    foreign key (unit_type_id) REFERENCES UnitTypes (id)
);

create table Orders (
	id int primary key auto_increment,
    division_id int not null,
    product_id int not null,
    count int not null,

    foreign key (division_id) REFERENCES Divisions (id),
    foreign key (product_id) REFERENCES Products (id)
);

create table DivisionsProducts (
	division_id int not null,
    product_id int not null,
    count int not null,
    foreign key (division_id) REFERENCES Divisions (id),
    foreign key (product_id) REFERENCES Products (id),
    min_value int not null default 1,
    primary key (division_id, product_id)
);

insert into Roles (id, name) VALUES (1, 'superadmin'), (2, 'admin'), (3, 'storekeeper')
