-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Account (username, password) VALUES ('Pekka', 'Sala1');
INSERT INTO Account (username, password, banned) VALUES ('Estetty', 'Salasana', true);
INSERT INTO Movie (creator_id, name, year) VALUES (1, 'Logan', 2017);
INSERT INTO Movie(creator_id, name, year, description) VALUES (1, 'Kong: Skull Island', 2017, 'A team of scientists explore an uncharted island in the Pacific, venturing into the domain of the mighty Kong, and must fight to escape a primal Eden.
');
INSERT INTO Message (user_id, movie_id, content, posted_at) VALUES (1, 1, 'Testiviesti', CURRENT_TIMESTAMP);
INSERT INTO Message (user_id, movie_id, content, posted_at) VALUES (1, 1, 'Hyvä leffa', CURRENT_TIMESTAMP);
INSERT INTO Message (user_id, movie_id, content, posted_at) VALUES (1, 2, '5/5', CURRENT_TIMESTAMP);
INSERT INTO Genre (name) VALUES ('Toiminta');
INSERT INTO Genre (name) VALUES ('Draama');
INSERT INTO MovieGenre (movie_id, genre_id) VALUES (1, 1);
INSERT INTO MovieGenre (movie_id, genre_id) VALUES (2, 1);
INSERT INTO MovieGenre (movie_id, genre_id) VALUES (1, 2)

