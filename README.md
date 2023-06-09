
# Payment Service Sederhana

Test membuat payment service sederhana


## Set Up Aplikasi

Import database

```bash
  mysql -u [username] -p [nama database] < payment-service-sederhana.sql
```

Konfigurasi database terdapat pada file berikut

```bash
  src/config/database.php
```

Install depedency via composer

```bash
  composer install
```

Jalankan service REST API dengan development server php

```bash
  php -S localhost:8000 -t src/public/
```

Jalankan service PHP CLI

```bash
  php src/cli/transaction-cli.php references_id=1 status=paid
```
## Dokumentasi Postman

[Postman Request](https://documenter.getpostman.com/view/14496975/2s93sabDnv)

