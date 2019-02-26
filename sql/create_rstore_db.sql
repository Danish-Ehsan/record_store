DROP DATABASE IF EXISTS record_store_db;
CREATE DATABASE record_store_db;
USE record_store_db;

CREATE TABLE artists (
	artistID		INT				NOT NULL	AUTO_INCREMENT,
	artistName		VARCHAR(80)		NOT NULL,
	PRIMARY KEY (artistID)
);

CREATE TABLE albums (
	albumID			INT 			NOT NULL	AUTO_INCREMENT,
	artistID		INT 			NOT NULL,
	albumName		VARCHAR(255)	NOT NULL,
	year			INT 			NOT NULL,
	label			VARCHAR(80)		NOT NULL,
	price			DECIMAL(10,2)	NOT NULL,
	featured		BOOLEAN			NOT NULL 	DEFAULT FALSE,
	PRIMARY KEY (albumID)
);

CREATE TABLE songs (
	songSequence	INT 			NOT NULL,
	albumID			INT 			NOT NULL,
	songName		VARCHAR(255)	NOT NULL,
	PRIMARY KEY (songSequence, albumID)
);

CREATE TABLE customers (
	customerID		INT 			NOT NULL	AUTO_INCREMENT,
	emailAddress	VARCHAR(255)	NOT NULL,
	password		VARCHAR(100)		NOT NULL,
	firstName		VARCHAR(60)		NOT NULL,
	lastName		VARCHAR(60)		NOT NULL,
	addressID		INT 						DEFAULT NULL,
	disabled		BOOLEAN			NOT NULL	DEFAULT FALSE,
	PRIMARY KEY (customerID),
	UNIQUE INDEX emailAddress (emailAddress)
);

CREATE TABLE addresses (
	addressID		INT 			NOT NULL	AUTO_INCREMENT,
	customerID		INT 						DEFAULT NULL,
	country			VARCHAR(60)		NOT NULL,
	city			VARCHAR(60)		NOT NULL,
	postalCode		VARCHAR(10)		NOT NULL,
	line1			VARCHAR(60)		NOT NULL,
	line2			VARCHAR(60)					DEFAULT NULL,
	PRIMARY KEY (addressID),
	INDEX customerID (customerID)
);

CREATE TABLE orders (
	orderID			INT 			NOT NULL	AUTO_INCREMENT,
	customerID		INT 			NOT NULL,
	addressID		INT 			NOT NULL,
	orderDate		DATE			NOT NULL,
	shipDate		DATE 						DEFAULT NULL,
	price			DECIMAL(10,2)	NOT NULL,
	cardType		VARCHAR(60)		NOT NULL,
	PRIMARY KEY (orderID),
	INDEX customerID (customerID)
);

CREATE TABLE orderItems (
	itemID			INT 			NOT NULL	AUTO_INCREMENT,
	orderID			INT 			NOT NULL,
	productID		INT 			NOT NULL,
	itemPrice		DECIMAL(10,2) 	NOT NULL,
	quantity		INT 			NOT NULL,
	PRIMARY KEY (itemID),
	INDEX orderID (orderID),
	INDEX productID (productID)
);

CREATE TABLE admins (
	adminID           INT            NOT NULL   AUTO_INCREMENT,
	emailAddress      VARCHAR(255)   NOT NULL,
	password          VARCHAR(100)   NOT NULL,
	firstName         VARCHAR(60)    NOT NULL,
	lastName          VARCHAR(60)    NOT NULL,
	PRIMARY KEY (adminID)
);

CREATE TABLE adminPermissions (
	adminID			INT				NOT NULL,
	updateItems		BOOLEAN			NOT NULL,
	deleteItems		BOOLEAN			NOT NULL,
	updateAdmins	BOOLEAN			NOT NULL,
	PRIMARY KEY (adminID)
);

INSERT INTO artists (artistName)
VALUES
	('Foo Fighters'),
	('Metric'),
	('Nine Inch Nails'),
	('A Perfect Circle'),
	('Queens of the Stone Age'),
	('Radiohead'),
	('Tool');

