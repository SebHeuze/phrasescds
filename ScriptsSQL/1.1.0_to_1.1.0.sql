--Passage de ManyToMany (inutile) à OneToMany

ALTER TABLE phrase ADD COLUMN id_boulette INTEGER;

UPDATE phrase SET id_boulette = (SELECT DISTINCT bp.id_boulette FROM boulette_phrase bp WHERE phrase.id_phrase = bp.id_phrase);

DROP TABLE boulette_phrase