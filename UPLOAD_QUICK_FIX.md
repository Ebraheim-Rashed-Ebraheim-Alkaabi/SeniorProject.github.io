# File Upload Quick Fix

## ✅ FIXED: Missing Form Attribute

The form was missing `enctype="multipart/form-data"` which is **required** for file uploads!

**What I changed:**
- Added `enctype="multipart/form-data"` to the form tag

## Test Now

1. **Refresh the page** (Ctrl+F5 to clear cache)
2. **Go to Step 5 (Documents)**
3. **Select a file** - You should see the file name appear
4. **Submit the form** - Files should now upload

## If Still Not Working

### Quick Test
Open this test page:
```
http://localhost/project/backend/test_upload.php
```

This will:
- ✅ Check PHP upload settings
- ✅ Test if upload directory works
- ✅ Let you test uploading a file directly

### Common Issues

1. **PHP Upload Limit Too Small**
   - Open: `C:\xampp\php\php.ini`
   - Find: `upload_max_filesize = 5M`
   - Change to: `upload_max_filesize = 10M`
   - Find: `post_max_size = 10M`
   - Change to: `post_max_size = 20M`
   - **Restart Apache**

2. **Directory Not Writable**
   - Check: `backend/uploads/documents/` folder
   - Right-click → Properties → Security
   - Make sure it's writable

3. **File Too Large**
   - Maximum file size: 5MB per file
   - Check file size before uploading

## What Should Happen

1. ✅ Select file → File name appears
2. ✅ Submit form → Files upload to server
3. ✅ Success message → Application ID shown
4. ✅ Files saved → In `backend/uploads/documents/`

Try it now - the form should work!