INSERT INTO albums (artistID, albumName, year, label, price, featured)
VALUES 
	(1, 'Echoes, Silence, Patience & Grace', 2007, 'Roswell RCA', 20.00, TRUE),
	(2, 'Fantasies', 2009, 'Metric Music International, Last Gang, Mom + Pop', 60.00, TRUE),
	(3, 'Year Zero', 2007, 'Interscope', 60.00, TRUE),
	(4, 'Thirteenth Step', 2003, 'Virgin', 20.00, TRUE),
	(5, 'Songs for the Deaf', 2002, 'Interscope', 30.00, TRUE),
	(6, 'In Rainbows', 2007, 'Self-released', 22.00, TRUE),
	(7, 'Lateralus', 2001, 'Volcano Entertainment', 35.00, TRUE);

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 1, 'The Pretender'),
	(2, 1, 'Let It Die'),
	(3, 1, 'Erase/Replace'),
	(4, 1, 'Long Road to Ruin'),
	(5, 1, 'Come Alive'),
	(6, 1, 'Stranger Things Have Happened'),
	(7, 1, 'Cheer Up, Boys (Your Make Up Is Running)'),
	(8, 1, 'Summers End'),
	(9, 1, 'Ballad of the Beaconsfield Miners'),
	(10, 1, 'Statues'),
	(11, 1, 'But, Honestly'),
	(12, 1, 'Home');

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 2, "Help I'm Alive"),
	(2, 2, "Sick Muse"),
	(3, 2, "Satellite Mind"),
	(4, 2, "Twilight Galaxy"),
	(5, 2, "Gold Guns Girls"),
	(6, 2, "Gimme Sympathy"),
	(7, 2, "Collect Call"),
	(8, 2, "Front Row"),
	(9, 2, "Blindness"),
	(10, 2, "Stadium Love");

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 3, "Hyperpower!"),
	(2, 3, "The Beginning of the End"),
	(3, 3, "Survivalism"),
	(4, 3, "The Good Soldier"),
	(5, 3, "Vessel"),
	(6, 3, "Me, I'm Not"),
	(7, 3, "Capital G"),
	(8, 3, "My Violent Heart"),
	(9, 3, "The Warning"),
	(10, 3, "God Given"),
	(11, 3, "Meet Your Master"),
	(12, 3, "The Greater Good"),
	(13, 3, "The Great Destroyer"),
	(14, 3, "Another Version of the Truth"),
	(15, 3, "In This Twilight"),
	(16, 3, "Zero-Sum");

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 4, "The Package"),
	(2, 4, "Weak and Powerless"),
	(3, 4, "The Noose"),
	(4, 4, "Blue"),
	(5, 4, "Vanishing"),
	(6, 4, "A Stranger"),
	(7, 4, "The Outsider"),
	(8, 4, "Crimes"),
	(9, 4, "The Nurse Who Loved Me (Failure cover)"),
	(10, 4, "Pet"),
	(11, 4, "Lullaby"),
	(12, 4, "Gravity");

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 5, "You Think I Aint Worth a Dollar, But I Feel Like a Millionaire"),
	(2, 5, "No One Knows"),
	(3, 5, "First It Giveth"),
	(4, 5, "A Song for the Dead"),
	(5, 5, "The Sky Is Fallin"),
	(6, 5, "Six Shooter"),
	(7, 5, "Hangin Tree"),
	(8, 5, "Go with the Flow"),
	(9, 5, "Gonna Leave You"),
	(10, 5, "Do It Again"),
	(11, 5, "God Is In The Radio"),
	(12, 5, "Another Love Song"),
	(13, 5, "A Song for the Deaf"),
	(14, 5, "Mosquito Song (Hidden Track)");

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 6, "15 Step"),
	(2, 6, "Bodysnatchers"),
	(3, 6, "Nude"),
	(4, 6, "Weird Fishes/Arpeggi"),
	(5, 6, "All I Need"),
	(6, 6, "Faust Arp"),
	(7, 6, "Reckoner"),
	(8, 6, "House of Cards"),
	(9, 6, "Jigsaw Falling into Place"),
	(10, 6, "Videotape");

INSERT INTO songs (songSequence, albumID, songName)
VALUES
	(1, 7, "The Grudge"),
	(2, 7, "Eon Blue Apocalypse (instrumental)"),
	(3, 7, "The Patient"),
	(4, 7, "Mantra (instrumental)"),
	(5, 7, "Schism"),
	(6, 7, "Parabol"),
	(7, 7, "Parabola"),
	(8, 7, "Ticks & Leeches"),
	(9, 7, "Lateralus"),
	(10, 7, "Disposition"),
	(11, 7, "Reflection"),
	(12, 7, "Triad"),
	(13, 7, "Faaip de Oiad");

INSERT INTO customers(emailAddress, password, firstName, lastName)
VALUES
	('johndoe@localhost.com', '4fa7325f0ea4e82ecc94113885099a964c5f579a', 'John', 'Doe');

INSERT INTO admins (emailAddress, password, firstName, lastName)
VALUES
	('danish@localhost.com', '3c261e338a7277f105ef27ea3edbc893ab75e76f', 'Danish', 'Ehsan');

INSERT INTO adminPermissions (adminID, updateItems, deleteItems, updateAdmins)
VALUES
	(1, TRUE, TRUE, TRUE);

GRANT SELECT, INSERT, UPDATE, DELETE
ON *
TO danish@localhost
IDENTIFIED BY 'admin';