-- tz_exams: Stores exam details
CREATE TABLE `tz_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_name` varchar(100) NOT NULL COMMENT 'e.g., Midterm, Final Exam',
  `exam_date` date DEFAULT NULL COMMENT 'Date of the exam',
  `session_id` int(11) NOT NULL COMMENT 'References the sessions table',
  `is_active` enum('yes', 'no') DEFAULT 'yes' COMMENT 'yes = active, no = inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `tz_exams_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_exam_classes: Links exams to classes
CREATE TABLE `tz_exam_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL COMMENT 'References the tz_exams table',
  `class_id` int(11) NOT NULL COMMENT 'References the classes table',
  `session_id` int(11) NOT NULL COMMENT 'References the sessions table for consistency',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `tz_exam_classes_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_classes_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_exam_subjects: Links subjects to exams
CREATE TABLE `tz_exam_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL COMMENT 'e.g., Mathematics, Physics',
  `max_marks` int(11) DEFAULT NULL COMMENT 'Maximum marks for this subject',
  `passing_marks` int(11) DEFAULT NULL COMMENT 'Minimum passing marks',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  CONSTRAINT `tz_exam_subjects_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_exam_results: Stores exam results
CREATE TABLE `tz_exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL COMMENT 'References the classes table',
  `session_id` int(11) NOT NULL COMMENT 'References the sessions table',
  `total_points` int(11) DEFAULT NULL,
  `division` varchar(5) DEFAULT NULL COMMENT 'I, II, III, IV, O',
  `division_description` varchar(50) DEFAULT NULL COMMENT 'Excellent, Very Good, Good, Satisfactory, Fail',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `tz_exam_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_results_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_results_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tz_exam_subject_marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL COMMENT 'References the classes table, must match tz_exam_classes',
  `session_id` int(11) NOT NULL COMMENT 'References the sessions table',
  `exam_subject_id` int(11) NOT NULL,
  `marks_obtained` int(11) DEFAULT NULL COMMENT 'Marks scored by the student in this subject',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  KEY `exam_subject_id` (`exam_subject_id`),
  CONSTRAINT `tz_exam_subject_marks_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_subject_marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_subject_marks_ibfk_3` FOREIGN KEY (`exam_subject_id`) REFERENCES `tz_exam_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_subject_marks_ibfk_4` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_exam_subject_marks_ibfk_5` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_gener



CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `admission_no` varchar(100) DEFAULT NULL,
  `roll_no` varchar(100) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `rte` varchar(20) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `cast` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `school_house_id` int(11) DEFAULT NULL,
  `blood_group` varchar(200) NOT NULL,
  `hostel_room_id` int(11) DEFAULT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `samagra_id` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `guardian_is` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(150) NOT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_email` varchar(100) DEFAULT NULL,
  `father_pic` varchar(200) NOT NULL,
  `mother_pic` varchar(200) NOT NULL,
  `guardian_pic` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `previous_school` text DEFAULT NULL,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `measurement_date` date DEFAULT NULL,
  `dis_reason` int(11) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  `dis_note` text NOT NULL,
  `app_key` text DEFAULT NULL,
  `parent_app_key` text DEFAULT NULL,
  `disable_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `student_session` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `hostel_room_id` int(11) DEFAULT NULL,
  `vehroute_id` int(10) DEFAULT NULL,
  `route_pickup_point_id` int(11) DEFAULT NULL,
  `transport_fees` float(10,2) NOT NULL DEFAULT 0.00,
  `fees_discount` float(10,2) NOT NULL DEFAULT 0.00,
  `is_leave` int(1) NOT NULL DEFAULT 0,
  `is_active` varchar(255) DEFAULT 'no',
  `is_alumni` int(11) NOT NULL,
  `default_login` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


- tz_alevel_combinations: Stores A-level combinations (e.g., PCM, CBG)
CREATE TABLE `tz_alevel_combinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combination_code` varchar(10) NOT NULL COMMENT 'e.g., PCM, CBG',
  `combination_name` varchar(100) NOT NULL COMMENT 'e.g., Physics-Chemistry-Math',
  `is_active` enum('yes', 'no') DEFAULT 'yes' COMMENT 'yes = active, no = inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `combination_code` (`combination_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_alevel_combination_subjects: Links combinations to subjects
