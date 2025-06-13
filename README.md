## 🧰 Setup Instructions
1. Install [XAMPP](https://www.apachefriends.org/index.html)
3. Copy this project folder into `htdocs/` (e.g., `Xampp/htdocs`)
4. Start Apache and MySQL in XAMPP
5. Visit http://localhost/phpmyadmin/
6. Click on databases and create a new database (e.g my database name is 'pharmacy')
7. Click on Import, then import SQL file i provided in the project(`pharmacy` database)
9. Visit `http://localhost/project/index.php`  (I named my folder name as 'project' but you can change it)


 **1 Introduction**
 The Pharmacy Management System is a web-based application developed to streamline
 the operations of a pharmacy, including managing medicines, customers, suppliers, and
 invoices. The system aims to minimize manual work and improve efficiency through a
 secure and user-friendly platform. It enables administrators to handle customer details,
 medicine stock, suppliers, and sales records with ease.
 
 **2 Objectives**
 • To build a user-friendly interface for managing pharmacy data
 • To automate the billing and stock-keeping process
 • To reduce errors and increase efficiency in pharmacy management
 • To provide secure login and user session handling
 • To maintain complete and accurate sales and inventory records
 
 **3 Tools and Technologies Used**
 • Frontend: HTML5, CSS3, Bootstrap 5
 • Backend: PHP
 • Database: MySQL (XAMPP)
 • IDE: Visual Studio Code
 
 **4 System Modules**
 4.1 Customers Module
 • Add, update, delete, and view customer information
 • Fields: Name, Contact Number, Address, Password
 4.2 Medicines Module
 • Manage medicine records
 • Fields: Name, Generic Name, Manufacturer, Supplier ID
 4.3 Suppliers Module
 • Manage supplier data including name, contact, and medicine supply history
 4.4 Invoices Module
 • Maintains sales records referencing customer and medicine data
 • Automatically calculates total price
 • Adjusts medicine stock levels
 4.5 Admin Dashboard
 • Centralized admin dashboard for navigating all modules
 • Restricted to authenticated administrators
 • Includes control over medicines, stock, customers, suppliers, and invoice reports
 
 **5 Database Design**
 The MySQL database consists of multiple related tables:
 • admin– Stores admin login credentials
 • customers– Stores customer profiles
 • medicines– Stores general medicine details
 • medicines
 • invoice
 stock– Maintains stock quantity and expiry data
 medicines– Records invoices and sale details
 • suppliers– Contains supplier records and references
 
 **6 Features**
 • Responsive Admin Dashboard for smooth navigation
 • CRUD operations for customers, medicines, and suppliers
 • Secure login system using PHP sessions
 • Invoice generation with date, time, and total amount
 • Automatic stock deduction on invoice creation
 • Alerts for medicines that are low in stock or near expiry

 **7 Conclusion**
The Pharmacy Management System developed is functional and meets the basic opera
tional requirements of a small-scale pharmacy. It reduces manual tasks, ensures secure
data handling, and streamlines billing and stock tracking. With the proposed improve
ments, the system can scale and support advanced features for a professional pharmacy
environment.
