# Settings Save Issue - Debugging Guide

## 🔧 Changes Made to Fix the Issue

### 1. Added CSRF Token to Form
**File:** `app/Views/Settings.php`
```php
<form id="settingsForm">
    <?= csrf_field() ?>
    <!-- form fields -->
</form>
```

### 2. Enhanced JavaScript with Debugging
**File:** `app/Views/Settings.php`

Added comprehensive console logging to track:
- Button click events
- Form validation
- Form data being sent
- Request URL
- Response status and data
- All errors

### 3. Added Controller Logging
**File:** `app/Controllers/SettingsController.php`

Added detailed logging throughout the `update()` method to track:
- Incoming POST data
- File uploads
- Validation results
- Database operations
- Session creation/updates
- Errors and exceptions

### 4. Created Test Endpoint
**File:** `app/Controllers/SettingsController.php`
**Route:** `/settings/test`

New endpoint to verify the controller is accessible.

---

## 🐛 How to Debug

### Step 1: Open Browser Console
1. Press `F12` to open Developer Tools
2. Go to the **Console** tab
3. Click "Save Settings" button
4. Read the console messages

**Expected Console Output (Success):**
```
Save settings button clicked
Form data:
school_name: Your School Name
total_classes: 20
school_year: 2024-2025
...
Sending request to: http://localhost/your-project/settings/update/1
Response status: 200
Response OK: true
Content-Type: application/json
Response data: {status: "success", ...}
```

**If you see errors:**
- Note the exact error message
- Take a screenshot
- Proceed to Step 2

### Step 2: Check Network Tab
1. In Developer Tools, go to the **Network** tab
2. Click "Save Settings" button again
3. Look for a POST request to `settings/update/1`
4. Click on it to see:
   - **Headers** tab: Request details
   - **Payload** tab: Data being sent
   - **Response** tab: Server response
   - **Preview** tab: Formatted response

**What to look for:**
- Status code (should be 200)
- Response type (should be JSON)
- Error messages in response

### Step 3: Test Controller Directly
Open your browser and visit:
```
http://your-domain/settings/test
```

**Expected Response:**
```json
{
    "status": "success",
    "message": "SettingsController is working!",
    "timestamp": "2024-01-15 10:30:00"
}
```

**If this fails:**
- The controller is not accessible
- Check your base URL configuration
- Check if the web server is running
- Check Routes.php configuration

### Step 4: Check Application Logs
**Location:** `writable/logs/log-YYYY-MM-DD.php`

Look for:
```
[SettingsController.update] Update method called with ID: 1
[SettingsController.update] POST data: {...}
[SettingsController.update] Validation passed
[SettingsController.update] Settings data inserted successfully
[SettingsController.update] Session created: 2024-2025
[SettingsController.update] Sending success response
```

**If you see ERROR entries:**
- Read the full error message
- Look for stack traces
- Note the file and line number

### Step 5: Check Database
Verify that both tables exist:
- `tz_web_setting`
- `sessions`

**Quick SQL Check:**
```sql
SHOW TABLES LIKE 'tz_web_setting';
SHOW TABLES LIKE 'sessions';

-- Check structure
DESCRIBE tz_web_setting;
DESCRIBE sessions;
```

---

## 🔍 Common Issues & Solutions

### Issue: Button Click Does Nothing

**Possible Causes:**
1. JavaScript error before click handler
2. Form validation failing silently
3. Base URL incorrect

**Solutions:**
- Check console for JavaScript errors
- Make sure all required fields have values
- Verify base URL in `.env` or `app/Config/App.php`

### Issue: "Form validation failed" in Console

**Solution:**
Fill in all required fields:
- School Name (required)
- Total Classes (required, must be a number)
- School Year (required, format: YYYY-YYYY like 2024-2025)
- Active Status (required, must select Yes or No)

### Issue: 404 Not Found

**Possible Causes:**
1. Routes not configured correctly
2. Base URL is wrong
3. URL rewriting not enabled

**Solutions:**
1. Check `app/Config/Routes.php` has:
```php
$routes->post("update/(:num)", 'SettingsController::update/$1');
```

