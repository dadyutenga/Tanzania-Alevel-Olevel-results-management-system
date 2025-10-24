-- Create extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE school_result TO tz_results_user;
GRANT ALL ON SCHEMA public TO tz_results_user;

-- Set timezone
SET timezone = 'Africa/Dar_es_Salaam';
