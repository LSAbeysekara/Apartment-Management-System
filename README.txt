# 🏢 Apartment Management System

A robust web-based platform designed to streamline the management of tenants, apartments, payments, and maintenance requests. This system enhances communication between administrators and residents while providing tools for tracking utilities, expenses, and entry people.

## ✨ Features

- **Apartment Management**
  - Keep detailed records of all apartments
  - Track occupancy status and availability
  - Maintain apartment specifications and features

- **Payment Handling**
  - Process and track rental payments
  - Generate automated payment reminders
  - Maintain payment history and receipts

- **Maintenance Requests**
  - Submit and track maintenance tickets
  - Assign tasks to maintenance staff
  - Monitor request status and completion

- **Entry People Tracking**
  - Log visitor details and duration of stay
  - Monitor service personnel access
  - Generate visitor reports

- **Utility Tracking**
  - Monitor electricity, water, and gas usage
  - Generate utility bills
  - Track consumption patterns

- **Expense Management**
  - Record building maintenance costs
  - Track operational expenses
  - Generate expense reports

- **Communication System**
  - Send announcements to tenants
  - Handle complaints and feedback
  - Maintain communication history

- **Automated Notifications**
  - Payment due reminders
  - Maintenance update alerts
  - Important announcements

## ⚙️ Installation Guide

### Prerequisites

- XAMPP or any local server environment (Apache, MySQL)
- Git (optional, for cloning the repository)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/LSAbeysekara/Apartment-Management-System.git
   ```
   Alternatively, download the ZIP file and extract it.

2. **Set Up the Database**
   - Open phpMyAdmin
   - Create a new database named `apartment_ms`
   - Import `apartment_ms.sql` from the database folder

3. **Configure the Application**
   ```php
   // Update includes/config.php with your database credentials
   $host = "localhost";      // Server host
   $username = "root";       // Database username
   $password = "";          // Database password
   $database = "apartment_ms"; // Database name
   ```

4. **Deploy to Web Server**
   - Copy the project folder to your web server's root directory
   - For XAMPP: `C:\xampp\htdocs\apartment-management-system`

5. **Start Services**
   - Launch XAMPP Control Panel
   - Start Apache and MySQL services

6. **Access the Application**
   ```
   http://localhost/apartment-management-system
   ```

## 🛠️ Technologies Used

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL

## 📂 Project Structure

```
apartment-management-system/
├── assets/           # Static resources
│   ├── css/         # Stylesheets
│   ├── js/          # JavaScript files
│   └── images/      # Image resources
├── includes/         # Configuration files
├── modules/         # Core functionality
│   ├── tenants/
│   ├── apartments/
│   ├── payments/
│   └── maintenance/
├── views/           # HTML templates
├── database/        # SQL files
└── index.php        # Entry point
```

## 🚀 Future Enhancements

- Multi-language support for international users
- SMS/Email notification system integration
- Mobile application development
- Advanced reporting and analytics
- Payment gateway integration
- Document management system
- Smart home integration capabilities

## 📝 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 👥 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For support, email support@apartmentms.com or join our Slack channel.
