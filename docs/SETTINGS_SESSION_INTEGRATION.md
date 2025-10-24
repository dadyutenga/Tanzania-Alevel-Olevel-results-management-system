# Settings and Session Integration Documentation

## Overview

This document explains how the Settings system integrates with the Session system in the Tanzania A-Level/O-Level Results Management System.

## Key Features

### 1. Automatic Session Creation/Update

When school settings are created or updated, the system automatically manages the corresponding session based on the `school_year` field.

### 2. School Year Format

- **Format**: `YYYY-YYYY` (e.g., `2024-2025`)
- **Length**: 9 characters
- **Purpose**: Represents the academic year

### 3. How It Works

#### Creating Settings for the First Time

1. User navigates to `/settings`
2. Fills in the form with school information including `school_year` (e.g., `2024-2025`)
3. Clicks "Save Settings"
4. System performs the following actions:
   - Validates all input data
   - Saves settings to `tz_web_setting` table
   - Automatically creates a new session in the `sessions` table with:
     - `session` = school_year value (e.g., `2024-2025`)
     - `is_active` = `yes`

#### Updating Existing Settings

1. User navigates to `/settings`
2. Form is pre-populated with existing data
3. User modifies fields (including `school_year` if needed)
4. Clicks "Save Settings"
5. System performs the following actions:
   - Validates all input data
   - Deletes existing settings record
   - Inserts new settings record with updated data
   - Checks if a session exists with the same `school_year`
     - **If exists**: Updates the existing session to `is_active = yes`
     - **If not exists**: Creates a new session record

### 4. Database Transaction

All operations (settings update + session create/update) are wrapped in a database transaction to ensure data consistency:

```php
// Start transaction
$db->transStart();

// Update settings
// Create/Update session

// Commit or rollback
$db->transComplete();
```

If any operation fails, all changes are rolled back.

## API Endpoints

### View Settings Page
```
GET /settings/
```
Returns the settings view with pre-populated data (if exists).

### Get Settings Data (JSON)
```
GET /settings/index
```
Returns current settings in JSON format.

### Update Settings
```
POST /settings/update/1
```
Updates settings and creates/updates the corresponding session.

**Form Data**:
- `school_name` (required)
- `total_classes` (required)
- `school_year` (required, format: YYYY-YYYY)
- `school_address` (optional)
- `school_logo` (optional, image file)
- `contact_email` (optional)
- `contact_phone` (optional)
- `is_active` (required, values: yes/no)

**Response**:
```json
{
    "status": "success",
    "message": "Settings and session saved successfully",
    "data": {
        "id": "...",
        "school_name": "...",
        "school_year": "2024-2025",
        ...
    }
}
```

## Models

### SettingsModel
- **Table**: `tz_web_setting`
- **Purpose**: Stores school configuration data
- **Key Fields**: school_name, school_year, total_classes, contact information

### SessionModel
- **Table**: `sessions`
- **Purpose**: Stores academic session/year information
- **Key Fields**: session, is_active
- **Methods**:
  - `getCurrentSession()` - Get the active session
  - `createSession()` - Create a new session
  - `updateSession()` - Update an existing session

## Controller Logic

### SettingsController::update()

1. Receives POST data from form
2. Handles file upload for school logo (if provided)
3. Validates input data
4. Starts database transaction
5. Deletes existing settings record (if exists)
6. Inserts new settings record
7. Extracts `school_year` from settings data
8. Checks if session with same year exists:
   - **Exists**: Updates session
   - **Not exists**: Creates new session
9. Commits transaction
10. Returns success response

### SettingsController::settingsPage()

1. Fetches current settings from database
2. Passes settings data to view
3. View displays form with pre-populated fields
4. If no data exists, form shows empty fields with placeholders

## View (Settings.php)

### Features

1. **Pre-populated Form Fields**:
   - Uses PHP's `esc()` function to safely display existing data
   - Falls back to empty string or default value if no data exists

2. **Year Picker**:
   - Custom year selection interface
   - Generates year ranges (e.g., 2024-2025)

3. **File Upload**:
   - Handles school logo upload
   - Displays current logo if exists
   - Shows preview using base64 encoded image

4. **Real-time Feedback**:
   - Success alert on successful save
   - Error alert on validation or save failure
   - Auto-reload after successful save to show updated data

5. **Responsive Design**:
   - Mobile-friendly interface
   - Sidebar navigation
   - Form validation

## Usage Example

### First Time Setup

```
1. Navigate to: http://your-domain/settings
2. Fill in the form:
   - School Name: "Dar es Salaam Secondary School"
   - Total Classes: 20
   - School Year: 2024-2025
   - Address: "123 School Street, Dar es Salaam"
   - Contact Email: "info@school.tz"
   - Contact Phone: "+255 123 456 789"
   - Active Status: Yes
3. Click "Save Settings"
4. System creates:
   - Settings record in tz_web_setting
   - Session record with session="2024-2025" and is_active="yes"
```

### Updating School Year

```
1. Navigate to: http://your-domain/settings
2. Form shows existing data
3. Update School Year from "2024-2025" to "2025-2026"
4. Click "Save Settings"
5. System:
   - Updates settings record
   - Creates new session "2025-2026" (or updates if exists)
   - Sets new session as active
```

## Error Handling

### Validation Errors
- Required fields missing
- Invalid email format
- Invalid school year format
- Image file too large (>5MB)
- Invalid image type (only JPEG, PNG, GIF allowed)

### Database Errors
- Transaction rollback on failure
- Detailed error logging
- User-friendly error messages

### File Upload Errors
- Invalid file type
- File size exceeded
- Failed to read file data

## Security Considerations

1. **Input Validation**: All inputs are validated using CodeIgniter's validation rules
2. **XSS Prevention**: Output is escaped using `esc()` function
3. **File Upload Security**: 
   - File type validation
   - File size limits
   - Binary data storage
4. **SQL Injection Prevention**: Uses CodeIgniter's Query Builder
5. **Transaction Safety**: Database transactions ensure data consistency

## Future Enhancements

1. Multiple session management (multiple active years)
2. Session history and archiving
3. Bulk session operations
4. Session-based data filtering across the application
5. Academic calendar integration

## Troubleshooting

### Issue: Session not created
**Solution**: Check that `school_year` field is filled and in correct format (YYYY-YYYY)

### Issue: Form doesn't show existing data
**Solution**: Ensure settings record exists in database and `settingsPage()` method is fetching data correctly

### Issue: Transaction fails
**Solution**: Check database logs for specific error. Ensure both `tz_web_setting` and `sessions` tables exist and have correct structure

### Issue: Image not displaying
**Solution**: Verify that `school_logo` is stored as BLOB in database and view is correctly encoding to base64

## Conclusion

The Settings and Session integration provides a seamless way to manage school configuration and academic year information. By automatically creating/updating sessions when settings are saved, the system ensures consistency and reduces manual data entry.