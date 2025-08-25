<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Basic To do App</title>
    <link rel="stylesheet" href="/css/authentication/registration/style.css">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Create an Account</h1>
                <p>Join us to get started with your todo list</p>
            </div>
            
            <form action="/register" method="POST" class="register-form">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required />
                </div>

                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required />
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required />
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">Create Account</button>
                </div>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="/login">Sign in here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>