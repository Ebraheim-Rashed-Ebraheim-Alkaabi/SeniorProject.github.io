# Troubleshooting Guide - No Job Listings Showing

## Quick Diagnosis Steps

### Step 1: Check Browser Console
1. Open the vacancies page
2. Press **F12** to open Developer Tools
3. Click **Console** tab
4. Look for red error messages
5. Take a screenshot or note the error

### Step 2: Test API Directly
Open this URL in your browser:
```
http://localhost/project/backend/api/vacancies.php
```

**Expected Result:**
- Should show JSON data like: `{"success":true,"vacancies":[...]}`
- If you see an error, that's the problem!

### Step 3: Check Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `uos_recruitment` database
3. Click on `vacancies` table
4. Click **Browse** tab
5. **Do you see any rows?**
   - ✅ Yes → Database has data, problem is elsewhere
   - ❌ No → Database needs to be imported

### Step 4: Check Database Connection
1. Open: `http://localhost/project/backend/api/vacancies.php`
2. If you see: `{"success":false,"message":"Database connection failed"}`
   - Check `backend/config/database.php`
   - Verify MySQL is running in XAMPP

---

## Common Issues & Solutions

### Issue 1: "Failed to fetch" or Network Error

**Cause:** API path is wrong or server not running

**Solution:**
1. Check Apache is running in XAMPP
2. Verify the API path in browser:
   ```
   http://localhost/project/backend/api/vacancies.php
   ```
3. If 404 error, check file exists at that path

### Issue 2: Database Connection Error

**Error:** `Database connection failed`

**Solution:**
1. Check MySQL is running in XAMPP
2. Open `backend/config/database.php`
3. Verify credentials:
   ```php
   'username' => 'root',
   'password' => '',  // Empty by default
   ```
4. Test connection in phpMyAdmin

### Issue 3: Empty Database (No Vacancies)

**Error:** `{"success":true,"vacancies":[]}` (empty array)

**Solution:**
1. Open phpMyAdmin
2. Select `uos_recruitment` database
3. Click **Import** tab
4. Select `database.sql` file
5. Click **Go**
6. Verify `vacancies` table has 6 rows

### Issue 4: CORS Error

**Error:** `Access-Control-Allow-Origin` in console

**Solution:**
- Already handled in API (CORS headers included)
- If still occurs, check browser extensions

### Issue 5: Wrong API Path

**Current path in code:** `../../backend/api/vacancies.php`

**If your URL is:** `http://localhost/project/...`
**Then path should be:** `../backend/api/vacancies.php` or `../../backend/api/vacancies.php`

**To fix:**
1. Check browser Network tab (F12 → Network)
2. See what URL it's trying to fetch
3. Adjust path in `vacancies.html` if needed

---

## Quick Fix: Test API Endpoint

Open these URLs directly in your browser:

1. **Test API:**
   ```
   http://localhost/project/backend/api/vacancies.php
   ```

2. **Test with filters:**
   ```
   http://localhost/project/backend/api/vacancies.php?status=open
   ```

**Expected:** JSON response with vacancies array

---

## Manual Database Check

Run this SQL in phpMyAdmin to check if data exists:

```sql
SELECT COUNT(*) as total FROM vacancies WHERE status = 'open';
```

Should return: `6` (if database was imported correctly)

---

## Still Not Working?

1. **Check PHP Error Log:**
   - Location: `C:\xampp\php\logs\php_error_log`
   - Look for recent errors

2. **Check Apache Error Log:**
   - Location: `C:\xampp\apache\logs\error.log`
   - Look for recent errors

3. **Enable Error Display:**
   - Open `backend/config/config.php`
   - Make sure `display_errors` is set to `1` for debugging

4. **Test Database Connection:**
   Create a test file `test_db.php` in backend folder:
   ```php
   <?php
   require_once 'config/db_connection.php';
   try {
       $db = getDB();
       echo "Database connected successfully!";
   } catch (Exception $e) {
       echo "Error: " . $e->getMessage();
   }
   ?>
   ```
   Access: `http://localhost/project/backend/test_db.php`

---

## Most Common Issue

**90% of cases:** Database not imported or empty

**Quick Fix:**
1. Go to phpMyAdmin
2. Drop `uos_recruitment` database (if exists)
3. Create new `uos_recruitment` database
4. Import `database.sql` again
5. Verify `vacancies` table has 6 rows
6. Refresh vacancies page

