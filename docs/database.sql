create table users (
    id serial primary key,
    name varchar(50) not null,
    email varchar(50) not null,
    pass varchar(128) not null
);

create table tweets (
    id serial primary key,
    content varchar(140) not null,
    created date not null,
    author int not null,
    foreign key (author) references users (id)
);