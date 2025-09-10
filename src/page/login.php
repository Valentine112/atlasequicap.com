<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlasequicap Broker</title>
    <link rel="stylesheet" href="form/style.css">
</head>

<body>
    <div class="neural-background">
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
        <div class="neural-node"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="ai-glow"></div>

            <div class="login-header">
                <div class="ai-logo">
                    <div class="logo-core">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <div class="logo-rings">
                        <div class="ring ring-1"></div>
                        <div class="ring ring-2"></div>
                        <div class="ring ring-3"></div>
                    </div>
                </div>
                <h1>Atlasequicap</h1>
                <p>Welcome back</p>
            </div>

            <form class="login-form" id="loginForm" novalidate data-type="login" onsubmit="event.preventDefault()">
                <div id="message"></div>
                <div class="smart-field" data-field="email">
                    <div class="field-background"></div>
                    <input type="email" id="email" name="email" required autocomplete="email">
                    <label for="email">Email Address</label>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="smart-field" data-field="password">
                    <div class="field-background"></div>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <label for="password">Password</label>
                    <button type="button" class="smart-toggle" id="passwordToggle" aria-label="Toggle password visibility" onclick="togglePassword(this)">
                        <svg class="toggle-show" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M9 3.75c-3.15 0-5.85 1.89-7.02 4.72a.75.75 0 000 .56c1.17 2.83 3.87 4.72 7.02 4.72s5.85-1.89 7.02-4.72a.75.75 0 000-.56C14.85 5.64 12.15 3.75 9 3.75zM9 12a3 3 0 110-6 3 3 0 010 6z" fill="currentColor"/>
                        </svg>
                        <svg class="toggle-hide" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M3.53 2.47a.75.75 0 00-1.06 1.06l11 11a.75.75 0 101.06-1.06l-2.82-2.82c.8-.67 1.5-1.49 2.04-2.42a.75.75 0 000-.56C12.58 4.84 10.89 3.75 9 3.75c-.69 0-1.36.1-2 .29L3.53 2.47zM7.974 5.847L10.126 8a1.5 1.5 0 01-2.126-2.126l-.026-.027z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="ai-indicator">
                        <div class="ai-pulse"></div>
                    </div>
                    <div class="field-completion"></div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <div class="form-options">
                    <label class="smart-checkbox">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="checkbox-ai">
                            <div class="checkbox-core"></div>
                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none">
                                <path d="M1 5l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="checkbox-text">Remember this session</span>
                    </label>
                    <a href="forgot" class="neural-link">Reset password</a>
                </div>

                <button type="submit" class="neural-button" onclick="log(this, 'login')">
                    <div class="button-bg"></div>
                    <span class="button-text">Login now</span>
                    <div class="button-loader">
                        <div class="neural-spinner">
                            <div class="spinner-segment"></div>
                            <div class="spinner-segment"></div>
                            <div class="spinner-segment"></div>
                        </div>
                    </div>
                    <div class="button-glow"></div>
                </button>
            </form>

            <div class="auth-separator">
                <div class="separator-line"></div>
                <span class="separator-text">or</span>
                <div class="separator-line"></div>
            </div>

            <div class="signup-section">
                <span>New to Atlasequicap? </span>
                <a href="signup" class="neural-signup">Join us now</a>
            </div>
        </div>
    </div>

    <?php include "src/template/quick-notice.php"; ?>

    <script src="form/shared/js/form-utils.js"></script>
    <script src="form/script.js"></script>
    <script src="src/assets/js/main.js"></script>
</body>

</html>