2. Check `.env` file:
```env
app.baseURL = 'http://localhost/your-project/'
```
(Make sure it ends with `/`)

3. For Apache, ensure `.htaccess` exists:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### Issue: 500 Internal Server Error

**Possible Causes:**
1. PHP error in controller
2. Database connection failed
3. Validation error
4. Transaction failure

**Solutions:**
1. Check `writable/logs/` for detailed error
2. Test database connection
3. Check all required fields are filled
4. Verify both tables exist in database

### Issue: "Server returned non-JSON response"

**Possible Causes:**
1. PHP errors being output
2. Debug toolbar interfering
3. HTML error page being returned

**Solutions:**
1. Set environment to production in `.env`:
```env
CI_ENVIRONMENT = production
```

2. Disable debug toolbar in `app/Config/Filters.php`:
```php
public array $globals = [
    'after' => [
        // 'toolbar',  // Comment this out
    ],
];
```

3. Check Network tab Response to see what's actually returned

### Issue: Validation Errors

**Check console for specific field errors:**
- `school_name`: Must be filled, max 255 characters
- `total_classes`: Must be a number, minimum 1
- `school_year`: Must be filled, max 9 characters (format: YYYY-YYYY)
- `contact_email`: Must be valid email format (if provided)
- `is_active`: Must be 'yes' or 'no'

**File Upload Issues:**
- Max size: 5MB
- Allowed types: JPEG, PNG, GIF only
- If logo upload fails, try saving without uploading first

---

## 📝 What to Report

If the issue persists after following all steps, provide:

### 1. Browser Console Output
Copy the entire console output from when you click the button.

### 2. Network Tab Details
Screenshot or copy:
- Request URL
- Request Method
- Status Code
- Response Headers
- Response Body

### 3. Log File Entries
From `writable/logs/log-YYYY-MM-DD.php`:
- Any entries containing `[SettingsController.update]`
- Any ERROR entries
- Last 20-30 lines

### 4. Environment Info
- PHP Version: `php -v`
- Database: MySQL/MariaDB/PostgreSQL version
- CodeIgniter Version: Check `app/Config/Constants.php`
- Web Server: Apache/Nginx/PHP built-in
- Operating System: Windows/Linux/Mac

### 5. Form Data
What values you entered in each field.

---

## ✅ How the System Should Work

### Normal Flow:
1. User visits `/settings`
2. Form loads with existing data (if any)
3. User fills/updates form fields
4. User clicks "Save Settings"
5. JavaScript validates form
6. JavaScript sends POST request to `/settings/update/1`
7. Controller receives data
8. Controller validates data
9. Controller starts database transaction
10. Controller saves settings
11. Controller creates/updates session
12. Controller commits transaction
13. Controller returns JSON success response
14. JavaScript shows success message
15. Page reloads after 2 seconds

### What Gets Created/Updated:
- **Settings Table (`tz_web_setting`)**: All school configuration
- **Sessions Table (`sessions`)**: Academic year from `school_year` field
- **Session Active Status**: Automatically set to `yes`

---

## 🚀 Quick Start (First Time Setup)

1. Make sure database is configured in `.env`
2. Create tables if they don't exist
3. Visit `/settings`
4. Fill in required fields:
   - School Name: "Your School Name"
   - Total Classes: 20
   - School Year: 2024-2025
   - Active Status: Yes
5. Click "Save Settings"
6. Check console for success message
7. Page should reload with data saved

---

## 📞 Need More Help?

1. Read `TROUBLESHOOTING_SETTINGS.md` for detailed steps
2. Read `SETTINGS_SESSION_INTEGRATION.md` for system documentation
3. Check CodeIgniter logs in `writable/logs/`
4. Enable debugging in `.env`: `CI_ENVIRONMENT = development`
5. Use browser's Developer Tools (F12)

---

## 🔑 Key Files Modified

1. `app/Views/Settings.php` - Enhanced JavaScript with debugging
2. `app/Controllers/SettingsController.php` - Added logging and test endpoint
3. `app/Config/Routes.php` - Added test route
4. Documentation files created for reference

**All changes are backward compatible and include enhanced debugging only.**