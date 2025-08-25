<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Basic To do App</title>
    <link rel="stylesheet" href="/css/authentication/login/style.css">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Welcome Back</h1>
                <p>Sign in to access your todo list</p>
            </div>
            
            <form action="/login" method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember_me" value="1"/>
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                    <a href="/forgot-password" class="forgot-link">Forgot password?</a>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">Sign In</button>
                </div>
                
                <div class="form-footer">
                    <p>Don't have an account? <a href="/register">Create one here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>