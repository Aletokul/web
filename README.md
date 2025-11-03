**SRB**

# Tereni Web — Rezervacija sportskih terena

- Jednostavna PHP aplikacija za rezervaciju termina na sportskim terenima
- Korisnici mogu da prave i otkazuju rezervacije, dok admin ima mogućnost pregleda i upravljanja terminima

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
- Aplikacija omogućava jednostavno upravljanje terminima sportskih terena
- Postoje dve uloge: korisnik (User) i administrator (Admin)

### Korisnik
- Korisnik ima mogućnost da se registruje, prijavi, pregleda terene i pravi rezervacije
- Njegov fokus je korišćenje sistema za sopstvene potrebe — da pronađe slobodan termin i rezerviše ga
**Detaljno:**
  1. **Registracija i prijava**
      -Kreira nalog preko register.php
      - Lozinka se čuva pomoću password_hash()
      - Nakon prijave (login.php), sistem pamti korisnika kroz PHP session
      - Opcionalno: remember me cookie
  2. **Pregled dostupnih terena**
     - Na fields.php vidi sve terene (naziv, lokacija, tip, cena, kapacitet)
     - Može da izabere željeni teren i termin
  3. **Kreiranje rezervacije**
     - Stranica reservation.php omogućava izbor datuma i vremena
     - Sistem proverava da li je termin slobodan i sprečava dupliranje
  4. **Pregled i otkazivanje rezervacija**
     - Na myres.php korisnik vidi svoje rezervacije
     - Može da ih otkaže (ako termin još nije prošao)
  5. **Odjava**
     - Klikom na “Odjava” (logout.php) briše se session i cookie

### Administrator
- Administrator ima sve funkcije kao korisnik, uz dodatne mogućnosti upravljanja sistemom
**Detaljno:**
  1. **Pristup admin panelu**
     - Nakon prijave, admin vidi link ka manage_fields.php
     - Samo korisnici sa role = 'admin' mogu da pristupe (proverava se kroz session)
  2. **Pregled svih rezervacija**
     - Na manage_fields.php vidi sve rezervacije svih korisnika
  3. **Brisanje rezervacija**
     - Može da otkaže ili ukloni bilo koju rezervaciju (npr. ako se termin otkaže)
  4. **Upravljanje terenima**
     - Admin može da dodaje, menja ili briše terene (tabela fields)
    
# Prirodno-matematički fakultet Univerziteta u Novom Sadu 2025



**ENG**

# Tereni Web — Sports Field Reservation

- A simple PHP web application for booking time slots on sports fields
- Users can create and cancel reservations, while the admin can view and manage all bookings

## Technologies
- PHP
- MySQL
- HTML, CSS

## Project Setup
1. Clone the repository: git clone https://github.com/uname/teren-app.git cd teren-app
2. Create the MySQL database: CREATE DATABASE teren_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
3. Import the SQL dump from **baza/teren_db.sql**
4. Configure the connection in **config.ini**
5. Run the project using XAMPP(Place the project inside the htdocs folder and Start Apache and MySQL)

### Security
- Passwords are stored using password_hash()
- Login system uses PHP session
- Basic XSS protection implemented with htmlspecialchars()


## System Features
- The application allows easy management of sports field bookings
- There are two user roles: User and Administrator

### User
- Users can register, log in, view available fields, and make reservations
- Their focus is to find available time slots and book them easily
**Details:**
  1. **Registration and Login**
      -Register via register.php
      - Passwords are secured with password_hash()
      - After logging in (login.php), the system stores user data through PHP session
      - Optional: “remember me” cookie
  2. **Viewing Available Fields**
     - On fields.php, users can view all fields (name, location, type, price, capacity)
     - They can select a preferred field and time slot
  3. **Creating a Reservation**
     - The reservation.php page allows users to pick a date and time
     - The system checks for availability and prevents duplicate bookings
  4. **Viewing and Canceling Reservations**
     - On myres.php, users can see their own reservations
     - They can cancel a reservation (if the time has not passed yet)
  5. **Logout**
     - Clicking “Logout” (logout.php) clears the session and cookies

### Admin
- The administrator has all user permissions plus additional management tools.
**Details:**
  1. **Accessing the Admin Panel**
     - After logging in, the admin can access manage_fields.php
     - Only users with role = 'admin' can access this page (checked via session)
  2. **Viewing All Reservations**
     - On manage_fields.php, the admin can view all users’ reservations
  3. **Deleting Reservations**
     - The admin can cancel or remove any reservation (e.g., if a field becomes unavailable)
  4. **Managing Fields**
     - The admin can add, edit, or delete fields (from the fields table)
    
# The Faculty of Sciences at the University of Novi Sad 2025




