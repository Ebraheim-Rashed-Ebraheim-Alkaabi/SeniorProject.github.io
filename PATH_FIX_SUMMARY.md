# API Path Fix Summary

## Problem Identified
The API requests were going to the wrong URL:
- **Wrong:** `http://localhost/backend/api/vacancies.php` (404 error)
- **Correct:** `http://localhost/project/backend/api/vacancies.php`

## Solution Applied
Changed all API paths from relative to absolute paths:

### Files Updated:

1. **vacancies.html**
   - Changed: `../backend/api/vacancies.php`
   - To: `/project/backend/api/vacancies.php`

2. **apply.html**
   - Changed: `../../backend/api/submit_application.php`
   - To: `/project/backend/api/submit_application.php`

## Why Absolute Path?
When accessing from `http://localhost/project/...`, relative paths can be inconsistent. Using absolute paths starting with `/project/` ensures the correct path regardless of the current page location.

## Testing
After this fix:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh the page (Ctrl+F5)
3. Check Network tab - should now show 200 OK instead of 404
4. Vacancies should load correctly

## If Still Not Working
If you're accessing from a different base URL (not `/project/`), update the paths:
- If using: `http://localhost/uos_recruitment/...`
- Change paths to: `/uos_recruitment/backend/api/...`

