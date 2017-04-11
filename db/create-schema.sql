create database advance default charset utf8 collate utf8_general_ci;
grant all privileges on advance.* to 'motorbike'@'localhost' identified by '';
grant all privileges on advance.* to 'motorbike'@'%' identified by '';
