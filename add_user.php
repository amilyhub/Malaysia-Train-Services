<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - Malaysia Train Services</title>
    <style>
        :root {
            --pastel-pink: #ffd6e7;       /* Soft pink */
            --pastel-green: #d6f5e3;      /* Soft green */
            --pink-accent: #ff85a2;       /* Pink untuk button & accent */
            --green-accent: #7ecf9b;      /* Green untuk hover state */
            --text-dark: #4a4a4a;         /* Text color */
            --text-light: #888888;        /* Text secondary */
            --border-light: #e8d4e0;      /* Border color */
            --white: #ffffff;
            --background: #fef7fa;        /* Light pink background */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            min-height: 100vh;
            color: var(--text-dark);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 2px solid var(--border-light);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text-dark);
            border: 2px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--pastel-pink);
            border-color: var(--pink-accent);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--green-accent) 0%, #5bb982 100%);
            color: var(--white);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(126, 207, 155, 0.3);
        }

        .page-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--text-dark);
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .card {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
            padding-left: 8px;
            border-left: 3px solid var(--pink-accent);
        }

        .required::after {
            content: " *";
            color: #ff6b8b;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--pastel-pink);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: var(--white);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--pink-accent);
            box-shadow: 0 0 0 4px rgba(255, 133, 162, 0.1);
            background-color: #fffdfe;
        }

        .form-control::placeholder {
            color: #c4a8b7;
            font-style: italic;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-col {
            flex: 1;
            min-width: 250px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid var(--pastel-pink);
        }

        footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
            color: var(--text-light);
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .error-message {
            color: #ff6b8b;
            font-size: 12px;
            margin-top: 6px;
            display: none;
            padding-left: 8px;
        }

        .form-control.error {
            border-color: #ff6b8b;
            background-color: #fff5f7;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .nav-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">Malaysia Train Services</div>
            <div class="nav-buttons">
                <a href="manage_users.php" class="btn btn-secondary">Manage Users</a>
                <button onclick="exportUsers()" class="btn btn-primary">Export Users</button>
            </div>
        </header>

        <h1 class="page-title">Add New User</h1>

        <div class="card">
            <form id="addUserForm" action="manage_users.php" method="POST">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="firstName" class="required">First Name</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Enter first name" required>
                            <div class="error-message" id="firstNameError">Please enter a valid first name</div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="lastName" class="required">Last Name</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Enter last name" required>
                            <div class="error-message" id="lastNameError">Please enter a valid last name</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="username" class="required">Username</label>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
                            <div class="error-message" id="usernameError">Please enter a valid username</div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                            <div class="error-message" id="passwordError">Password must be at least 6 characters</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" name="department" class="form-control">
                                <option value="">Select Department</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Operations">Operations</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                    <div class="error-message" id="phoneError">Please enter a valid phone number</div>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="window.location.href='manage_users.php'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2023 Malaysia Train Services. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // Form submission handling
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => {
                msg.style.display = 'none';
            });
            
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.classList.remove('error');
            });
            
            let isValid = true;
            
            // Basic form validation
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (!firstName || firstName.trim().length < 2) {
                document.getElementById('firstNameError').style.display = 'block';
                document.getElementById('firstName').classList.add('error');
                isValid = false;
            }
            
            if (!lastName || lastName.trim().length < 2) {
                document.getElementById('lastNameError').style.display = 'block';
                document.getElementById('lastName').classList.add('error');
                isValid = false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('email').classList.add('error');
                isValid = false;
            }
            
            if (!username || username.trim().length < 3) {
                document.getElementById('usernameError').style.display = 'block';
                document.getElementById('username').classList.add('error');
                isValid = false;
            }
            
            if (!password || password.length < 6) {
                document.getElementById('passwordError').style.display = 'block';
                document.getElementById('password').classList.add('error');
                isValid = false;
            }
            
            // Phone validation (optional)
            const phone = document.getElementById('phone').value;
            if (phone && !validatePhone(phone)) {
                document.getElementById('phoneError').style.display = 'block';
                document.getElementById('phone').classList.add('error');
                isValid = false;
            }
            
            if (isValid) {
                // If validation passes, submit the form
                const submitBtn = document.querySelector('.btn-success');
                submitBtn.textContent = 'Adding User...';
                submitBtn.disabled = true;
                
                // Simulate form submission
                setTimeout(() => {
                    alert('🎀 User added successfully!');
                    submitBtn.textContent = 'Add User';
                    submitBtn.disabled = false;
                    
                    // In a real application, you would submit the form here
                    // this.submit();
                }, 1500);
            }
        });

        // Export function
        function exportUsers() {
            // In a real application, this would make an API call to export user data
            alert('Export functionality would be implemented here. This would typically download a CSV or Excel file of all users.');
            
            // For demonstration, we'll create a simple CSV download
            const csvContent = "data:text/csv;charset=utf-8,First Name,Last Name,Email,Username,Department,Role\n";
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "users_export.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Helper functions
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            const re = /^[\+]?[0-9\s\-\(\)]{8,}$/;
            return re.test(phone);
        }

        // Add some interactive effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>