# 🚀 Super Simple Step-By-Step Installation Guide for Windows

Share this guide with your friend! They just need to copy and paste the commands (one by one) into their terminal (Command Prompt or PowerShell).  
**No prior experience required.**

---

## 1. Install Git

Download and install Git from here:  
👉 https://git-scm.com/download/win  
Click "Next" on all steps until it's done.

---

## 2. Install PHP

Download the PHP installer from here:  
👉 https://windows.php.net/download/  
- Scroll to "PHP 8.2 (x64)" — download the "Zip" file (Thread Safe).
- Unzip it to `C:\php`
- Open the folder, find `php.exe` and remember this location.

**Add PHP to your PATH:**  
- Search for “environment variables” in Windows Search.
- Click "Edit the system environment variables" → "Environment Variables..."
- Under "System variables", find and select “Path”, then click "Edit".
- Click "New" and paste the path to your PHP folder (like `C:\php`)
- Click OK on all windows.

**Test PHP:**  
Open Command Prompt and type:
```sh
php -v
```
You should see something like `PHP 8.2.x`.

---

## 3. Install Composer

Download Composer installer from here:  
👉 https://getcomposer.org/Composer-Setup.exe  
Run it and follow the steps (it should auto-detect your PHP folder).

---

## 4. Install Node.js (npm included)

Download and install Node.js from here:  
👉 https://nodejs.org/  
Pick the **LTS** version (recommended for most users).

---

## 5. Download the Project

Open **Command Prompt** (press Windows Key, type "cmd", hit Enter), then run:
```sh
git clone https://github.com/FAROUK0001/simple-contact-manager.git
cd simple-contact-manager
```

---

## 6. Install PHP Dependencies

```sh
composer install
```

---

## 7. Install JavaScript (frontend) Dependencies

```sh
npm install
```

---

## 8. Set Up Environment

```sh
copy .env.example .env
php artisan key:generate
```

If you get an error, try:
```sh
cp .env.example .env
```

---

## 9. Use SQLite for Database (Easiest)

```sh
cd database
type nul > database.sqlite
cd ..
```
Open the `.env` file in Notepad:
```sh
notepad .env
```
Change these lines:
```
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/your/project/database/database.sqlite
```
(Replace `/full/path/...` with your actual path, e.g. `C:/Users/YourName/simple-contact-manager/database/database.sqlite`)

Save and close Notepad.

---

## 10. Run Database Migrations

```sh
php artisan migrate
```

---

## 11. Build and Run the App

Start the backend (leave this window open):
```sh
php artisan serve
```
Open **another** Command Prompt, go to your project again:
```sh
cd simple-contact-manager
npm run dev
```

---

## 12. Open in Your Browser

Go to:  
👉 http://localhost:8000

---

## 13. Register a New Account

Click "Register" and sign up.  
Now you can use the app!

---

## 💡 Need Help?

- If something doesn't work, copy the error message and ask for help!
- Or visit the official Laravel docs: https://laravel.com/docs

---

You did it! 🎉
