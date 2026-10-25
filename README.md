# MetroSheba — Online Metro Ticketing Platform

A multi-tier web application for Dhaka MRT Line-6 ticketing, built with
**HTML, CSS, JavaScript (DOM + AJAX), PHP and MySQL** in an MVC layout.

Passengers register with OTP verification, buy tickets at a distance-based
fare, manage their purchase history and edit their profile. An admin manages
customers and reviews ticket sales.

---

## 1. Requirements

- PHP 7.4 or newer (tested on PHP 8.3) with the `mysqli` extension
- MySQL 5.7+ / MariaDB 10+
- XAMPP, WAMP, Laragon or any Apache + PHP + MySQL stack

## 2. Setup

**Step 1 — put the project in your web root**

```
C:\xampp\htdocs\Project-Metrosheba-group2     (Windows / XAMPP)
/opt/lampp/htdocs/Project-Metrosheba-group2   (Linux)
```

**Step 2 — create the database**

Import `database/metrosheba.sql`. Either:

- phpMyAdmin → *Import* → choose `database/metrosheba.sql` → *Go*, or
- command line:
  ```bash
  mysql -u root -p < database/metrosheba.sql
  ```

This creates the `metrosheba` database, both tables and some demo data.

**Step 3 — check the connection settings**

`models/db.php` defaults to the usual XAMPP values:

```php
$host = "localhost";  $user = "root";  $pass = "";  $dbname = "metrosheba";
```

Change them if your MySQL uses a different user or password.

**Step 4 — open the app**

```
http://localhost/Project-Metrosheba-group2/views/index.php
```

## 3. Demo logins

| Role      | Email                 | Password       |
|-----------|-----------------------|----------------|
| Admin     | admin@gmail.com       | admin          |
| Passenger | passenger@gmail.com   | passenger123   |

New passengers can register themselves. The OTP is not sent by SMS in this
prototype — it is shown on screen after pressing **Send OTP**.

## 4. Project structure

```
Project-Metrosheba-group2/
├── assets/
│   ├── css/          base.css, auth.css, dashboard.css, profile.css
│   ├── js/           shared helpers, validators, one file per page
│   ├── uploads/      profile pictures uploaded at runtime
│   └── ...           images and fonts
├── controllers/      request handling, validation, AJAX endpoints (*_api.php)
├── models/           database access, one file per feature
├── views/            pages (HTML only — no CSS or JS written inline)
└── database/
    └── metrosheba.sql
```

The three tiers stay separate: **views** render, **controllers** validate and
decide, **models** talk to MySQL. No page writes SQL directly, and no model
prints HTML.

## 5. Features

### Passenger
- Registration with OTP verification (AJAX), validated in both JS and PHP
- Login with a signed "Remember me" cookie
- Ticket purchase without a page reload; the fare recalculates as the
  stations and quantity change, and the chosen leg lights up on the route map
- A printable receipt built in the DOM after each purchase
- **My Tickets**: AJAX history with live search, status filter, column
  sorting and ticket cancellation
- Profile view, profile picture upload (AJAX) and profile editing

### Admin
- Dashboard with customer and revenue statistics
- Customer management: search, inline edit and delete, all over AJAX
- Ticket sales page with server-side search, column sorting, a busiest-routes
  bar chart and CSV export generated in the browser

## 6. Where each technology is used

| Technology | Where |
|---|---|
| **HTML** | `views/*.php` — pages, forms, tables |
| **CSS** | `assets/css/*.css` — layout, responsive design, theming |
| **JavaScript** | `assets/js/*.js` — validation, interactivity |
| **DOM manipulation** | Tables, receipts, the route map and the route chart are built with `createElement` / `textContent` in `mytickets.js`, `admin-users.js`, `admin-tickets.js`, `passenger-dashboard.js` |
| **AJAX** | `XMLHttpRequest` in `assets/js/metrosheba.js`, calling `controllers/*_api.php` and exchanging JSON |
| **PHP** | `controllers/` and `models/` — validation, sessions, business rules |
| **MySQL** | `models/*db.php` — all queries use prepared statements |

## 7. Fare rule

Fare rises by **10 BDT per station** with a **minimum of 20 BDT**, so
Uttara North → Motijheel (14 stations) costs 140 BDT per ticket. The rule
lives in `models/stations.php` and is served to the browser by
`controllers/stations_api.php`, so the price shown always matches the price
charged. The server recalculates the total on purchase and ignores any
amount posted by the browser.

## 8. Security notes

- Every query uses prepared statements, so user input cannot alter SQL
- Passwords are stored with `password_hash()` and checked with `password_verify()`
- All output is escaped with `htmlspecialchars()` against XSS
- The "Remember me" cookie is signed with HMAC-SHA256 and cannot be forged
- AJAX endpoints check the session role, so a passenger cannot reach admin actions
- Uploads are verified with `getimagesize()`, so a renamed script is rejected
- Deletes and edits happen over POST, not through a link that can be followed
