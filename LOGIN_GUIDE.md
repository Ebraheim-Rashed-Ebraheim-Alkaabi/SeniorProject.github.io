# Admin Login Guide

## ✅ Login Page Created

I've created a login page for the admin portal at:
```
http://localhost/project/University_Recruitment_System/login.html
```

## How to Access Admin Pages

### Option 1: Direct Login
1. Go to: `http://localhost/project/University_Recruitment_System/login.html`
2. Enter credentials
3. Click "Login"
4. You'll be redirected to the dashboard

### Option 2: From Admin Portal Index
1. Go to: `http://localhost/project/University_Recruitment_System/`
2. Click "🔐 Login to Admin Portal" button
3. Enter credentials and login

## Default Login Credentials

All passwords are: `password`

| Email | Role | Access Level |
|-------|------|--------------|
| admin@sharjah.ac.ae | HR Admin | Full access |
| hr@sharjah.ac.ae | HR Manager | HR features |
| hod@sharjah.ac.ae | Head of Department | HoD features |
| dean@sharjah.ac.ae | College Dean | Dean features |

## Authentication System

### How It Works
1. **Login** → User enters email/password
2. **API Call** → Sends to `/backend/api/login.php`
3. **Session Created** → User info stored (localStorage for demo)
4. **Protected Pages** → Check authentication before loading
5. **Logout** → Clears session and redirects to login

### Protected Pages
These pages now require login:
- ✅ Recruitment Dashboard
- ✅ Application Review Interface
- ✅ Interview Scheduling
- ✅ Talent Request Form
- ✅ HR Onboarding Management

### Public Pages (No Login Required)
- Pre-Joining Checklist (for new faculty)
- New Faculty Welcome (for new faculty)

## Logout

Click the **"Logout"** button in the header of any admin page, or:
- Clear browser localStorage
- Or manually go to: `login.html`

## Troubleshooting

### Issue: "Connection error" on login
**Solution:**
- Check if backend API is accessible: `http://localhost/project/backend/api/login.php`
- Verify database is set up
- Check Apache is running

### Issue: "Invalid email or password"
**Solution:**
- Verify credentials (all passwords are: `password`)
- Check database has user accounts (import database.sql)
- Check phpMyAdmin → `users` table

### Issue: Redirected to login even after logging in
**Solution:**
- Check browser console for errors
- Verify `auth_check.js` is loading
- Clear browser cache and try again

## Security Notes

**Current Implementation:**
- Uses localStorage for demo (client-side)
- In production, should use server-side sessions
- Passwords are hashed in database (bcrypt)

**For Production:**
- Implement proper session management
- Add CSRF protection
- Add rate limiting
- Use secure cookies
- Implement password reset functionality

