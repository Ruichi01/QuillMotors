# Quill Motors — Laravel Landing Page Starter

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan storage:link
php artisan serve
```

`storage:link` exposes uploaded vehicle photos from `storage/app/public` at
`public/storage`, which is required for car images to display.

No real vehicle listings or prices are included. Replace all sample names and price placeholders before launch.