CREATE TABLE `tz_alevel_combination_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combination_id` int(11) NOT NULL COMMENT 'References tz_alevel_combinations',
  `subject_name` varchar(100) NOT NULL COMMENT 'e.g., Physics, General Studies',
  `subject_type` enum('major', 'additional') NOT NULL COMMENT 'major = core subject, additional = GS or extra',
  `is_active` enum('yes', 'no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `combination_id` (`combination_id`),
  CONSTRAINT `tz_alevel_combination_subjects_ibfk_1` FOREIGN KEY (`combination_id`) REFERENCES `tz_alevel_combinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_student_alevel_combinations: Assigns combinations to a class, section, and session
CREATE TABLE `tz_student_alevel_combinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combination_id` int(11) NOT NULL COMMENT 'References tz_alevel_combinations',
  `class_id` int(11) NOT NULL COMMENT 'References classes table, e.g., Form 5 or 6',
  `section_id` int(11) DEFAULT NULL COMMENT 'References sections table, optional',
  `session_id` int(11) NOT NULL COMMENT 'References sessions table',
  `is_active` enum('yes', 'no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `combination_id` (`combination_id`),
  KEY `class_id` (`class_id`),
  KEY `section_id` (`section_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `tz_student_alevel_combinations_ibfk_1` FOREIGN KEY (`combination_id`) REFERENCES `tz_alevel_combinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_student_alevel_combinations_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_student_alevel_combinations_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tz_student_alevel_combinations_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_alevel_exam_combinations: Links exams to combinations to prevent mix-ups
CREATE TABLE `tz_alevel_exam_combinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL COMMENT 'References tz_exams table',
  `combination_id` int(11) NOT NULL COMMENT 'References tz_alevel_combinations',
  `class_id` int(11) NOT NULL COMMENT 'References classes table, e.g., Form 5 or 6',
  `session_id` int(11) NOT NULL COMMENT 'References sessions table',
  `is_active` enum('yes', 'no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `combination_id` (`combination_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `tz_alevel_exam_combinations_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_combinations_ibfk_2` FOREIGN KEY (`combination_id`) REFERENCES `tz_alevel_combinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_combinations_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_combinations_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- tz_alevel_exam_results: Stores A-level-specific results
CREATE TABLE `tz_alevel_exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `combination_id` int(11) NOT NULL COMMENT 'References tz_alevel_combinations',
  `total_points` int(11) DEFAULT NULL COMMENT 'Sum of points from major subjects',
  `division` varchar(5) DEFAULT NULL COMMENT 'A-level division, e.g., I, II, III',
  `division_description` varchar(50) DEFAULT NULL COMMENT 'e.g., Excellent, Good',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  KEY `combination_id` (`combination_id`),
  CONSTRAINT `tz_alevel_exam_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_results_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_results_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_exam_results_ibfk_5` FOREIGN KEY (`combination_id`) REFERENCES `tz_alevel_combinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Table to store A-level subject marks
CREATE TABLE `tz_alevel_subject_marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL COMMENT 'References the tz_exams table',
  `student_id` int(11) NOT NULL COMMENT 'References the students table',
  `class_id` int(11) NOT NULL COMMENT 'References the classes table, e.g., Form 5 or 6',
  `session_id` int(11) NOT NULL COMMENT 'References the sessions table',
  `combination_id` int(11) NOT NULL COMMENT 'References the tz_alevel_combinations table',
  `subject_id` int(11) NOT NULL COMMENT 'References the tz_alevel_combination_subjects table',
  `marks_obtained` int(11) DEFAULT NULL COMMENT 'Marks scored by the student in this subject',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`),
  KEY `session_id` (`session_id`),
  KEY `combination_id` (`combination_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `tz_alevel_subject_marks_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `tz_exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_subject_marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_subject_marks_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_subject_marks_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_subject_marks_ibfk_5` FOREIGN KEY (`combination_id`) REFERENCES `tz_alevel_combinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tz_alevel_subject_marks_ibfk_6` FOREIGN KEY (`subject_id`) REFERENCES `tz_alevel_combination_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

