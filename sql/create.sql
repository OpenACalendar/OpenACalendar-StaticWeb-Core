CREATE TABLE country (
	id INTEGER PRIMARY KEY NOT NULL,
	two_char_code VARCHAR(2) NOT NULL,
	title VARCHAR(255) NOT NULL,
	timezones TEXT NOT NULL
);

CREATE TABLE event_information (
	id INTEGER PRIMARY KEY NOT NULL,
	slug VARCHAR(255),
	summary VARCHAR(255),
	description TEXT,
	url TEXT,
	country_id INTEGER NOT NULL,
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