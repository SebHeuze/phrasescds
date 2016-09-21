ALTER TABLE boulette ADD COLUMN archive INTEGER DEFAULT 0;
UPDATE boulette SET archive = 0;