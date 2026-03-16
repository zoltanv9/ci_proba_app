-- 1. Hány darab sikeres jegyeladás volt 2026 első két hónapjában, és ezeket milyen módon fizették ki?

-- Sikeresen eladott jegyek darabszáma:
SELECT 
    COUNT(j.id) AS eladott_darab
FROM tranzakciok tr
JOIN jegyek j ON j.tranzakcio_id = tr.id
WHERE j.statusz = 'eladott'
  AND tr.tranzakcio_datum >= '2026-01-01' 
  AND tr.tranzakcio_datum < '2026-03-01';

-- Használt fizetési módok:
SELECT 
    tfm.mod_nev, 
    COUNT(*) AS tranzakcio_darab
FROM tranzakciok tr
JOIN tranzakcio_elemek te ON te.tranzakcio_id = tr.id
JOIN tranzakcio_fizetesi_modok tfm ON tfm.id = te.tranzakcio_fizetesi_mod_id
  AND tr.tranzakcio_datum >= '2026-01-01' 
  AND tr.tranzakcio_datum < '2026-03-01'
GROUP BY tfm.id;

-- Megjegyzés: Azért nem lehet egy lekérdezésben megadni, mert a feladat szerint részleteiben is lehet fizetni.
-- Megértésem szerint ilyenkor egy jegyhez több fizetési mód is tartozhat, vagy egy fizetési mód több jegyet is lefedhet.
-- Tehát pl: ha három jegyet fizetek kétféle fizetési móddal, akkor nem lehet pontosan megmondani, hogy melyik fizetési mód melyik jegyhez tartozik.

-- 2. Mennyi az egyes események pillanatnyi kihasználtsága %-ban?
SELECT 
    e.nev, 
    ROUND(IF(e.ferohelyek_szam = 0, 0, (COUNT(j.id) / e.ferohelyek_szam) * 100), 2) AS kihasznaltsag_szazalek
FROM esemenyek e
LEFT JOIN jegyek j ON j.esemeny_id = e.id 
    AND j.statusz IN ('eladott', 'foglalt')
GROUP BY e.id, e.nev, e.ferohelyek_szam;

-- 3. Eladások darabszáma és bruttó bevétel napi bontásban fizetési módtól függetlenül 2026-01-01-óta
SELECT 
    napok.datum,
    IFNULL(jegy_statisztika.napi_jegy_db, 0) AS eladott_darab,
    IFNULL(penz_statisztika.napi_bevetel, 0) AS brutto_bevetel
FROM (
    SELECT DISTINCT DATE(tranzakcio_datum) AS datum 
    FROM tranzakciok 
    WHERE tranzakcio_datum >= '2026-01-01'
) AS napok
LEFT JOIN (
    SELECT DATE(tr2.tranzakcio_datum) AS datum, COUNT(j.id) AS napi_jegy_db
    FROM tranzakciok tr2
    JOIN jegyek j ON j.tranzakcio_id = tr2.id
    WHERE j.statusz = 'eladott'
    GROUP BY DATE(tr2.tranzakcio_datum)
) AS jegy_statisztika ON napok.datum = jegy_statisztika.datum
LEFT JOIN (
    SELECT DATE(tr3.tranzakcio_datum) AS datum, SUM(te.fizetett_osszeg) AS napi_bevetel
    FROM tranzakciok tr3
    JOIN tranzakcio_elemek te ON te.tranzakcio_id = tr3.id
    GROUP BY DATE(tr3.tranzakcio_datum)
) AS penz_statisztika ON napok.datum = penz_statisztika.datum
ORDER BY napok.datum;

-- 4. Melyik az a 3 esemény amelyekre a legtöbb jegyet adták el
SELECT 
    e.nev, 
    COUNT(j.id) AS eladott_darab
FROM esemenyek e
JOIN jegyek j ON j.esemeny_id = e.id
WHERE j.statusz = 'eladott'
    AND j.tranzakcio_id IS NOT NULL
GROUP BY e.id
ORDER BY eladott_darab DESC
LIMIT 3;
 