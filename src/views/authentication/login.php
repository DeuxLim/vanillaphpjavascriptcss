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
                <a href="/"><h1>TaskFlow</h1></a>
                <p>Sign in to access your todo list</p>
            </div>
            
            <form action="/login" method="POST" class="login-form">
                <!-- Error Placeholder -->
                <?php if (isset($_SESSION['errors']['user'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($_SESSION['errors']['user']) ?>
                    </div>
                    <?php unset($_SESSION['errors']['user']); ?>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"/>

                    <!-- Error Placeholder -->
                    <?php if (isset($_SESSION['errors']['email'])): ?>
                        <div class="error-message">
                            <?= htmlspecialchars($_SESSION['errors']['email']) ?>
                        </div>
                        <?php unset($_SESSION['errors']['email']); ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required value="<?= htmlspecialchars($_SESSION['form_data']['password'] ?? '') ?>"/>

                    <!-- Error Placeholder -->
                    <?php if (isset($_SESSION['errors']['password'])): ?>
                        <div class="error-message">
                            <?= htmlspecialchars($_SESSION['errors']['password']) ?>
                        </div>
                        <?php unset($_SESSION['errors']['password']); ?>
                    <?php endif; ?>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="rememberMe" value="1"/>
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

                <?php unset($_SESSION['form_data']); ?>
            </form>
        </div>
    </div>
</body>
</html>