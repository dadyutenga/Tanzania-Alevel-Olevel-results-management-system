# MinIO Integration and Settings Update - Summary

## Overview
This update integrates MinIO object storage for school logos and improves the settings/school management system to ensure proper user-school relationships and session management.

## Changes Made

### 1. MinIO Configuration (`app/Config/Minio.php`)
- Created MinIO configuration file with credentials from your credentials.json
- Configured endpoint: `20.163.1.176:9000`
- Access Key: `91Z059h0qV3GV3LNN2E3`
- Default bucket: `data`

### 2. MinIO Service Library (`app/Libraries/MinioService.php`)
Created a comprehensive MinIO service with methods:
- `uploadFile()` - Upload files to MinIO bucket
- `downloadFile()` - Download files from MinIO
- `deleteFile()` - Delete files from MinIO
- `fileExists()` - Check if file exists
- `getObjectUrl()` - Get public URL of object
- `getPresignedUrl()` - Generate temporary access URLs
- `listObjects()` - List files in a path/prefix

### 3. SettingsModel Updates (`app/Models/SettingsModel.php`)
Added new methods:
- `getSchoolByUserId($userId)` - Get school by created_by user
- `userHasSchool($userId)` - Check if user has a school entry
- `getSchoolById($schoolId)` - Get school by ID
- `createSchool($schoolData)` - Create new school
- `updateSchool($schoolId, $schoolData)` - Update existing school

Removed `school_id` from allowed fields since the main `id` field serves as the school identifier.

### 4. SessionModel Updates (`app/Models/SessionModel.php`)
Already had `school_id` in allowed fields. Added new methods:
- `getSessionsBySchool($schoolId)` - Get all sessions for a school
- `getSessionBySchoolAndYear($schoolId, $sessionYear)` - Get specific session
- Updated `createSession()` and `updateSession()` to handle school_id

### 5. SettingsController Updates (`app/Controllers/SettingsController.php`)
Complete overhaul of the `update()` method:

**New Logic Flow:**
1. Get current user from session
2. Check if user already has a school entry
3. If file uploaded:
   - Validate file (type, size)
   - Delete old logo from MinIO if updating
   - Upload new logo to MinIO `data` bucket at path `schools/logos/{uuid}.{ext}`
   - Store MinIO URL in database
4. If user has existing school:
   - Update the existing school record
5. If user has no school:
   - Create new school with generated UUID
6. Handle session:
   - Check if session exists for this school and year
   - Update existing session or create new one
   - Link session to school via `school_id` (UUID)
7. Store school_id and school_year in user's session

**Key Features:**
- Automatic school creation for new users
- School updates for existing users
- MinIO integration for logo storage
- Session linked to school via UUID
- Proper transaction handling

### 6. Settings View Updates (`app/Views/Settings.php`)
- Removed hardcoded ID from update URL
- Changed from `/settings/update/1` to `/settings/update`

### 7. Database Schema
- Settings table (`tz_web_setting`): Uses UUID for `id` (serves as school_id)
- Sessions table: Has `school_id` field (CHAR 36) referencing schools

### 8. Composer Dependencies
Added AWS SDK for PHP: `aws/aws-sdk-php: ^3.0`

## How It Works

### For New Users:
1. User fills in school settings form
2. Uploads school logo
3. Logo is uploaded to MinIO at `data/schools/logos/{uuid}.jpg`
4. New school record created with UUID
5. New session record created linked to school UUID
6. User can now access their school settings

### For Existing Users:
1. User updates school settings
2. If new logo uploaded:
   - Old logo deleted from MinIO
   - New logo uploaded to MinIO
3. Existing school record updated
4. Session updated or created for new year
5. All linked via school UUID

## MinIO File Structure
```
data/
└── schools/
    └── logos/
        ├── {school-uuid-1}.jpg
        ├── {school-uuid-2}.png
        └── {school-uuid-3}.gif
```

## Next Steps

1. **Install Dependencies:**
   ```bash
   composer install
   ```

2. **Run Migrations:**
   ```bash
   php spark migrate
   ```

3. **Test MinIO Connection:**
   - Ensure MinIO server at `20.163.1.176:9000` is accessible
   - Verify bucket `data` exists or will be auto-created

4. **Update Routes (if needed):**
   ```php
   $routes->post('settings/update', 'SettingsController::update');
   ```

5. **Test the Flow:**
   - New user creates school
   - Existing user updates school
   - Verify logos are stored in MinIO
   - Verify sessions are linked to schools

## Security Considerations

1. **MinIO Credentials:** Consider moving credentials to `.env` file
2. **File Upload Validation:** Already implemented (size, type)
3. **User Authentication:** Ensure user is authenticated before allowing school creation
4. **Access Control:** Consider adding role-based access (only school admins can update)

## Troubleshooting

### MinIO Connection Issues:
- Check if MinIO server is running
- Verify endpoint and port (20.163.1.176:9000)
- Check credentials are correct
- Ensure bucket 'data' exists

### File Upload Issues:
- Check PHP upload limits in php.ini
- Verify writable temp directory
- Check MinIO bucket permissions

### Session Issues:
- Verify sessions table has school_id column
- Check foreign key relationships
- Ensure UUID generation is working
