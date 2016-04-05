ALTER TABLE boulette ADD COLUMN archive INTEGER;
UPDATE boulette SET archive = 0;