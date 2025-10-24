# Troubleshooting Guide: Settings Save Issue

## Problem
When clicking "Save Settings" button, nothing happens.

## Recent Changes Made

1. **Added CSRF token** to the form
2. **Enhanced JavaScript** with detailed console logging
3. **Added debugging** to the controller
4. **Created test endpoint** at `/settings/test`

## Step-by-Step Debugging Process

### Step 1: Check Browser Console

1. Open your browser's Developer Tools (F12)
2. Go to the **Console** tab
3. Click "Save Settings" button
4. Look for console messages

**What to look for:**
- "Save settings button clicked" - Confirms button click works
- "Form data:" - Shows what data is being sent
- "Sending request to:" - Shows the URL being called
- Any error messages in red

### Step 2: Check Network Tab

1. In Developer Tools, go to the **Network** tab
2. Click "Save Settings" button
3. Look for a POST request to `settings/update/1`

**If you see the request:**
- Click on it to see details
- Check the **Response** tab for error messages
- Check the **Status** code (should be 200)

**If you DON'T see the request:**
- JavaScript is failing before fetch
- Check Console tab for JavaScript errors

### Step 3: Test the Controller Directly

Visit this URL in your browser:
```
http://your-domain/settings/test
```

**Expected response:**
```json
{
    "status": "success",
    "message": "SettingsController is working!",
    "timestamp": "2024-01-15 10:30:00"
}
```

**If you get an error:**
- Controller is not accessible
- Check Routes.php configuration
- Check if server is running

### Step 4: Check Application Logs

Location: `writable/logs/log-YYYY-MM-DD.php`

Look for messages containing:
- `[SettingsController.update]`
- `ERROR` entries
- Stack traces

### Step 5: Check Database Connection

The issue might be database-related. Check:
```php
// In terminal or add to a test page:
$db = \Config\Database::connect();
if ($db->connID) {
    echo "Database connected!";
} else {
    echo "Database connection failed!";
}
```

## Common Issues and Solutions

### Issue 1: Base URL Not Set Correctly

**Symptom:** Fetch request goes to wrong URL

**Solution:**
1. Check `.env` file:
```
app.baseURL = 'http://localhost/your-project/'
```

2. Or check `app/Config/App.php`:
```php
public string $baseURL = 'http://localhost/your-project/';
```

**Important:** Make sure baseURL ends with `/`

### Issue 2: CSRF Token Mismatch

**Symptom:** 403 Forbidden or CSRF verification failed

**Solution:**
1. Clear browser cache and cookies
2. Reload the page
3. Try again

**Note:** CSRF is currently disabled in Filters.php, so this shouldn't be the issue.

### Issue 3: File Upload Issues

**Symptom:** Error when trying to upload school logo

**Solution:**
1. Check `php.ini` settings:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

2. Restart web server after changes

3. Try saving WITHOUT uploading a file first

### Issue 4: JSON Response Not Returned

**Symptom:** "Server returned non-JSON response" error

**Possible causes:**
1. PHP errors being output before JSON
2. HTML/Debug toolbar interfering
3. Echo/print statements in code

**Solution:**
1. Check if debug mode is on in `.env`:
```
CI_ENVIRONMENT = production
```

2. Temporarily disable Debug Toolbar in `app/Config/Filters.php`:
```php
public array $globals = [
    'after' => [
        // 'toolbar',
    ],
];
```

### Issue 5: Route Not Found (404)

**Symptom:** 404 error in Network tab

**Check:**
1. Is the route defined in `app/Config/Routes.php`?
```php
$routes->post("update/(:num)", 'SettingsController::update/$1');
```

2. Is URL rewriting enabled? (Apache: mod_rewrite, Nginx: try_files)

3. Is `.htaccess` file present in project root?

### Issue 6: Database Transaction Fails

**Symptom:** "Failed to save settings and session" error

**Check:**
1. Do both tables exist?
   - `tz_web_setting`
   - `sessions`

2. Do tables have correct structure?

3. Check database logs for errors

### Issue 7: Validation Errors

**Symptom:** Form data not passing validation

**Solution:**
Check browser console for validation errors. Common issues:
- Missing required fields
- Invalid email format
- School year not in YYYY-YYYY format
- Total classes not a number

## Quick Test Script

Create a file `test-settings.php` in your project root:

```php
<?php
require 'vendor/autoload.php';

// Test database connection
$db = \Config\Database::connect();
echo "Database: " . ($db->connID ? "Connected ✓" : "Failed ✗") . "\n";

// Test if tables exist
$tables = $db->listTables();
echo "tz_web_setting exists: " . (in_array('tz_web_setting', $tables) ? "Yes ✓" : "No ✗") . "\n";
echo "sessions exists: " . (in_array('sessions', $tables) ? "Yes ✓" : "No ✗") . "\n";

// Test models
try {
    $settingsModel = new \App\Models\SettingsModel();
    echo "SettingsModel: Working ✓\n";
} catch (Exception $e) {
    echo "SettingsModel: Error ✗ - " . $e->getMessage() . "\n";
}

try {
    $sessionModel = new \App\Models\SessionModel();
    echo "SessionModel: Working ✓\n";
} catch (Exception $e) {
    echo "SessionModel: Error ✗ - " . $e->getMessage() . "\n";
}
```

Run: `php test-settings.php`

## What the Enhanced Code Does

### JavaScript Changes (Settings.php)

1. **Console Logging**: Every step is logged to help you see where it fails
2. **Better Error Messages**: Shows specific errors instead of generic ones
3. **Visual Feedback**: Button shows "Saving..." while processing
4. **Response Validation**: Checks if response is valid JSON

### Controller Changes (SettingsController.php)

1. **Detailed Logging**: Every action is logged to `writable/logs/`
2. **Error Tracking**: Full exception details including stack trace
3. **Test Endpoint**: New `/settings/test` route to verify controller works

## Expected Console Output (Success)

```
Save settings button clicked
Form data:
school_name: Test School
total_classes: 20
school_year: 2024-2025
...
Sending request to: http://localhost/project/settings/update/1
Response status: 200
Response OK: true
Content-Type: application/json
Response data: {status: "success", message: "Settings and session saved successfully", data: {...}}
```

## Expected Console Output (Error)

```
Save settings button clicked
Form data: ...
Sending request to: http://localhost/project/settings/update/1
Response status: 500
Response not OK. Status: 500
Response text: [error details]
Error in saveSettings: Server error (500). Check console for details.
```

## Next Steps

1. **Follow Step 1-5 above** in order
2. **Take screenshots** of any errors you see
3. **Copy console messages** exactly as shown
4. **Check log files** in `writable/logs/`
5. **Report findings** with:
   - Console output
   - Network tab details
   - Log file entries
   - Any error messages

## Contact Information

If you've followed all steps and still have issues, provide:
- Browser console output (full)
- Network tab screenshot
- Relevant log file entries
- PHP version
- Database type and version
- CodeIgniter version

This will help diagnose the exact issue!