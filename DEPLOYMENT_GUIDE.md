# University of Sharjah Faculty Recruitment System
## Deployment Guide for XAMPP

This guide will help you set up and deploy the UoS Faculty Recruitment System on XAMPP.

---

## Prerequisites

1. **XAMPP** installed on your system
   - Download from: https://www.apachefriends.org/
   - Version: PHP 7.4 or higher recommended
   - Includes: Apache, MySQL, PHP, phpMyAdmin

2. **Web Browser** (Chrome, Firefox, Edge, etc.)

---

## Step 1: Install and Start XAMPP

1. Install XAMPP if not already installed
2. Open **XAMPP Control Panel**
3. Start **Apache** and **MySQL** services
   - Click "Start" buttons next to Apache and MySQL
   - Both should show green "Running" status

---

## Step 2: Place Project Files

1. Copy the entire project folder to XAMPP's `htdocs` directory:
   - **Windows**: `C:\xampp\htdocs\`
   - **Mac**: `/Applications/XAMPP/htdocs/`
   - **Linux**: `/opt/lampp/htdocs/`

2. Rename the project folder to `uos_recruitment` (optional, for cleaner URLs)

   **Final path should be:**
   ```
   C:\xampp\htdocs\uos_recruitment\
   ```

---

## Step 3: Create Database

1. Open phpMyAdmin:
   - Go to: `http://localhost/phpmyadmin`
   - Or click "Admin" button next to MySQL in XAMPP Control Panel

2. Create a new database:
   - Click "New" in the left sidebar
   - Database name: `uos_recruitment`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. Import the database schema:
   - Select the `uos_recruitment` database
   - Click "Import" tab
   - Click "Choose File"
   - Select `database.sql` from the project root
   - Click "Go" at the bottom

4. Verify import:
   - You should see multiple tables created (users, applications, vacancies, etc.)
   - Check that seed data was imported (users, colleges, vacancies)

---

## Step 4: Configure Database Connection

1. Open the database configuration file:
   ```
   backend/config/database.php
   ```

2. Update database credentials if needed:
   ```php
   return [
       'host' => 'localhost',
       'dbname' => 'uos_recruitment',
       'username' => 'root',        // Default XAMPP MySQL username
       'password' => '',            // Default XAMPP MySQL password (empty)
       // ... rest of config
   ];
   ```

   **Note:** Default XAMPP MySQL credentials:
   - Username: `root`
   - Password: `` (empty)

   If you've changed MySQL root password, update it here.

---

## Step 5: Set File Permissions (Linux/Mac only)

If you're on Linux or Mac, set proper permissions for uploads directory:

```bash
chmod -R 755 backend/uploads/
```

Windows users can skip this step.

---

## Step 6: Access the Application

1. **Main Portal:**
   ```
   http://localhost/uos_recruitment/
   ```

2. **Public Applicant Portal:**
   ```
   http://localhost/uos_recruitment/public_facing_applicant_portal_module/
   ```

3. **Admin Portal:**
   ```
   http://localhost/uos_recruitment/University_Recruitment_System/
   ```

---

## Step 7: Test the System

### Test Database Connection

1. Visit: `http://localhost/uos_recruitment/backend/api/vacancies.php`
2. You should see JSON response with vacancies

### Test Application Submission

1. Go to: `http://localhost/uos_recruitment/public_facing_applicant_portal_module/vacancies.html`
2. Click "Apply Now" on any vacancy
3. Fill out the application form
4. Submit and verify it saves to database

### Test Admin Login

**Default Admin Credentials:**
- Email: `admin@sharjah.ac.ae`
- Password: `password`

**Note:** The default password hash in the database is for the word "password". In production, change all default passwords!

---

## Step 8: Default User Accounts

The database includes these seed accounts (all with password: `password`):

| Email | Role | Full Name |
|-------|------|-----------|
| admin@sharjah.ac.ae | HR | System Administrator |
| hr@sharjah.ac.ae | HR | HR Manager |
| hod@sharjah.ac.ae | HoD | Head of Department |
| dean@sharjah.ac.ae | Dean | College Dean |
| applicant@example.com | Applicant | Test Applicant |

