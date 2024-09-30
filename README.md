# TigerCommerce

Polytechnic 2024 batches multivendor eCommerce in PHP-MySQL

## Project Description

TigerCommerce is a multivendor eCommerce platform developed for the Polytechnic 2024 batches. The application is built using PHP and MySQL, with additional front-end technologies including CSS, Hack, and JavaScript.

## Folder Structure

The following directories are required for uploads:
- `uploads/`
  - `profile_pics/`
  - `vendor/`
    - `logo/`
    - `products/`

## Recent Commits

Here are some recent changes in the repository:
1. [312a10b](https://github.com/asamamun/tigercommerce/commit/312a10ba4f2ff8c69eb23eb04b29ca1ea55f9678): "Refactor place-order.php."
2. [4b1cd78](https://github.com/asamamun/tigercommerce/commit/4b1cd789a748e4790631c4f3ac4874ab68093196): "Elements in alphabetical order by their display text"
3. [c5cb919](https://github.com/asamamun/tigercommerce/commit/c5cb91900c5a88d8e536f4fab83b86e1b0f964bd): "Update README.md"

For more details, you can view the [full commit history](https://github.com/asamamun/tigercommerce/commits).

## Technical Details

### Languages Used
- **PHP**: 83%
- **CSS**: 8.7%
- **Hack**: 5.2%
- **JavaScript**: 3.1%

### Key Features
- **Multivendor Support**: Allows multiple vendors to sell their products on a single platform.
- **User Management**: Supports user registration and profile management.
- **Product Management**: Vendors can add, update, and remove products.
- **Order Processing**: Facilitates the order placement and tracking process.

## Getting Started

### Prerequisites
- PHP 7.x or higher
- MySQL 5.x or higher
- Web server (e.g., Apache or Nginx)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/asamamun/tigercommerce.git
   ```
2. **Navigate to the project directory:**

    ```bash
    cd tigercommerce
    ```

3. **Set up the database:**

    - Create a MySQL database.
    - Import the provided SQL file (`database/tigercommerce.sql`) into the database.

4. **Configure the application:**

    - Rename `.env.example` to `.env`.
    - Update the database credentials in the `.env` file:

    ```plaintext
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

5. **Install PHP dependencies using Composer:**

    ```bash
    composer install
    ```

6. **Start the server and navigate to the application URL:**

    For local development, you can use XAMPP or any other local server:

    - Place the project folder inside the `htdocs` directory of XAMPP.
    - Start the Apache and MySQL servers from the XAMPP control panel.
    - Navigate to `http://localhost/tigercommerce` in your browser.

7. **Configure root, uploadpath, companyname, logo, hostname in src/helpers.php:**   

## Screenshots

### Homepage
[![Homepage](https://github.com/asamamun/tigercommerce/blob/master/screenshots/homepage.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/homepage.png)

### Admin Carousel
[![Admin Carousel](https://github.com/asamamun/tigercommerce/blob/master/screenshots/admin-carousel.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/admin-carousel.png)

### Products
[![Products](https://github.com/asamamun/tigercommerce/blob/master/screenshots/products.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/products.png)

### Orders
[![Orders](https://github.com/asamamun/tigercommerce/blob/master/screenshots/orders.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/orders.png)

### Product Details
[![Product Details](https://github.com/asamamun/tigercommerce/blob/master/screenshots/details.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/details.png)

### Cart
[![Cart](https://github.com/asamamun/tigercommerce/blob/master/screenshots/cart.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/cart.png)

### Invoice
[![Invoice](https://github.com/asamamun/tigercommerce/blob/master/screenshots/invoice.png)](https://github.com/asamamun/tigercommerce/blob/master/screenshots/invoice.png)


## Contributing

Contributions are welcome! Please submit pull requests with clear descriptions and ensure that they follow the project's coding standards.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.