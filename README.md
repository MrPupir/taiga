# Taiga

Welcome to Taiga — a website for booking rooms and managing hotel services. This project was created as part of a coursework to demonstrate the basic features and functionality needed for successful hotel management.

## Description

Taiga provides a user-friendly interface for both users and administrators:

- **Room Booking**: Users can browse available rooms, select and book them (test function, no payment required).
- **Admin Panel**: Administrators can manage rooms, view bookings, and update information.
- **Responsive Design**: The site is optimized for various devices, including mobile phones and tablets.
- **Intuitive Interface**: Simple and easy access to all site functions.

## Requirements

- **PHP** version 7.8 or higher
- **MySQL** version 8.0 or higher

## Installation

1. Clone the repository to your local machine:
    ```bash
    git clone https://github.com/your-username/taiga.git
    ```

2. Navigate to the project directory:
    ```bash
    cd taiga
    ```

3. Set up the database:
    - Create a MySQL database named `taiga`.
    - Import the `taiga.sql` file into the database.

4. Edit the `include/init.php` file to connect to your database:
    ```php
    $db = new DB([
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'db' => 'taiga',
    ]);
    ```

5. Start the local server:
    ```bash
    php -S localhost:8000
    ```

6. Open your browser and go to `http://localhost:8000`.

## Usage

### For Users

1. Visit the main page of the site.
2. Browse available rooms and select the one that suits you.
3. Click the "Book" button, fill in the necessary fields, and confirm the booking.

### For Administrators

1. Go to the admin login page (`http://localhost:8000/admin`).
2. Enter your credentials to access the admin panel.
3. Manage rooms and bookings through the user-friendly interface.

## Contribution

If you want to contribute to the Taiga project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push the changes to your fork (`git push origin feature-branch`).
5. Create a pull request to the main repository.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for details.