**⚠️ IMPORTANT:** Change all passwords before deploying to production!

---

## Step 9: API Endpoints

All API endpoints are located in `backend/api/`:

| Endpoint | Method | Description | Auth Required |
|----------|--------|-------------|---------------|
| `/api/vacancies.php` | GET | Get all vacancies | No |
| `/api/submit_application.php` | POST | Submit application | No |
| `/api/applications.php` | GET | Get applications | Yes |
| `/api/my_applications.php` | GET | Get user's applications | Yes |
| `/api/login.php` | POST | User login | No |
| `/api/logout.php` | POST | User logout | Yes |
| `/api/notifications.php` | GET | Get notifications | Yes |
| `/api/messages.php` | GET | Get messages | Yes |

---

## Step 10: File Uploads

Uploaded files are stored in:
- Documents: `backend/uploads/documents/`
- Advertisements: `backend/uploads/advertisements/`

Make sure these directories exist and are writable.

---

## Troubleshooting

### Issue: "Database connection failed"

**Solution:**
1. Check MySQL is running in XAMPP Control Panel
2. Verify database credentials in `backend/config/database.php`
3. Ensure database `uos_recruitment` exists
4. Check phpMyAdmin is accessible

### Issue: "404 Not Found" on API endpoints

**Solution:**
1. Check Apache is running
2. Verify file paths are correct
3. Check `.htaccess` if using URL rewriting (not required for this setup)

### Issue: File uploads not working

**Solution:**
1. Check `backend/uploads/` directory exists
2. Verify directory permissions (755 or 777)
3. Check `php.ini` settings:
   - `upload_max_filesize = 5M`
   - `post_max_size = 10M`
   - `file_uploads = On`

### Issue: "Session not working"

**Solution:**
1. Check `php.ini`:
   - `session.save_path` is writable
   - `session.auto_start = 0` (should be 0)
2. Clear browser cookies
3. Restart Apache

### Issue: CORS errors in browser console

**Solution:**
- The API endpoints include CORS headers
- If issues persist, check Apache mod_headers is enabled
- For local development, browser extensions may interfere

---

## Production Deployment Checklist

Before deploying to production:

- [ ] Change all default passwords
- [ ] Update database credentials
- [ ] Set `display_errors = 0` in `php.ini`
- [ ] Enable HTTPS/SSL
- [ ] Configure proper file permissions
- [ ] Set up database backups
- [ ] Configure email sending (for notifications)
- [ ] Review and update security settings
- [ ] Test all functionality thoroughly
- [ ] Set up error logging
- [ ] Configure session security
- [ ] Review file upload security

---

## System Architecture

```
Project Structure:
├── backend/
│   ├── api/              # API endpoints
│   ├── config/           # Configuration files
│   ├── controllers/      # Business logic
│   ├── models/           # Database models
│   └── uploads/          # Uploaded files
├── public_facing_applicant_portal_module/
│   ├── index.html
│   ├── vacancies.html
│   ├── apply.html
│   └── applicant-dashboard.html
├── University_Recruitment_System/
│   ├── talent_request_submission_form.html
│   ├── recruitment_dashboard_hr_view.html
│   ├── application_review_interface.html
│   └── ... (other admin pages)
├── database.sql          # Database schema
└── index.html            # Main portal
```

---

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review error logs in XAMPP
3. Check browser console for JavaScript errors
4. Verify PHP error logs

---

## Quick Start Summary

1. ✅ Start XAMPP (Apache + MySQL)
2. ✅ Copy project to `htdocs`
3. ✅ Create database `uos_recruitment` in phpMyAdmin
4. ✅ Import `database.sql`
5. ✅ Access: `http://localhost/uos_recruitment/`
6. ✅ Login: `admin@sharjah.ac.ae` / `password`

---

**System is now ready to use!** 🎉

