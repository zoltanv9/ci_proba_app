# CodeIgniter 4 – Próba Applikáció

## Telepítés

### Rendszerkövetelmények

- PHP 8.2 vagy újabb, a következő kiterjesztésekkel:
  - `intl`
  - `mbstring`
  - `mysqlnd` (MySQL használatához)
  - `json` (alapértelmezetten engedélyezett)
- [Composer](https://getcomposer.org/)
- MySQL 5.7+ vagy MariaDB

### Lépések

**1. Függőségek telepítése**

```bash
composer install
```

**2. Környezeti konfiguráció**

Másold az `.env` fájlt és állítsd be az értékeket:

```bash
cp env .env
```

Fontos beállítások az `.env` fájlban:

```ini
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = ci_proba_app
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

# Admin felhasználó – a seeder ezt az értéket használja, szabadon megadható
ADMIN_USERNAME = <felhasználónév>
ADMIN_PASSWORD = <jelszó>
```

**3. Adatbázis létrehozása és migrációk futtatása**

Hozd létre az adatbázist a MySQL-ben, majd futtasd a migrációkat és a seedereket:

```bash
php spark migrate
php spark db:seed UserSeeder
php spark db:seed MenuSeeder
```

## Bejelentkezés

Az alapértelmezett admin felhasználó az `.env`-ben beállított `ADMIN_USERNAME` és `ADMIN_PASSWORD` értékekkel jelentkezhet be.

---

## SQL Feladatok

Az `sql_feladatok/` mappában találhatók az SQL gyakorló feladatok:

| Fájl | Leírás |
|---|---|
| `letrehozas_es_insertek.sql` | Táblák létrehozása (`CREATE TABLE`) és feltöltése dummy adatokkal (`INSERT`) |
| `lekerdezesek.sql` | Lekérdezési feladatok és azok SQL megoldásai |


---

