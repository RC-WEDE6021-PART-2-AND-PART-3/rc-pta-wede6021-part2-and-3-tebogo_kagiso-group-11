================================================================================
                              PASTIMES
                  Pre-loved Branded Clothing Marketplace
================================================================================


1. HOW TO OPEN AND RUN THE PROJECT
================================================================================

Step 1: Install XAMPP
- Download XAMPP from https://www.apachefriends.org/
- Install and run the XAMPP Control Panel

Step 2: Start XAMPP Services
- Click "Start" for Apache
- Click "Start" for MySQL

Step 3: Copy Project Files
- Copy the "don" folder to: C:\xampp\htdocs\

Step 4: Set Up the Database
- Open http://localhost/phpmyadmin
- Click "Import" tab
- Select "clothingstore.sql" from the project folder
- Click "Go"

Step 5: Run the Application
- Open your browser
- Go to: http://localhost/don/


2. REQUIRED SOFTWARE
================================================================================

- XAMPP (includes Apache, PHP, and MySQL)
- Web Browser (Chrome, Firefox, or Edge)
- No additional software required


3. HOW TO SET UP THE DATABASE
================================================================================

Option 1: Using phpMyAdmin
- Open http://localhost/phpmyadmin
- Click "New" and create database: clothingstore
- Click "Import" tab
- Select "clothingstore.sql" from the project folder
- Click "Go"

Option 2: Using createTable.php
- Go to: http://localhost/don/createTable.php
- The script will create all tables automatically


4. WHERE THE DATABASE FILE IS LOCATED
================================================================================

Location: C:\xampp\htdocs\don\clothingstore.sql

The file is inside the project folder "don".


5. USERNAMES AND PASSWORDS FOR TESTING
================================================================================

Admin Access:
- Username: admin
- Password: admin123

Sample Users:
- Username: nomsa_k     | Password: password123 | Status: Approved
- Username: kagiso_m    | Password: password123 | Status: Approved
OR
YOU CAN REGISTER YOUR OWN DETAILS AND LOGIN AS AN ADMIN TO APPROVE.

6. IMPORTANT NOTES FOR THE MARKER
================================================================================

Note 1: User Registration & Approval
- New users register with "Pending" status
- Admin must approve users before they can log in
- Admin login: admin / admin123

Note 2: Product Images
- Images are stored in the "images/" folder
- Some images have spaces in file names (e.g., "black hoodie.jpeg")
- The system handles spaces in URLs automatically

Note 3: Cart & Delivery
- Free delivery on orders over R500
- Delivery fee is R50 for orders under R500
- Cart uses PHP sessions

Note 4: Seller Requests
- Users submit items for sale via the "Sell" page
- Admin approves/rejects requests
- Approved items are added to the product catalog

Note 5: Database Tables
- tbladmin: Admin credentials
- tbluser: User accounts
- tblclothes: Product catalog
- tblorders: Customer orders
- tblmessages: User-admin messages
- tblseller_requests: Seller listing requests

================================================================================
                    © 2024 Pastimes - All Rights Reserved
================================================================================
