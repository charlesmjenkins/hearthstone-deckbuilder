-- addcard.php
------------------------------------------------------------------------------------------------
SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did] AND caid = '[caid]';

SELECT elite 
FROM card 
WHERE caid = '[caid]';

SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did];

INSERT INTO deck_card 
VALUES(DEFAULT, [did], '[caid]');

SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did];

SELECT name, class 
FROM deck 
WHERE user = (SELECT uid FROM user WHERE username = '[username]') AND did = [did];

SELECT card.caid, name, type, rarity, class, cost, COUNT(deck_card.caid) AS count 
FROM card 
INNER JOIN deck_card ON card.caid = deck_card.caid
WHERE did = [did]
GROUP BY name;
------------------------------------------------------------------------------------------------

-- cardcreate.php
------------------------------------------------------------------------------------------------
SELECT caid 
FROM card 
WHERE caid = '[cardID]';

INSERT INTO card 
VALUES ('[cardID]', '[cardName]', 'Custom', '[cardRarity]', [cardCost], [cardAttack], 
		 [cardHealth], [cardDurability], NULL, [cardElite], [cardClass]);
------------------------------------------------------------------------------------------------

-- classcreate.php
------------------------------------------------------------------------------------------------
INSERT INTO class 
VALUES (DEFAULT, '[className]');
------------------------------------------------------------------------------------------------

-- deckbuilder.php
------------------------------------------------------------------------------------------------
SELECT name, class, did 
FROM deck 
WHERE user = (SELECT uid FROM user WHERE username = '[username]');

SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did];

SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` 
FROM card 
WHERE collectible = true AND type <> 'Hero' 
ORDER BY class ASC, cost ASC;
------------------------------------------------------------------------------------------------

-- deckcreate.php
------------------------------------------------------------------------------------------------
INSERT INTO deck 
VALUES (DEFAULT, '[deckName]', [class], (SELECT uid FROM user WHERE username = '[username]'));
------------------------------------------------------------------------------------------------

-- deckdelete.php
------------------------------------------------------------------------------------------------
SELECT username 
FROM user 
WHERE user.uid = (SELECT user FROM deck WHERE did = [did]);

SELECT did 
FROM deck 
WHERE did = [did];

DELETE FROM deck 
WHERE did = [did];
------------------------------------------------------------------------------------------------

-- deckedit.php
------------------------------------------------------------------------------------------------
SELECT username 
FROM user 
WHERE user.uid = (SELECT user FROM deck WHERE did = [deckID]);

SELECT name, class 
FROM deck 
WHERE did = [deckID];

SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [deckID];

SELECT name, class 
FROM deck 
WHERE user = (SELECT uid FROM user WHERE username = '[username]') AND did = [deckID];

SELECT card.caid, name, type, rarity, class, cost, COUNT(deck_card.caid) as count 
FROM card 
INNER JOIN deck_card ON card.caid = deck_card.caid
WHERE did = [deckID]
GROUP BY name;

SELECT name 
FROM class 
WHERE clid = [deckClass];

SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` 
FROM card 
WHERE collectible = true AND type <> 'Hero' AND (class IS NULL OR class = [deckClass]) 
ORDER BY class DESC, cost ASC;
------------------------------------------------------------------------------------------------

-- deckrename.php
------------------------------------------------------------------------------------------------
SELECT username 
FROM user 
WHERE user.uid = (SELECT user FROM deck WHERE did = [did]);

SELECT did 
FROM deck 
WHERE did = [did];

UPDATE deck 
SET name = '[newDeckName]' 
WHERE did = [did];
------------------------------------------------------------------------------------------------

-- removecard.php
------------------------------------------------------------------------------------------------
SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did] AND caid = '[caid]';

DELETE FROM deck_card 
WHERE did = [did] AND caid = '[caid]' LIMIT 1;

SELECT COUNT(caid) AS count 
FROM deck_card 
WHERE did = [did];

SELECT name, class 
FROM deck 
WHERE user = (SELECT uid FROM user WHERE username = '[username]') AND did = [did];

SELECT card.caid, name, type, rarity, class, cost, COUNT(deck_card.caid) AS count 
FROM card 
INNER JOIN deck_card ON card.caid = deck_card.caid
WHERE did = [did]
GROUP BY name;
------------------------------------------------------------------------------------------------

-- session.php
------------------------------------------------------------------------------------------------
SELECT password 
FROM user 
WHERE username = '[username1]';

SELECT password 
FROM user 
WHERE username = '[username2]';

INSERT INTO user 
VALUES(DEFAULT, '[username2]', '[hash]');
------------------------------------------------------------------------------------------------

-- showcustominserts.php
------------------------------------------------------------------------------------------------
SELECT name 
FROM class 
WHERE clid > 11 
ORDER BY clid ASC;

SELECT clid, name 
FROM class 
ORDER BY clid ASC;

SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` 
FROM card 
WHERE `type` = 'Custom' 
ORDER BY class ASC, cost ASC;
------------------------------------------------------------------------------------------------