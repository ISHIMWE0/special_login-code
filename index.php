
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Login | Welcome</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

<main class="auth-container">

    <section class="welcome-panel">
        <div class="welcome-content">
            <h1>Welcome Back!</h1>
            <p>Your account, your security.
                Sign up or log in securely using your email.</p>
        </div>
    </section>

    <section class="form-panel">

        <div class="brand">
            <span class="brand-icon">S</span>
            <span>Special Login</span>
        </div>

        <div class="form-heading">
            <h2 id="form-title">Create your account</h2>
            <p id="form-description">
                Enter your name and email to get started.
            </p>
        </div>

        <div class="form-tabs">
            <button type="button" id="signup-tab"
                class="tab active" aria-selected="true">Sign Up</button>
            <button type="button" id="login-tab"
                class="tab" aria-selected="false">Login</button>
        </div>

        <!-- SIGNUP FORM: KEEP THIS FOR ACCOUNT CREATION -->
        <form id="signup-form">
            <div class="input-group">
                <label for="signup-name">Full name</label>
                <input type="text" id="signup-name" name="name"
                    placeholder="Enter your full name"
                    autocomplete="name" required>
            </div>

            <div class="input-group">
                <label for="signup-email">Email address</label>
                <input type="email" id="signup-email" name="email"
                    placeholder="you@example.com"
                    autocomplete="email" required>
            </div>

            <button type="submit" class="submit-button">
                Create Account
            </button>
        </form>

        <!-- LOGIN FORM -->
        <form id="login-form" hidden>
            <div class="input-group">
                <label for="login-name">Full name</label>
                <input type="text" id="login-name" name="name"
                    placeholder="Enter your registered full name"
                    autocomplete="name" required>
            </div>

            <div class="input-group">
                <label for="login-email">Email address</label>
                <input type="email" id="login-email" name="email"
                    placeholder="Enter your registered email"
                    autocomplete="email" required>
            </div>

            <button type="submit" class="submit-button"
                id="send-otp-button">
                Send Login OTP
            </button>
        </form>

        <!-- OTP FORM: SHOWN AFTER OTP IS SENT -->
        <form id="otp-form" hidden>
            <div class="input-group">
                <label for="otp-code">Enter your 6-digit OTP</label>
                <input type="text" id="otp-code" name="otp"
                    placeholder="Enter OTP sent to your email"
                    inputmode="numeric" pattern="[0-9]{6}"
                    maxlength="6" autocomplete="one-time-code" required>
            </div>

            <button type="submit" class="submit-button"
                id="verify-otp-button">
                Verify OTP &amp; Login
            </button>

            <button type="button" id="back-to-login" class="tab">
                Back to Login
            </button>
        </form>

        <p id="message" class="message"
            role="status" aria-live="polite"></p>

        <p class="security-note">
            Your email will be used to verify your identity.
        </p>

    </section>
</main>

</body>
</html>
