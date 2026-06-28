# FullPort - Simple PHP Authentication

Quick setup:

1. Place this folder in your XAMPP `htdocs` (example path: `c:\xampp\htdocs\fullport`).
2. Import the SQL schema: open `c:\xampp\htdocs\fullport\sql\init.sql` in phpMyAdmin and run it.
3. Update DB credentials in `db.php` if different from defaults (`root` / empty password).
4. Start Apache + MySQL from XAMPP and open `http://localhost/fullport/register.php` to create an account.

Admin:
- To create an admin user, either set `is_admin=1` for a user in phpMyAdmin, or insert a new user and set the flag.

Security notes:
- Uses `password_hash()` and prepared statements.
- For production, enable HTTPS, use a non-root DB user, and secure session cookies.
