<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Irányítószám REST API

Laravelben készült REST API megyék és települések kezelésére.

## Funkciók

- Megyék és települések listázása, részletes lekérdezése.
- Név szerinti keresés.
- Települések szűrése megye alapján.
- Hozzáadás, módosítás és törlés.
- Tokenes bejelentkezés Laravel Sanctummal.
- Kijelentkezéskor az aktuális token törlése.

Az adatok lekérdezése nyilvános. Az adatmódosításokhoz
érvényes Bearer token szükséges.

## Telepítés

Szükséges: PHP 8.2 vagy újabb, Composer és MySQL.

A projekt letöltése után:

```bash
composer install
```

Másold le az `.env.example` fájlt `.env` néven.
Hozz létre egy `iranyitoszam_api` nevű MySQL-adatbázist,
majd a `.env` fájlban állítsd be a kapcsolatot:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iranyitoszam_api
DB_USERNAME=root
DB_PASSWORD=
```

A felhasználónevet és jelszót a saját MySQL-beállításaidhoz igazítsd.

Ezután futtasd:

```bash
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
php artisan serve
```

Alapcím: http://127.0.0.1:8000

A seeder Pest és Fejér megyét, valamint Vác, Szentendre,
Székesfehérvár és Dunaújváros településeket hozza létre.

## Bejelentkezés

A helyi tesztfelhasználó:

- E-mail: teszt@example.com
- Jelszó: Teszt123456!

POST kérés az `/api/login` végpontra:

```json
{
    "email": "teszt@example.com",
    "password": "Teszt123456!"
}
```

A válaszban kapott tokent a védett kérésekhez így kell megadni:

```http
Authorization: Bearer IDE_KERUL_A_TOKEN
```

## Végpontok

| Metódus | Végpont | Feladat | Token szükséges |
| --- | --- | --- | --- |
| POST | /api/login | Bejelentkezés, token kérése | Nem |
| POST | /api/logout | Aktuális token törlése | Igen |
| GET | /api/counties | Megyék listázása és keresése | Nem |
| GET | /api/counties/{county} | Egy megye és települései | Nem |
| POST | /api/counties | Megye hozzáadása | Igen |
| PUT | /api/counties/{county} | Megye módosítása | Igen |
| DELETE | /api/counties/{county} | Üres megye törlése | Igen |
| GET | /api/cities | Települések listázása, keresése, szűrése | Nem |
| GET | /api/cities/{city} | Egy település és megyéje | Nem |
| POST | /api/cities | Település hozzáadása | Igen |
| PUT | /api/cities/{city} | Település módosítása | Igen |
| DELETE | /api/cities/{city} | Település törlése | Igen |

A kapcsos zárójelben szereplő részek helyére
a rekord azonosítóját kell írni.

## Keresés és szűrés

Megye keresése:

```text
GET /api/counties?search=Pest
```

Település keresése:

```text
GET /api/cities?search=Vác
```

Szűrés megyeazonosító alapján:

```text
GET /api/cities?county_id=1
```

Keresés és szűrés együtt:

```text
GET /api/cities?search=Vác&county_id=1
```

Az azonosítókat a saját adatbázis rekordjai alapján kell megadni.

## Adatmódosítás

Megye hozzáadásakor és módosításakor a `name` mező kötelező.

Település hozzáadásakor és módosításakor a `name` és
a létező megyére mutató `county_id` mező is kötelező.

Példa település hozzáadására:

```json
{
    "name": "Eger",
    "county_id": 3
}
```

A példa akkor használható, ha a 3-as azonosítójú megye létezik.
Településeket tartalmazó megye nem törölhető.

## Válaszkódok

| Kód | Jelentés |
| --- | --- |
| 200 | Sikeres kérés |
| 201 | Új rekord létrehozva |
| 401 | Hibás bejelentkezés vagy érvénytelen/hiányzó token |
| 404 | A rekord nem található |
| 409 | A megyéhez települések tartoznak |
| 422 | Hibás vagy hiányzó bemeneti adat |

## Tesztelés

A végpontokat Brunóval teszteltem.
A mentett kérések a projekt `bruno` mappájában találhatók.

JSON küldésekor használt fejlécek:

```http
Accept: application/json
Content-Type: application/json
```

A tokennel védett kérésekhez bejelentkezés után
saját tokent kell beállítani a Bruno Auth részében.

## API-dokumentáció

Az apiDoc dokumentáció megnyitható az alkalmazás futása közben:

http://127.0.0.1:8000/docs/index.html

Újragenerálás a projekt könyvtárában:

```bash
npm ci
npx apidoc -i app/Http/Controllers -o public/docs -c apidoc.json
```