# Quill Motors — Laravel Landing Page Starter

## Quick start (Windows PowerShell)
1. Extract this folder.
2. Open PowerShell inside the `Quill-motors` folder.
3. Run:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan serve
```

4. Open http://127.0.0.1:8000

This starter is built on Laravel 12. Composer installs the Laravel framework and dependencies the first time you run `composer install`.

## Add your images
Put your own images in `public/images/` using these filenames:

| File | Suggested size | Use |
|---|---:|---|
| `hero-car.jpg` | 1920 × 1000 | Hero banner; keep the car on the right |
| `car-01.jpg` | 1200 × 750 | First vehicle card |
| `car-02.jpg` | 1200 × 750 | Second vehicle card |
| `car-03.jpg` | 1200 × 750 | Third vehicle card |
| `showroom.jpg` | 1000 × 1250 | Brand story portrait |
| `driving-experience.jpg` | 1920 × 900 | Wide promo banner |

Missing images display a designed placeholder automatically.

## Customize
- Main page: `resources/views/welcome.blade.php`
- Styling: `public/css/landing.css`
- JavaScript: `public/js/landing.js`
- Route: `routes/web.php`
- Brand and sample vehicle details are easy to find in the Blade template.

## Important
The inquiry form is wired to a Laravel POST route with validation and stores inquiries in a local JSON-lines file under `storage/app/inquiries.jsonl`. It is a starter implementation, not a production CRM. Add rate limiting, spam protection, and a database before deploying publicly.

No real vehicle listings or prices are included. Replace all sample names and price placeholders before launch.
