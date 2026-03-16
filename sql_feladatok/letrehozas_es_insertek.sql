/* 
Készíts sql táblákat
- tranzakciok
- tranzakcio_elemek
- tranzakcio_fizetesi_modok (bankkártya, készpénz, szépkártya, ajándékutalvány) - egy vásárlást ki lehet fizetni több módon is részleteiben
- jegyek (ebben a táblában mindig annyi darab jegy van amennyi férőhelyes az esemény, minden jegynek saját kódja van, és van altuális státusza, pl.: [eladott, szabad, foglalt, nem eladható]
- esemenyek

Készítsd el a táblák oszlopait, hogy az alábbi lekérdezéseket végre lehessen hajtani rajtuk és írd meg hozzá a lekérdezéseket is SQL utasításban. Legyenek meg a táblákon a szükséges kulcsok és/vagy indexek. 
*/

CREATE TABLE tranzakciok (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tranzakcio_datum DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE tranzakcio_elemek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tranzakcio_id INT NOT NULL,
    tranzakcio_fizetesi_mod_id INT NOT NULL,
    fizetett_osszeg DECIMAL(10, 2) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE tranzakcio_fizetesi_modok (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mod_nev VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE jegyek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ar DECIMAL(10, 2) NOT NULL,
    kod VARCHAR(255) NOT NULL UNIQUE,
    esemeny_id INT NOT NULL,
    tranzakcio_id INT NULL,
    statusz VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE esemenyek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nev VARCHAR(255) NOT NULL,
    ferohelyek_szam INT NOT NULL,
    esemeny_datum DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE INDEX idx_tranzakciok_datum ON tranzakciok(tranzakcio_datum);
CREATE INDEX idx_elemek_tranzakcio_fizetes ON tranzakcio_elemek(tranzakcio_id, tranzakcio_fizetesi_mod_id);
CREATE INDEX idx_jegyek_esemeny_statusz ON jegyek(esemeny_id, statusz);
CREATE INDEX idx_jegyek_tranzakcio ON jegyek(tranzakcio_id);

-- Töltsd fel bizonyos mennyiségű dummy adattal a táblákat. 

-- tranzakcio_fizetesi_modok
INSERT INTO tranzakcio_fizetesi_modok (mod_nev, created_at) VALUES
('Bankkártya',      NOW()),
('Készpénz',        NOW()),
('Szépkártya',      NOW()),
('Ajándékutalvány', NOW());
-- esemenyek (6 db)
INSERT INTO esemenyek (nev, ferohelyek_szam, esemeny_datum, created_at) VALUES
('Rock Koncert Budapest',        5,  '2026-04-10 20:00:00', NOW()),
('Nyári Fesztivál Debrecen',     8,  '2026-06-15 14:00:00', NOW()),
('Színházi Előadás Miskolc',     6,  '2026-05-03 19:00:00', NOW()),
('Jazz Est Pécs',                7,  '2026-04-25 21:00:00', NOW()),
('Klasszikus Zene Győr',         10, '2026-07-08 18:30:00', NOW()),
('Stand-up Comedy Eger',         6,  '2026-05-20 20:00:00', NOW());
-- tranzakciok (12 db) - INSERT BEFORE jegyek due to FK references
INSERT INTO tranzakciok (tranzakcio_datum, created_at) VALUES
('2026-03-01 10:15:00', NOW()),  -- id=1
('2026-03-02 14:30:00', NOW()),  -- id=2
('2026-03-03 09:00:00', NOW()),  -- id=3
('2026-03-04 16:45:00', NOW()),  -- id=4
('2026-03-05 11:20:00', NOW()),  -- id=5
('2026-03-06 18:00:00', NOW()),  -- id=6
('2026-03-07 13:10:00', NOW()),  -- id=7
('2026-03-08 09:45:00', NOW()),  -- id=8
('2026-03-09 15:30:00', NOW()),  -- id=9
('2026-03-10 12:00:00', NOW()),  -- id=10
('2026-03-11 17:20:00', NOW()),  -- id=11
('2026-03-12 10:50:00', NOW());  -- id=12
-- jegyek
-- esemeny_id=1 (Rock Koncert Budapest, 5 jegy, 4500 Ft)
INSERT INTO jegyek (ar, kod, esemeny_id, tranzakcio_id, statusz, created_at) VALUES
(4500.00, 'RKB-0001', 1, 1,    'eladott',      NOW()),
(4500.00, 'RKB-0002', 1, 1,    'eladott',      NOW()),
(4500.00, 'RKB-0003', 1, 2,    'eladott',      NOW()),
(4500.00, 'RKB-0004', 1, NULL, 'foglalt',      NOW()),
(4500.00, 'RKB-0005', 1, NULL, 'szabad',       NOW()),
-- esemeny_id=2 (Nyári Fesztivál Debrecen, 8 jegy, 3200 Ft)
(3200.00, 'NYF-0001', 2, 3,    'eladott',      NOW()),
(3200.00, 'NYF-0002', 2, 3,    'eladott',      NOW()),
(3200.00, 'NYF-0003', 2, 4,    'eladott',      NOW()),
(3200.00, 'NYF-0004', 2, 4,    'eladott',      NOW()),
(3200.00, 'NYF-0005', 2, NULL, 'foglalt',      NOW()),
(3200.00, 'NYF-0006', 2, NULL, 'szabad',       NOW()),
(3200.00, 'NYF-0007', 2, NULL, 'nem eladhato', NOW()),
(3200.00, 'NYF-0008', 2, NULL, 'szabad',       NOW()),
-- esemeny_id=3 (Színházi Előadás Miskolc, 6 jegy, 6000 Ft)
(6000.00, 'SZE-0001', 3, 5,    'eladott',      NOW()),
(6000.00, 'SZE-0002', 3, 5,    'eladott',      NOW()),
(6000.00, 'SZE-0003', 3, 6,    'eladott',      NOW()),
(6000.00, 'SZE-0004', 3, 6,    'eladott',      NOW()),
(6000.00, 'SZE-0005', 3, NULL, 'foglalt',      NOW()),
(6000.00, 'SZE-0006', 3, NULL, 'szabad',       NOW()),
-- esemeny_id=4 (Jazz Est Pécs, 7 jegy, 5500 Ft)
(5500.00, 'JEP-0001', 4, 7,    'eladott',      NOW()),
(5500.00, 'JEP-0002', 4, 7,    'eladott',      NOW()),
(5500.00, 'JEP-0003', 4, 7,    'eladott',      NOW()),
(5500.00, 'JEP-0004', 4, 8,    'eladott',      NOW()),
(5500.00, 'JEP-0005', 4, NULL, 'szabad',       NOW()),
(5500.00, 'JEP-0006', 4, NULL, 'szabad',       NOW()),
(5500.00, 'JEP-0007', 4, NULL, 'nem eladhato', NOW()),
-- esemeny_id=5 (Klasszikus Zene Győr, 10 jegy, 7000 Ft)
(7000.00, 'KZG-0001', 5, 9,    'eladott',      NOW()),
(7000.00, 'KZG-0002', 5, 9,    'eladott',      NOW()),
(7000.00, 'KZG-0003', 5, 10,   'eladott',      NOW()),
(7000.00, 'KZG-0004', 5, 10,   'eladott',      NOW()),
(7000.00, 'KZG-0005', 5, 10,   'eladott',      NOW()),
(7000.00, 'KZG-0006', 5, NULL, 'foglalt',      NOW()),
(7000.00, 'KZG-0007', 5, NULL, 'foglalt',      NOW()),
(7000.00, 'KZG-0008', 5, NULL, 'szabad',       NOW()),
(7000.00, 'KZG-0009', 5, NULL, 'szabad',       NOW()),
(7000.00, 'KZG-0010', 5, NULL, 'szabad',       NOW()),
-- esemeny_id=6 (Stand-up Comedy Eger, 6 jegy, 4000 Ft)
(4000.00, 'SCE-0001', 6, 11,   'eladott',      NOW()),
(4000.00, 'SCE-0002', 6, 11,   'eladott',      NOW()),
(4000.00, 'SCE-0003', 6, 12,   'eladott',      NOW()),
(4000.00, 'SCE-0004', 6, 12,   'eladott',      NOW()),
(4000.00, 'SCE-0005', 6, NULL, 'szabad',       NOW()),
(4000.00, 'SCE-0006', 6, NULL, 'nem eladhato', NOW());
-- tranzakcio_elemek
-- tr.1: 2x RKB = 9000 Ft → bankkártya + szépkártya
INSERT INTO tranzakcio_elemek (tranzakcio_id, tranzakcio_fizetesi_mod_id, fizetett_osszeg, created_at) VALUES
(1,  1, 5000.00, NOW()),   -- bankkártya
(1,  3, 4000.00, NOW()),   -- szépkártya
-- tr.2: 1x RKB = 4500 Ft → teljes készpénz
(2,  2, 4500.00, NOW()),   -- készpénz
-- tr.3: 2x NYF = 6400 Ft → bankkártya + ajándékutalvány
(3,  1, 4000.00, NOW()),   -- bankkártya
(3,  4, 2400.00, NOW()),   -- ajándékutalvány
-- tr.4: 2x NYF = 6400 Ft → szépkártya + készpénz
(4,  3, 5000.00, NOW()),   -- szépkártya
(4,  2, 1400.00, NOW()),   -- készpénz
-- tr.5: 2x SZE = 12000 Ft → bankkártya
(5,  1, 12000.00, NOW()),  -- bankkártya
-- tr.6: 2x SZE = 12000 Ft → szépkártya + ajándékutalvány
(6,  3, 8000.00, NOW()),   -- szépkártya
(6,  4, 4000.00, NOW()),   -- ajándékutalvány
-- tr.7: 3x JEP = 16500 Ft → bankkártya + készpénz
(7,  1, 10000.00, NOW()),  -- bankkártya
(7,  2,  6500.00, NOW()),  -- készpénz
-- tr.8: 1x JEP = 5500 Ft → szépkártya
(8,  3,  5500.00, NOW()),  -- szépkártya
-- tr.9: 2x KZG = 14000 Ft → bankkártya
(9,  1, 14000.00, NOW()),  -- bankkártya
-- tr.10: 3x KZG = 21000 Ft → bankkártya + ajándékutalvány + készpénz
(10, 1, 10000.00, NOW()),  -- bankkártya
(10, 4,  8000.00, NOW()),  -- ajándékutalvány
(10, 2,  3000.00, NOW()),  -- készpénz
-- tr.11: 2x SCE = 8000 Ft → szépkártya + bankkártya
(11, 3,  5000.00, NOW()),  -- szépkártya
(11, 1,  3000.00, NOW()),  -- bankkártya
-- tr.12: 2x SCE = 8000 Ft → teljes készpénz
(12, 2,  8000.00, NOW());  -- készpénz


-- =============================================
-- Plussz múltbéli események: 2025 + 2026 január
-- esemeny id: 7–10, tranzakcio id: 13–22
-- =============================================

-- esemenyek (4 db: 2x 2025, 2x 2026-01)
INSERT INTO esemenyek (nev, ferohelyek_szam, esemeny_datum, created_at) VALUES
('Őszi Jazznapok Sopron',       8,  '2025-10-18 19:00:00', '2025-09-01 10:00:00'),  -- id=7
('Karácsonyi Koncert Pécs',     7,  '2025-12-21 18:00:00', '2025-11-01 09:00:00'),  -- id=8
('Újévi Gála Budapest',         10, '2026-01-01 22:00:00', '2025-12-01 11:00:00'),  -- id=9
('Téli Fesztivál Debrecen',     6,  '2026-01-25 17:00:00', '2025-12-15 14:00:00');  -- id=10

-- tranzakciok (10 db: vásárlások a 2025-ös és 2026-01-es eseményekhez)
INSERT INTO tranzakciok (tranzakcio_datum, created_at) VALUES
('2025-09-20 10:00:00', '2025-09-20 10:00:00'),  -- id=13
('2025-10-01 14:15:00', '2025-10-01 14:15:00'),  -- id=14
('2025-10-05 09:30:00', '2025-10-05 09:30:00'),  -- id=15
('2025-11-10 16:00:00', '2025-11-10 16:00:00'),  -- id=16
('2025-11-25 11:45:00', '2025-11-25 11:45:00'),  -- id=17
('2025-12-03 13:20:00', '2025-12-03 13:20:00'),  -- id=18
('2025-12-10 17:00:00', '2025-12-10 17:00:00'),  -- id=19
('2025-12-15 10:30:00', '2025-12-15 10:30:00'),  -- id=20
('2026-01-05 12:00:00', '2026-01-05 12:00:00'),  -- id=21
('2026-01-12 15:45:00', '2026-01-12 15:45:00');  -- id=22

-- jegyek
-- esemeny_id=7 (Őszi Jazznapok Sopron, 8 jegy, 5000 Ft)
INSERT INTO jegyek (ar, kod, esemeny_id, tranzakcio_id, statusz, created_at) VALUES
(5000.00, 'OJS-0001', 7, 13,   'eladott',      '2025-09-20 10:00:00'),
(5000.00, 'OJS-0002', 7, 13,   'eladott',      '2025-09-20 10:00:00'),
(5000.00, 'OJS-0003', 7, 13,   'eladott',      '2025-09-20 10:00:00'),
(5000.00, 'OJS-0004', 7, 14,   'eladott',      '2025-10-01 14:15:00'),
(5000.00, 'OJS-0005', 7, 14,   'eladott',      '2025-10-01 14:15:00'),
(5000.00, 'OJS-0006', 7, 15,   'eladott',      '2025-10-05 09:30:00'),
(5000.00, 'OJS-0007', 7, NULL, 'nem eladhato', '2025-09-01 10:00:00'),
(5000.00, 'OJS-0008', 7, NULL, 'szabad',       '2025-09-01 10:00:00'),

-- esemeny_id=8 (Karácsonyi Koncert Pécs, 7 jegy, 6500 Ft)
(6500.00, 'KKP-0001', 8, 16,   'eladott',      '2025-11-10 16:00:00'),
(6500.00, 'KKP-0002', 8, 16,   'eladott',      '2025-11-10 16:00:00'),
(6500.00, 'KKP-0003', 8, 17,   'eladott',      '2025-11-25 11:45:00'),
(6500.00, 'KKP-0004', 8, 17,   'eladott',      '2025-11-25 11:45:00'),
(6500.00, 'KKP-0005', 8, 17,   'eladott',      '2025-11-25 11:45:00'),
(6500.00, 'KKP-0006', 8, NULL, 'foglalt',      '2025-11-01 09:00:00'),
(6500.00, 'KKP-0007', 8, NULL, 'szabad',       '2025-11-01 09:00:00'),

-- esemeny_id=9 (Újévi Gála Budapest, 10 jegy, 9000 Ft)
(9000.00, 'UGB-0001', 9, 18,   'eladott',      '2025-12-03 13:20:00'),
(9000.00, 'UGB-0002', 9, 18,   'eladott',      '2025-12-03 13:20:00'),
(9000.00, 'UGB-0003', 9, 19,   'eladott',      '2025-12-10 17:00:00'),
(9000.00, 'UGB-0004', 9, 19,   'eladott',      '2025-12-10 17:00:00'),
(9000.00, 'UGB-0005', 9, 19,   'eladott',      '2025-12-10 17:00:00'),
(9000.00, 'UGB-0006', 9, 20,   'eladott',      '2025-12-15 10:30:00'),
(9000.00, 'UGB-0007', 9, 20,   'eladott',      '2025-12-15 10:30:00'),
(9000.00, 'UGB-0008', 9, NULL, 'foglalt',      '2025-12-01 11:00:00'),
(9000.00, 'UGB-0009', 9, NULL, 'szabad',       '2025-12-01 11:00:00'),
(9000.00, 'UGB-0010', 9, NULL, 'nem eladhato', '2025-12-01 11:00:00'),

-- esemeny_id=10 (Téli Fesztivál Debrecen, 6 jegy, 4800 Ft)
(4800.00, 'TFD-0001', 10, 21,   'eladott',     '2026-01-05 12:00:00'),
(4800.00, 'TFD-0002', 10, 21,   'eladott',     '2026-01-05 12:00:00'),
(4800.00, 'TFD-0003', 10, 22,   'eladott',     '2026-01-12 15:45:00'),
(4800.00, 'TFD-0004', 10, NULL, 'foglalt',     '2025-12-15 14:00:00'),
(4800.00, 'TFD-0005', 10, NULL, 'szabad',      '2025-12-15 14:00:00'),
(4800.00, 'TFD-0006', 10, NULL, 'szabad',      '2025-12-15 14:00:00');

-- tranzakcio_elemek
INSERT INTO tranzakcio_elemek (tranzakcio_id, tranzakcio_fizetesi_mod_id, fizetett_osszeg, created_at) VALUES
-- tr.13: 3x OJS = 15000 Ft → bankkártya + szépkártya
(13, 1, 10000.00, '2025-09-20 10:00:00'),  -- bankkártya
(13, 3,  5000.00, '2025-09-20 10:00:00'),  -- szépkártya
-- tr.14: 2x OJS = 10000 Ft → teljes bankkártya
(14, 1, 10000.00, '2025-10-01 14:15:00'),  -- bankkártya
-- tr.15: 1x OJS = 5000 Ft → készpénz + ajándékutalvány
(15, 2,  3000.00, '2025-10-05 09:30:00'),  -- készpénz
(15, 4,  2000.00, '2025-10-05 09:30:00'),  -- ajándékutalvány
-- tr.16: 2x KKP = 13000 Ft → szépkártya + bankkártya
(16, 3,  8000.00, '2025-11-10 16:00:00'),  -- szépkártya
(16, 1,  5000.00, '2025-11-10 16:00:00'),  -- bankkártya
-- tr.17: 3x KKP = 19500 Ft → bankkártya + ajándékutalvány
(17, 1, 12000.00, '2025-11-25 11:45:00'),  -- bankkártya
(17, 4,  7500.00, '2025-11-25 11:45:00'),  -- ajándékutalvány
-- tr.18: 2x UGB = 18000 Ft → teljes bankkártya
(18, 1, 18000.00, '2025-12-03 13:20:00'),  -- bankkártya
-- tr.19: 3x UGB = 27000 Ft → szépkártya + bankkártya + készpénz
(19, 3, 15000.00, '2025-12-10 17:00:00'),  -- szépkártya
(19, 1,  8000.00, '2025-12-10 17:00:00'),  -- bankkártya
(19, 2,  4000.00, '2025-12-10 17:00:00'),  -- készpénz
-- tr.20: 2x UGB = 18000 Ft → ajándékutalvány + készpénz
(20, 4, 10000.00, '2025-12-15 10:30:00'),  -- ajándékutalvány
(20, 2,  8000.00, '2025-12-15 10:30:00'),  -- készpénz
-- tr.21: 2x TFD = 9600 Ft → teljes szépkártya
(21, 3,  9600.00, '2026-01-05 12:00:00'),  -- szépkártya
-- tr.22: 1x TFD = 4800 Ft → bankkártya + készpénz
(22, 1,  3000.00, '2026-01-12 15:45:00'),  -- bankkártya
(22, 2,  1800.00, '2026-01-12 15:45:00');  -- készpénz