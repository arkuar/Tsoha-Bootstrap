-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Account(
    id SERIAL PRIMARY KEY,
    username varchar(15) NOT NULL,
    password varchar(50) NOT NULL,
    administrator boolean DEFAULT FALSE,
    banned boolean DEFAULT FALSE
);

CREATE TABLE Movie(
    id SERIAL PRIMARY KEY,
    creator_id INTEGER REFERENCES Account(id),
    name varchar(250) NOT NULL,
    year INTEGER NOT NULL,
    description text
);

CREATE TABLE Message(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES Account(id),
    movie_id INTEGER REFERENCES Movie(id),
    content text NOT NULL,
    posted_at timestamp NOT NULL
);

CREATE TABLE Genre(
    id SERIAL PRIMARY KEY,
    name varchar(10) NOT NULL,
    description text
);

CREATE TABLE MovieGenre(
    movie_id INTEGER REFERENCES Movie(id),
    genre_id INTEGER REFERENCES Genre(id)
);