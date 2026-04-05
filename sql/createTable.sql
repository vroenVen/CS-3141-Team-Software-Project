use tsp;
drop table if exists Users;
drop table if exists Files;

create table Users (
  id int auto_increment,
  username varchar(25) not null,
  email varchar(50) not null,
  password char(64) not null,
  primary key(id)
);

create table Files (
  id int auto_increment,
  uuid uuid not null,
  owner int not null,
  primary key(id),
  foreign key(owner) references Users(id)
	on update cascade
);


create table SharedFiles (
  file_id int not null,
  shared_with int not null,
  primary key(file_id, shared_with),
  foreign key(file_id) references Files(id)
    on delete cascade on update cascade,
  foreign key(shared_with) references Users(id)
    on delete cascade on update cascade
);