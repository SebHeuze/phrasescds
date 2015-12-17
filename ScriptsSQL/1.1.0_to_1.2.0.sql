CREATE TABLE IF NOT EXISTS categorie (id_categorie INTEGER PRIMARY KEY AUTOINCREMENT, nom TEXT);
INSERT INTO categorie(id_categorie, nom) VALUES (0,'Non Classée');
INSERT INTO categorie(id_categorie, nom) VALUES (1,'Non Sens');
INSERT INTO categorie(id_categorie, nom) VALUES (2,'Sexuelle');
INSERT INTO categorie(id_categorie, nom) VALUES (3,'Pensées et Réflexions');
INSERT INTO categorie(id_categorie, nom) VALUES (4,'Techno-blagues');
INSERT INTO categorie(id_categorie, nom) VALUES (5,'Retourne à l''école');

ALTER TABLE boulette ADD COLUMN id_categorie INTEGER;
UPDATE boulette SET id_categorie=0;