
Built by https://www.blackbox.ai

---

# TRISNIME Auto Update Scraper

## Project Overview
TRISNIME Auto Update Scraper is a PHP-based web application designed for automated scraping and updating of content. It leverages CodeIgniter as its framework to manage workflows for scraping videos, specifically extracting data from platforms like Animasu. The application offers an admin interface, cron job automation, and comprehensive monitoring features to ensure smooth operation.

## Installation

### Prerequisites
Ensure you have the following installed:
- PHP (>= 5.3)
- MySQL
- Composer (for managing dependencies)

### Database Setup
1. Create a new database in MySQL.
2. Run the following command to initialize the database:
    ```bash
    mysql -u <username> -p <database_name> < scraper_database_update.sql
    ```

### File Permissions
Ensure the following permissions are set for the necessary files and directories:
```bash
chmod 755 app/application/controllers/admin/Scraper.php
chmod 755 app/application/libraries/Scraper_lib.php
chmod 755 app/application/models/Auto_update_model.php
mkdir -p public/storage/thumbnails && chmod 755 public/storage/thumbnails
```

### Clone the Repository
Clone this repository to your local machine:
```bash
git clone <repository_url>
cd <project_directory>
```

## Usage

### Running the Application
You can access the application through a web server or by using PHP's built-in server:
```bash
php -S localhost:8000
```
Navigate to [http://localhost:8000](http://localhost:8000) to access the application.

### Configuring the Application
Edit the `config.php` file to update your database credentials:
```php
$sk["DB_HOST"] = "localhost"; // Database host
$sk["DB_USER"] = "your_username"; // Database username
$sk["DB_PASS"] = "your_password"; // Database password
$sk["DB_NAME"] = "your_database"; // Database name
```

### Setting Up Cron Job for Automation
To automate the scraper updates, add the following line to your crontab:
```bash
0,30 * * * * /usr/bin/php /path/to/project/index.php admin/scraper/auto_update
```

## Features
- **Auto Update ON/OFF**: Complete toggle system for managing scraper updates.
- **Embed Video Support**: Capability to extract videos from Animasu.
- **Real-time Dashboard**: Modern interface for managing scrape activities.
- **Error Handling**: Comprehensive error recovery mechanisms.
- **Performance Monitoring**: Tracking of memory usage and execution time.
- **Database Health Management**: Built-in cleanup and maintenance functionalities.
- **Cron Job Support**: Scheduled automated scraping tasks.

## Dependencies
This project primarily utilizes the CodeIgniter framework for MVC architecture. The dependencies specific to this project can be managed through Composer, if necessary. Please ensure that your PHP installation meets the minimum requirements specified by CodeIgniter.

## Project Structure
The project's directory structure is as follows:
```
/project-root
│
├── app/
│   └── application/
│       ├── controllers/
│       │   └── admin/
│       │       └── Scraper.php        # Main controller for scraping tasks
│       ├── libraries/
│       │   └── Scraper_lib.php       # Library for scraping logic
│       └── models/
│           └── Auto_update_model.php  # Model for database interactions
│
├── theme/
│   └── backend/
│       ├── scraper/
│       │   └── index.php               # Admin interface
│       └── index.php                   # Updated menu
│
├── config.php                          # Database configuration
├── cron_auto_update.php                # Automation script for cron job
├── scraper_database_update.sql         # SQL script for initializing the database
└── index.php                           # Entry point for the application
```

## Conclusion
The **TRISNIME Auto Update Scraper** is designed for efficient and reliable content scraping with robust features tailored for modern web applications. Deploy with confidence and streamline your content management tasks!