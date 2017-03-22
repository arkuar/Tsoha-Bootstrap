-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Account (username, password) VALUES ('Pekka', 'Sala1');
INSERT INTO Movie (creator_id, name, year) VALUES (1, 'Logan', 2017);
INSERT INTO Message (user_id, movie_id, content, posted_at) VALUES (1, 1, 'Testiviesti', CURRENT_TIMESTAMP);
INSERT INTO Genre (name) VALUES ('Toiminta');
INSERT INTO MovieGenre (movie_id, genre_id) VALUES (1, 1);

