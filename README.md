# Library

# **Project Documentation**
# **as admin enter the page
 - email : admin@gmail.com;
 - password : admin123

# ** as member enter the page 
 - email : member@gmail.com
 - password : member123

# ** my repository link
   - repo : https://github.com/Althaf-444/Library.git
   
## **Project Setup Guide**

### **1. Prerequisites**

Before setting up the project, ensure you have the following installed on your system:

- PHP (version X.X or higher)
- MySQL or MariaDB
- A web server (e.g., Apache or Nginx)
- A code editor (optional but recommended, e.g., VS Code)

### **2. Database Setup**

1. Open your database management tool (e.g., phpMyAdmin, MySQL CLI, or Workbench).
2. Create a new database for the project.
3. Navigate to the `db` folder in the project directory.
4. Copy the contents of `table.sql` and execute it in your database to create the required tables.
5. Copy the contents of `data.sql` and execute it in your database to populate the tables with initial data.

### **3. Configuration**

1. Open the `config` folder in your project directory.
2. Locate the configuration file (`config.php` or similar).
3. Update the following details:
   - **Database Name**: Set it to the name of the database you created.
   - **Folder/Domain Name**: Update it with your project folder name or domain name.

Example:

```php
// Database Configuration
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_HOST', 'localhost');

// Base URL Configuration
define('BASE_URL', 'http://yourdomain.com/yourfolder/');
```

### **4. File Uploads Setup**

1. Ensure the `assets/uploads/` folder exists in your project directory.
2. Set proper permissions for the folder to allow file uploads.

---

## **Project Overview**

### **1. Features**

- **Borrowed Books Management**: Tracks borrowed books, calculates fines, and manages statuses like "borrowed" or "returned."
- **Fines and Payments**: Handles fine calculations, payment statuses, and payment tracking for each user.
- **File Uploads**: Allows users to upload images with proper validation and storage.
- **User Management**: Associates user data with borrowed books, payments, and fines.

### **2. Database Schema**

Refer to the `table.sql` file for details on table structures, relationships, and fields.

### **3. How It Works**

- **Borrowed Books**: When a user borrows a book, the system tracks the borrowing details and due date.
- **Fine Calculation**: Fines are calculated automatically when a book is overdue.
- **Payments**: Users can make payments, and the system updates the fine status accordingly.
- **File Uploads**: Images are validated and stored securely in the `assets/uploads/` directory.

---

## **API Endpoints or Key Files**

### **Key PHP Files**

- `index.php`: Entry point of the project.
- `borrowed_books.php`: Manages borrowed books operations.
- `payments.php`: Handles payment-related functionalities.
- `upload.php`: Manages file uploads.
- `config.php`: Stores database and project configuration.

---

## **Troubleshooting**

### **1. Database Connection Errors**

- Check the configuration file for correct database credentials.
- Ensure the database service is running.

### **2. File Upload Issues**

- Verify folder permissions for `assets/uploads/`.
- Check the file size and type restrictions in the upload script.

### **3. Missing Data**

- Ensure you executed both `table.sql` and `data.sql` scripts.
- Verify that the database name in `config.php` matches the actual database.

### **4. Common PHP Errors**

- **Undefined Variables**: Ensure all variables are declared before use.
- **File Not Found**: Check the path for included files and folders.

---

## **Future Improvements**

1. **Enhanced Fine Calculation**: Automate the fine calculations based on user activity and due dates.
2. **User Notifications**: Add email or SMS notifications for due books and payments.
3. **Detailed Logs**: Maintain logs for all operations for better debugging and auditing.

---

For any additional queries or support, please refer to the README file or contact the project team.



