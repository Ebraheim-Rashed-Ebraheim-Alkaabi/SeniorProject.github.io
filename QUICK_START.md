# Quick Start Guide - UoS Recruitment System

## 🚀 How to Run the System

Follow these simple steps to get the system running on your computer.

---

## Step 1: Install XAMPP (if not already installed)

1. Download XAMPP from: https://www.apachefriends.org/
2. Install it (default settings are fine)
3. Install location: Usually `C:\xampp\` on Windows

---

## Step 2: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** button next to:
   - ✅ **Apache** (web server)
   - ✅ **MySQL** (database)
3. Both should show green "Running" status

---

## Step 3: Copy Project to XAMPP

1. Copy your entire project folder to:
   ```
   C:\xampp\htdocs\
   ```

2. Your project should be at:
   ```
   C:\xampp\htdocs\uos_recruitment\
   ```
   (or whatever you named the folder)

---

## Step 4: Create Database

1. Open phpMyAdmin:
   - Go to: **http://localhost/phpmyadmin**
   - Or click **Admin** button next to MySQL in XAMPP

2. Create database:
   - Click **"New"** in left sidebar
   - Database name: `uos_recruitment`
   - Click **"Create"**

3. Import database:
   - Select `uos_recruitment` database
   - Click **"Import"** tab
   - Click **"Choose File"**
   - Select `database.sql` from your project folder
   - Click **"Go"** at bottom
   - Wait for "Import has been successfully finished" message

---

## Step 5: Configure Database (if needed)

1. Open file: `backend/config/database.php`

2. Check these settings (usually default is fine):
   ```php
   'host' => 'localhost',
   'dbname' => 'uos_recruitment',
   'username' => 'root',
   'password' => '',  // Empty by default in XAMPP
   ```

3. **Only change if** you modified MySQL password in XAMPP

---

## Step 6: Access the System

Open your web browser and go to:

### Main Portal:
```
http://localhost/uos_recruitment/
```

### Public Applicant Portal:
```
http://localhost/uos_recruitment/public_facing_applicant_portal_module/
```

### Admin Portal:
```
http://localhost/uos_recruitment/University_Recruitment_System/
```

---

## Step 7: Test the System

### Test 1: View Vacancies
1. Go to: `http://localhost/uos_recruitment/public_facing_applicant_portal_module/vacancies.html`
2. You should see job listings loaded from database

### Test 2: Submit Application
1. Click "Apply Now" on any vacancy
2. Fill out the application form
3. Submit
4. Should see success message with Application ID

### Test 3: Admin Login
1. Go to any admin page
2. Login with:
   - **Email:** `admin@sharjah.ac.ae`
   - **Password:** `password`

---

## ✅ You're Done!

The system is now running. You can:
- Browse job vacancies
- Submit applications
- Login as admin
- Manage the system

---

## 🐛 Troubleshooting

### Problem: "Database connection failed"
**Solution:**
- Make sure MySQL is running in XAMPP
- Check database name is `uos_recruitment`
- Verify credentials in `backend/config/database.php`

### Problem: "404 Not Found"
**Solution:**
- Check Apache is running
- Verify project is in `C:\xampp\htdocs\`
- Check folder name matches URL

### Problem: "No vacancies showing"
**Solution:**
- Check database was imported correctly
- Open phpMyAdmin and verify `vacancies` table has data
- Check browser console for errors (F12)

### Problem: "File upload not working"
**Solution:**
- Make sure `backend/uploads/` folder exists
- Check folder permissions (should be writable)

---

## 📝 Default Login Accounts

All passwords are: `password`

| Email | Role |
|-------|------|
| admin@sharjah.ac.ae | HR Admin |
| hr@sharjah.ac.ae | HR Manager |
| hod@sharjah.ac.ae | Head of Department |
| dean@sharjah.ac.ae | College Dean |
| applicant@example.com | Test Applicant |

---

## 🎯 Quick Commands

**Start XAMPP:**
- Open XAMPP Control Panel
- Start Apache + MySQL

**Stop XAMPP:**
- Click "Stop" in XAMPP Control Panel

**Access phpMyAdmin:**
- http://localhost/phpmyadmin

**View Error Logs:**
- Apache: `C:\xampp\apache\logs\error.log`
- PHP: `C:\xampp\php\logs\php_error_log`

---

## 📞 Need Help?

1. Check `DEPLOYMENT_GUIDE.md` for detailed instructions
2. Check `PROJECT_BREAKDOWN.md` for system architecture
3. Verify all steps above were completed
4. Check XAMPP error logs

---

**Happy Testing! 🎉**

