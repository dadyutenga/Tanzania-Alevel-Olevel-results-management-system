# School Results Management System

A comprehensive web-based application for managing student academic results, built with CodeIgniter 4 framework. This system is designed to handle exam management, result processing, and academic performance tracking for educational institutions.

## 🎯 Overview

This School Results Management System provides a complete solution for educational institutions to manage student exam results efficiently. The system supports both O-level and A-level education systems, making it particularly suitable for Tanzanian schools and similar educational structures.

## ✨ Key Features

### Student Management
- **Student Registration & Profiles**: Complete student information management
- **Class & Section Assignment**: Organize students by classes and sections
- **Session Management**: Handle different academic years/sessions

### Exam Management
- **Exam Creation**: Set up exams with custom subjects and marking schemes
- **Subject Configuration**: Define subjects with maximum marks and passing criteria
- **Class-Exam Linking**: Associate exams with specific classes

### Results Processing
- **Individual Result Entry**: Record marks for individual students
- **Bulk Results Upload**: Import results from spreadsheet files
- **Automated Grading**: Calculate grades based on predefined criteria
- **Result Validation**: Ensure data accuracy and completeness

### Advanced Features
- **A-Level Combinations**: Manage subject combinations for advanced level students
- **PDF Report Generation**: Generate printable result slips and transcripts
- **Data Analytics**: Dashboard with performance statistics and insights
- **Excel/CSV Support**: Import/export functionality for bulk data operations

### Security & Authentication
- **User Authentication**: Secure login system using CodeIgniter Shield
- **Access Control**: Role-based permissions for different user types
- **Data Protection**: Secure handling of sensitive student information

## 🛠 Technology Stack

- **Framework**: CodeIgniter 4 (PHP 8.1+)
- **Database**: MySQL
- **Authentication**: CodeIgniter Shield
- **PDF Generation**: DOMPDF, TCPDF
- **Spreadsheet Processing**: PhpSpreadsheet
- **Data Import/Export**: League CSV
- **Additional**: Afrika's Talking API integration, Redis support

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)
- Required PHP extensions: mysqli, json, mbstring, mysqlnd, xml, intl

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/dadyutenga/School-results-management-system.git
   cd School-results-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp env .env
   ```
   Edit the `.env` file and configure your database settings:
   ```
   database.default.hostname = localhost
   database.default.database = your_database_name
   database.default.username = your_username
   database.default.password = your_password
   ```

4. **Database setup**
   - Create a new MySQL database
   - Import the SQL schema from `my.sql`
   - Run migrations if available:
     ```bash
     php spark migrate
     ```

5. **File permissions**
   ```bash
   chmod -R 755 writable/
   ```

6. **Server configuration**
   - Point your web server document root to the `public/` directory
   - Ensure mod_rewrite is enabled for Apache

## 📖 Usage

### Getting Started

1. **Access the application** through your web browser
2. **Login** using your administrator credentials
3. **Set up basic data**:
   - Configure academic sessions
   - Create classes and sections
   - Add student records

### Managing Exams

1. **Create Exams**: Define exam names, dates, and associated classes
2. **Add Subjects**: Configure subjects with marking schemes
3. **Enter Results**: Input student marks individually or via bulk upload
4. **Generate Reports**: Create PDF reports for students and administrators

### Dashboard Features

- **Performance Analytics**: View class and individual student performance
- **Exam Statistics**: Track exam completion and grade distributions
- **Quick Actions**: Fast access to common tasks like result entry

## 📊 Database Structure

The system uses a well-structured database with the following main entities:

- **Students**: Student personal and academic information
- **Classes & Sections**: Academic groupings
- **Sessions**: Academic year management
- **Exams**: Exam details and configurations
- **Subjects**: Subject definitions and marking schemes
- **Results**: Student performance data
- **A-Level Combinations**: Advanced level subject groupings

## 🧪 Testing

Run the test suite using PHPUnit:

```bash
composer test
# or
./vendor/bin/phpunit
```

For more detailed testing information, see the [testing documentation](tests/README.md).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🔧 Configuration

### Additional Settings

- **PDF Generation**: Configure PDF templates in the settings panel
- **Grading System**: Customize grade boundaries and criteria
- **User Roles**: Set up different access levels for teachers, administrators
- **SMS Integration**: Configure Afrika's Talking for result notifications

### Performance Optimization

- Enable Redis caching for improved performance
- Configure database indexing for large datasets
- Use CDN for static assets in production

## 📞 Support

For support and questions:
- Check the documentation
- Review existing issues
- Create a new issue for bugs or feature requests

## 🙏 Acknowledgments

Built with CodeIgniter 4 framework and various open-source libraries that make this system robust and feature-rich.