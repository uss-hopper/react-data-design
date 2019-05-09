-- this is a comment in SQL (yes, the space is needed!)
-- these statements will drop the tables and re-add them
-- this is akin to reformatting and reinstalling Windows (OS X never needs a reinstall...) ;)
-- never ever ever ever ever ever ever ever ever ever ever ever ever ever ever ever ever ever ever ever
-- do this on live data!!!!
DROP TABLE IF EXISTS `like`;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS tweet;
DROP TABLE IF EXISTS profile;
-- the CREATE TABLE function is a function that takes tons of arguments to layout the table's schema
CREATE TABLE profile (
	-- this creates the attribute for the primary key
	-- auto_increment tells mySQL to number them {1, 2, 3, ...}
	-- not null means the attribute is required!
	profileId BINARY(16) NOT NULL,
	profileActivationToken CHAR(32),
	profileAtHandle VARCHAR(32) NOT NULL UNIQUE,
	profileAvatarUrl  VARCHAR(255),
	-- to make sure duplicate data cannot exist, create a unique index
	profileEmail VARCHAR(128) NOT NULL,
	-- to make something optional, exclude the not null
	profileHash CHAR(97) NOT NULL,
	profilePhone VARCHAR(32),
	UNIQUE(profileEmail),
	UNIQUE(profileAtHandle),
	-- this officiates the primary key for the entity
	PRIMARY KEY(profileId)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- create the tweet entity
CREATE TABLE tweet (
	-- this is for yet another primary key...
	tweetId BINARY(16) NOT NULL,
	-- this is for a foreign key; auto_incremented is omitted by design
	tweetProfileId BINARY(16) NOT NULL,
	tweetContent VARCHAR(140) NOT NULL,
	-- notice dates don't need a size parameter
	tweetDate DATETIME(6) NOT NULL,
	-- this creates an index before making a foreign key
	INDEX(tweetProfileId),
	-- this creates the actual foreign key relation
	FOREIGN KEY(tweetProfileId) REFERENCES profile(profileId),
	-- and finally create the primary key
	PRIMARY KEY(tweetId)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- create the tweetImage entity
CREATE TABLE image (
	imageId BINARY(16) NOT NULL,
	imageTweetId BINARY(16) NOT NULL,
	imageCloudinaryToken VARCHAR(255) NOT NULL,
	imageUrl VARCHAR(128) NOT NULL ,
	INDEX(imageId),
	INDEX(imageTweetId),
	FOREIGN KEY(imageTweetId) REFERENCES tweet(tweetId),
	PRIMARY KEY (imageId)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- create the like entity (a weak entity from an m-to-n for profile --> tweet)
CREATE TABLE `like` (
	-- these are not auto_increment because they're still foreign keys
	likeTweetId BINARY(16) NOT NULL,
	likeProfileId BINARY(16) NOT NULL,
	likeDate DATETIME(6) NOT NULL,	-- index the foreign keys
	INDEX(likeProfileId),
	INDEX(likeTweetId),
	-- create the foreign key relations
	FOREIGN KEY(likeTweetId) REFERENCES tweet(tweetId),
	FOREIGN KEY(likeProfileId) REFERENCES profile(profileId),
	-- finally, create a composite foreign key with the two foreign keys
	PRIMARY KEY(likeProfileId, likeTweetId)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;