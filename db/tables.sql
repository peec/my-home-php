DROP TABLE IF EXISTS `boxee`;
DROP TABLE IF EXISTS `imdb`;
DROP TABLE IF EXISTS `macros`;
DROP TABLE IF EXISTS `device_logs`;
DROP TABLE IF EXISTS  `devices`;
DROP TABLE IF EXISTS  `rooms`;
DROP TABLE IF EXISTS `floors`;
DROP TABLE IF EXISTS  `scripts`;


CREATE TABLE boxee(
	boxee_id int(11) PRIMARY KEY AUTO_INCREMENT,
	host varchar(255),
	identity varchar(255) UNIQUE,
	app_id varchar(255) UNIQUE,
	boxee_name varchar(255)
);
CREATE TABLE imdb(
	path_hash varchar(255) PRIMARY KEY,
	data text,
	last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);






CREATE TABLE `floors`(
	floor_id int(11) PRIMARY KEY AUTO_INCREMENT,
	floor_name varchar(255),
	floor_description text,
	floor_pos int(11)
);

CREATE TABLE `rooms`(
	room_id int(11) PRIMARY KEY AUTO_INCREMENT,
	floor_id int(11) NOT NULL,
	room_name varchar(255),
	room_description text,
	FOREIGN KEY (floor_id) REFERENCES floors(floor_id) 
);


CREATE TABLE `scripts`(
	script_id int(11) PRIMARY KEY AUTO_INCREMENT,
	script text,
	script_name varchar(255),
	active boolean
	
);


CREATE TABLE `devices` (
	device_code char(2) 	PRIMARY KEY,
	room_id int(11) NOT NULL,
	device_name varchar(255),
	device_desc text,
	device_flags int(11),
	powerline_communication boolean,
	FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);


CREATE TABLE `device_logs` (
	log_id int(11) PRIMARY KEY AUTO_INCREMENT,
	device_code char(2),
	method varchar(60),
	args varchar(400),
	ip_addr varchar(256),
	command text,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (device_code) REFERENCES devices(device_code)
);

CREATE TABLE `macros`(
	macro_id int(11) PRIMARY KEY AUTO_INCREMENT,
	macro_name varchar(255),
	macro_desc text,
	macro text,
	floor_id int(11),
	room_id int(11),
	count_usage int(11),
	FOREIGN KEY (floor_id) REFERENCES floors(floor_id),
	FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);



INSERT INTO floors VALUES(1, 'Basement', 'Devices in the basement', 0);
INSERT INTO floors VALUES(2, 'Ground floor', 'Devices in the ground floor', 1);
INSERT INTO floors VALUES(3, 'Second floor', 'Devices in the second floor', 2);


INSERT INTO rooms VALUES(1,1,'Appartment - Living room', NULL); 
INSERT INTO rooms VALUES(2,1,'Appartment - Kitchen', NULL); 
INSERT INTO rooms VALUES(3,1,'Appartment - Bedroom', NULL); 
INSERT INTO rooms VALUES(4,1,'Bathroom', NULL); 
INSERT INTO rooms VALUES(5,1,'Bedroom', NULL);

INSERT INTO rooms VALUES(6,2,'Living room', NULL);
INSERT INTO rooms VALUES(7,2,'Kitchen', NULL);
INSERT INTO rooms VALUES(8,2,'Master bedroom', NULL);
INSERT INTO rooms VALUES(9,2,'Guest bedroom', NULL);
INSERT INTO rooms VALUES(10,2,'Office', NULL);
INSERT INTO rooms VALUES(11,2,'Entryway', NULL);

 
INSERT INTO rooms VALUES(12,3,'Bedroom', NULL); 
INSERT INTO rooms VALUES(13,3,'Living room + Bedroom', NULL); 



INSERT INTO devices VALUES('e1',13,'Ceiling lights', NULL, 3, true); 




INSERT INTO `scripts` (`script_id`, `script`, `script_name`, `active`) VALUES
(1, '// Wake up lights on bedrooms.\r\nif (clockIs(7)){\r\n$x10->allLightsOn();\r\n}\r\n// Everyone should be at work this time.\r\nif (clockIs(10)){\r\n$x10->allUnitsOff();\r\n}\r\n\r\n// At midnight turn all off.\r\nif(clockIs(0)){\r\n$x10->allUnitsOff();\r\n}\r\n\r\n\r\n', 'Powersaver', 0);



