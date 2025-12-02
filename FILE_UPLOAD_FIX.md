# File Upload Fix & Troubleshooting

## What I Fixed

1. **Improved file upload handling** - Now properly handles both single and multiple file uploads
2. **Better error handling** - Shows specific error messages
3. **Multiple file support** - Fixed handling for `degreeCertificates`, `transcripts`, and `additionalDocuments`

## Test Your Upload

### Step 1: Test Upload Directory
Open this URL:
```
http://localhost/project/backend/test_upload.php
```

This will show:
- ✅ PHP upload settings
- ✅ Upload directory status
- ✅ Directory permissions
- ✅ Test upload form

### Step 2: Check Common Issues

#### Issue 1: PHP Upload Limits Too Small
**Check:** Open `test_upload.php` and look at "upload_max_filesize"

**Fix:**
1. Open `C:\xampp\php\php.ini`
2. Find these lines:
   ```ini
   upload_max_filesize = 5M
   post_max_size = 10M
   ```
3. Make sure they're at least:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 20M
   ```
4. **Restart Apache** in XAMPP Control Panel

#### Issue 2: Directory Not Writable
**Check:** `test_upload.php` will show if directory is writable

**Fix (Windows):**
- Right-click `backend/uploads/` folder
- Properties → Security tab
- Make sure "Users" or "Everyone" has "Write" permission
- Or set folder permissions to allow writing

**Fix (Command Line):**
```powershell
icacls "backend\uploads" /grant Users:F /T
```

#### Issue 3: Files Not Being Sent
**Check:** Open browser DevTools (F12) → Network tab → Submit form → Check the request

**What to look for:**
- Request should be `multipart/form-data`
- Should see file data in the request

## How File Upload Works

1. **User selects file** → File is shown in the form (this is just preview)
2. **User submits form** → Files are sent to server via `FormData`
3. **Server receives files** → Files are validated and saved
4. **Files are stored** → In `backend/uploads/documents/` directory

## Debugging Steps

### Step 1: Check Browser Console
1. Open DevTools (F12)
2. Go to Console tab
3. Try to upload a file
4. Look for any JavaScript errors

### Step 2: Check Network Tab
1. Open DevTools (F12)
2. Go to Network tab
3. Submit the form
4. Find `submit_application.php` request
5. Check:
   - Status code (should be 200)
   - Request payload (should show files)
   - Response (should show success/error)

### Step 3: Check Server Logs
1. Check PHP error log: `C:\xampp\php\logs\php_error_log`
2. Look for file upload errors

### Step 4: Test Direct Upload
Use the test page: `http://localhost/project/backend/test_upload.php`
- Try uploading a small file (under 1MB)
- If this works, the issue is with the form
- If this fails, the issue is with PHP/server settings

## Common Error Messages

| Error | Meaning | Solution |
|-------|---------|----------|
| "File upload error" | General upload failure | Check PHP error log |
| "File size exceeds..." | File too large | Increase `upload_max_filesize` |
| "Invalid file type" | Wrong file format | Use PDF, DOC, DOCX, JPG, PNG |
| "Failed to save file" | Directory not writable | Fix folder permissions |
| "No file was uploaded" | File not sent | Check form enctype |

## Quick Fix Checklist

- [ ] Upload directory exists: `backend/uploads/documents/`
- [ ] Directory is writable (check with test_upload.php)
- [ ] PHP `upload_max_filesize` ≥ 5M
- [ ] PHP `post_max_size` ≥ 10M
- [ ] File size < 5MB
- [ ] File type is allowed (PDF, DOC, DOCX, JPG, PNG)
- [ ] Form has `enctype="multipart/form-data"` (already set)
- [ ] Apache is running

## Still Not Working?

1. **Test with test_upload.php first** - This will show exactly what's wrong
2. **Check PHP error log** - Look for specific error messages
3. **Try a very small file** (like a 100KB PDF) - Rules out size issues
4. **Check browser console** - Look for JavaScript errors

