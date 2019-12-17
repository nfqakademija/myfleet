![](http://myfleet.projektai.nfqakademija.lt/my-fleet-logo.png)

# Intro

MyFleet - tai sistema, skirta patogiam įmonės autoparko informacijos valdymui bei padedanti automatizuoti procesus.

Pagrindinės funkcijos:
* įmonės automobilių sąrašo valdymas;
* su automobiliu susijusių įvykių, užduočių ir išlaidų registravimas;
* automobilio buvimo vietos stebėjimas realiu laiku;
* duomenų surinkimas iš trečių šalių;
* informavimas apie kritinius įvykius.

Projektas pasiekiamas adresu: [https://myfleet.projektai.nfqakademija.lt/](https://myfleet.projektai.nfqakademija.lt).

# Paleidimo instrukcija

Pirminė instaliacija:
```bash
scripts/start.sh
scripts/install-first.sh
```
Prisijungimas prie PHP konteinerio:
```bash
scripts/backend.sh
```
PHP konteineryje paleidžiamos komandos:
```bash
bin/console doctrine:data:drop --force
bin/console doctrine:data:create
bin/console doctrine:schema:create
php -d memory_limit=4G  bin/console doctrine:fixtures:load -q
bin/console data:import:vehicle
bin/console data:import:registry
```
Projektas pasiekiamas adresu `http://127.0.0.1:8000/`.

### Demo duomenys

Vartotojai:

* administratorius@imone.lt (slaptažodis: `password`);
* vadybininkas@imone.lt (slaptažodis: `password`).

Geografinių duomenų šaltinis: Lietuvos automobilių kelių direkcija prie Susisiekimo ministerijos.

### Padėka

Dėkajame Linui už AWS IoT button.