DROP TABLE IF EXISTS event_in_group;
DROP TABLE IF EXISTS group_information;
DROP TABLE IF EXISTS event_information;
DROP TABLE IF EXISTS cached_area_has_parent;
DROP TABLE IF EXISTS area_information;
DROP TABLE IF EXISTS country;

CREATE TABLE country (
	id INTEGER PRIMARY KEY NOT NULL,
	two_char_code VARCHAR(2) NOT NULL,
	title VARCHAR(255) NOT NULL,
	timezones TEXT NOT NULL
);

CREATE TABLE area_information (
	id INTEGER PRIMARY KEY NOT NULL,
	slug VARCHAR(255),
	title VARCHAR(255),
	country_id INTEGER NOT NULL,
	parent_area_id INTEGER NULL
);

CREATE TABLE cached_area_has_parent (
  area_id INTEGER NOT NULL,
  has_parent_area_id INTEGER NOT NULL,
  PRIMARY KEY(area_id, has_parent_area_id)
);

CREATE TABLE event_information (
	id INTEGER PRIMARY KEY NOT NULL,
	slug VARCHAR(255),
	summary VARCHAR(255),
	description TEXT,
	url TEXT,
	country_id INTEGER NOT NULL,
	area_id INTEGER NULL,
	timezone VARCHAR(255) NOT NULL,
	start_at INTEGER NOT NULL,
	end_at INTEGER NOT NULL
);


CREATE TABLE group_information (
	id INTEGER PRIMARY KEY NOT NULL,
	slug VARCHAR(255),
	title VARCHAR(255),
	description TEXT,
	url TEXT
);

CREATE TABLE event_in_group (
	event_id INTEGER NOT NULL,
	group_id INTEGER NOT NULL,
	is_main_group INTEGER NOT NULL DEFAULT 0
)