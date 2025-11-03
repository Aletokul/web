**SRB**

# Tereni Web — Rezervacija sportskih terena

- Jednostavna PHP aplikacija za rezervaciju termina na sportskim terenima.
- Korisnici mogu da prave i otkazuju rezervacije, dok admin ima mogućnost pregleda i upravljanja terminima.

## Tehnologije
- PHP
- MySQL
- HTML, CSS

## Pokretanje projekta
1. Kloniranje repo-a: git clone https://github.com/uname/teren-app.git cd teren-app
2. Kreiranje MySQL baze: CREATE DATABASE teren_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
3. Uvoz dump baze iz **baza/teren_db.sql**
4. Podesavanje koncekcije u **config.ini**
5. Pokretanje pomocu XAMPP(staviti projekat u htdocs folder i pokrenuti Apache i MySQL)

### Bezbednost
- Lozinke se čuvaju pomoću password_hash()
- Login koristi PHP session
- Osnovna zaštita od XSS-a pomoću htmlspecialchars()


## Funkcionalnosti sistema

Aplikacija omogućava jednostavno upravljanje terminima sportskih terena.
Postoje dve uloge: korisnik (User) i administrator (Admin).

### Korisnik
- Korisnik ima mogućnost da se registruje, prijavi, pregleda terene i pravi rezervacije.
- Njegov fokus je korišćenje sistema za sopstvene potrebe — da pronađe slobodan termin i rezerviše ga.
**Detaljno:**
  1. Registracija i prijava
      -Kreira nalog preko register.php.
      - Lozinka se čuva pomoću password_hash().
      - Nakon prijave (login.php), sistem pamti korisnika kroz PHP session.
      - Opcionalno: remember me cookie.
  2. Pregled dostupnih terena
     - Na fields.php vidi sve terene (naziv, lokacija, tip, cena, kapacitet).
     - Može da izabere željeni teren i termin.
  3. Kreiranje rezervacije
     - Stranica reservation.php omogućava izbor datuma i vremena.
     - Sistem proverava da li je termin slobodan i sprečava dupliranje.
  4. Pregled i otkazivanje rezervacija
     - Na myres.php korisnik vidi svoje rezervacije.
     - Može da ih otkaže (ako termin još nije prošao).
  5. Odjava
     - Klikom na “Odjava” (logout.php) briše se session i cookie.

### Administrator
- Administrator ima sve funkcije kao korisnik, uz dodatne mogućnosti upravljanja sistemom.
**Detaljno:**
  1. Pristup admin panelu
     - Nakon prijave, admin vidi link ka manage_fields.php.
     - Samo korisnici sa role = 'admin' mogu da pristupe (proverava se kroz session).
  2. Pregled svih rezervacija
     - Na manage_fields.php vidi sve rezervacije svih korisnika.
  3. Brisanje rezervacija
     - Može da otkaže ili ukloni bilo koju rezervaciju (npr. ako se termin otkaže).
  4. Upravljanje terenima
     - Admin može da dodaje, menja ili briše terene (tabela fields).
  5. Upravljanje korisnicima (opciono)
     - Može u bazi da dodeli korisniku ulogu admina (role='admin').
    
# PMF-UNS 2025

