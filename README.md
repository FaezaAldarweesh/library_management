## Overview
A comprehensive library management system built with Laravel. 
This application allows users to manage allows users to browse, filter, sort, and manage a collection of books,and rating this book after they borrow it,
also the system have manege to categories, borrow records, ratings , and users.

## Features
- Category Management: Organize books into categories.
- Book Management: Add, edit, delete, and list books.
- Borrow Records: Track borrowing and returning of books.
- Rating System:Users can rate books, and only users who have rated a book can update or delete their ratings.
- User Authentication: Secure user authentication and authorization.
- API Support: Access and manage book data via a RESTful API.
- 
**Installation**
  
- PHP 7.x or higher
- Composer
- Laravel 8.x or higher
- MySQL or any supported database
- Gitbash
  
**Steps:**
- Clone the repository:https://github.com/FaezaAldarweesh/library_management.git
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan serve

## API Endpoint

**category:**
- GET /api/category: Get a list of categories (requires authentication).
- POST /api/category: Add a new category (requires authentication).
- GET /api/category/{id}: Get details of a specific category (requires authentication).
- PUT /api/category/{id}: Update category details (requires authentication).
- DELETE /api/category/{id}: Delete a category (requires authentication).
  
**book:**
- GET /api/book: Get a list of book (supports with many filters) (requires authentication).
- POST /api/book: Add a new book (requires authentication).
- GET /api/book/{id}: Get details of a specific book (requires authentication).
- PUT /api/book/{id}: Update book details (requires authentication).
- DELETE /api/book/{id}: Delete a book (requires authentication).

**borrow_admin:**
- GET /api/borrow_admin: Get a list of borrow recordes (requires authentication).
- POST /api/borrow_admin: Add a new borrow recorde (requires authentication).
- GET /api/borrow_admin/{id}: Get details of a specific borrow recorde (requires authentication).
- PUT /api/borrow_admin/{id}: Update borrow recorde details (requires authentication).
- DELETE /api/borrow_admin/{id}: Delete a borrow recorde (requires authentication).

**borrowUpdatStatus:**
- PUT /api/borrowUpdatStatus/{borrow_id}: Update status borrow recorde (requires authentication).

**rating_admin:**
- GET /api/rating_admin/{rating}: Get details of all rating (requires authentication).
- DELETE /api/rating_admin/{id_rating}: Delete a rating (requires authentication).

**borrow_user:**
- GET /api/borrow_user: Get only user borrow recordes (requires authentication).
- POST /api/borrow_user: Add a new borrow recorde (requires authentication).
- GET /api/borrow_user/{id}: Get details of a specific borrow recorde (requires authentication).
- PUT /api/borrow_user/{id}: Update borrow recorde details (requires authentication).
- DELETE /api/borrow_user/{id}: Delete a borrow recorde (requires authentication).
- 
**rating_user:**
- GET /api/rating_user/{rating}: Get details of a specific rating (requires authentication).
- PUT /api/rating_user/{rating}: Update book details (only by the user who created it) (requires authentication).
- DELETE /api/rating_user/{id_rating}: Delete a rating (only by the user who created it) (requires authentication).

**rating_user:**
- POST /api/rating_user/{book}: Add a rating to a book (requires authentication).

- GET /api/all_books/: Get all books (without authentication).
- GET /api/view_book/{book_id}/: Get a specific book (without authentication).
- GET /api/all_categories/: Get all categories (without authentication).
- GET /api/view_category/{category_id}/: Get a specific book (without authentication).

## Postman documentation link:
https://documenter.getpostman.com/view/34467473/2sAXjNXqUU
in this documentation you have two variables :

- url : the link of server
- token : to change it after do sginup or login 

## Contact
For any inquiries or support, please reach out to Faeza Aldarweesh at faeza.aldarweesh@gmail.com

