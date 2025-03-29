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


