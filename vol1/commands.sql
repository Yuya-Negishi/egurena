create database dotinstall_todo_app;
grant all on dotinstall_todo_app.* to dbuser@locahost identified by '3EwruTREsc2';

use dotinstall_todo_app

create table tasks(
	id int not null auto_increment primary key,
	seq int not null,
	type enum('notyet' , 'done','delete') default 'notyet',
	title text,
	created datetime,
	modified datetime,
	KEY type(type),
	KEY seq(seq)
);

insert into tasks(seq,type,title,created,modified) values
 (1,'notyet','牛乳を買う',now(),now()),
 (2,'notyet','提案書をつくる',now(),now()),
 (3,'done','映画を見る',now(),now());
 
 ALTER TABLE tasks ADD task_team varchar(50) NULL ;
 
 create table task_group(
	id int not null auto_increment primary key,
	group_name varchar(50) not null,
	seq int not null ,
	type enum('notyet','done','delete') default 'notyet',
	KEY type(type),
	KEY seq(seq)
	);
	
insert into task_group (group_name) values
 ('朝やること'),
 ('今日のタスク');

 ALTER TABLE task_group ADD seq int not null;
 ALTER TABLE task_group ADD type enum('notyet' , 'done','delete') default 'notyet';
 
 ALTER TABLE task_group DROP type;
 
 select * from tasks  right join task_group on tasks.groupName = task_group.group_name; 
 
 UPDATE task_group left join tasks on 
 
create table users(
	id int not null auto_increment primary key,
	facebook_user_id varchar(255),
	facebook_name varchar(255),
	facebook_picture varchar(255),
	facebook_access_token varchar(255),
	username varchar(50),
	created datatime,
	modified datetime
);

create table teams(
	id int not null auto_increment primary key,
	teamname varchar(50) not null,
	teampass varchar(100) not null
);
$sql4 = insert into teams (teamname,teampass) values (:teamId,:teamPass);

CREATE TABLE followTeam (
	id int not null auto_increment primary key,
	task_group int,
	team int,
);
INSERT INTO followteam (team,task_group) value ($teamId,$groupId);
 
create table taskTeam (
	id int not null auto_increment primary key,
	teamname varchar(30),
	teampass varchar(100),
	teamTitle varchar(30)
);

create table userTeamS(
	id int not null auto_increment primary key,
	userId int not null,
	teamId int not null
	);

create table subtask (
    id int not null auto_increment primary key,
    subtaskTitle varchar(50),
    type enum('notyet','done','delete') default 'notyet',
    seq int ,
    tasksid int
   );

alter table users add email varchar(50);

INSERT INTO
       table (a, b, c)
   VALUES
       (1, 12, 100)
   ON DUPLICATE KEY UPDATE
        b = 20
        , c = 200;
