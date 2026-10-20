
const signupForm = document.getElementById("signup-form");
const loginForm = document.getElementById("login-form");
const otpForm = document.getElementById("otp-form");

const signupTab = document.getElementById("signup-tab");
const loginTab = document.getElementById("login-tab");
const backButton = document.getElementById("back-to-login");

const message = document.getElementById("message");
const formTitle = document.getElementById("form-title");
const formDescription = document.getElementById("form-description");

function showMessage(text, error = false) {
    message.textContent = text;
    message.style.color = error ? "#b42318" : "#187344";
}

function showForm(name) {
    signupForm.hidden = name !== "signup";
    loginForm.hidden = name !== "login";
    otpForm.hidden = name !== "otp";

    signupTab.classList.toggle("active", name === "signup");
    loginTab.classList.toggle("active", name !== "signup");

    signupTab.setAttribute("aria-selected", String(name === "signup"));
    loginTab.setAttribute("aria-selected", String(name !== "signup"));

    if (name === "signup") {
        formTitle.textContent = "Create your account";
        formDescription.textContent =
            "Enter your name and email to get started.";
    } else if (name === "login") {
        formTitle.textContent = "Welcome Back!";
        formDescription.textContent =
            "Enter your registered name and email to receive an OTP.";
    } else {
        formTitle.textContent = "Verify your email";
        formDescription.textContent =
            "Enter the six-digit code sent to your email.";
    }
}

async function postForm(url, data) {
    const response = await fetch(url, {
        method: "POST",
        body: data
    });

    const result = await response.json();
    return { response, result };
}

// Switch tabs.
signupTab.addEventListener("click", () => {
    showForm("signup");
    showMessage("");
});

loginTab.addEventListener("click", () => {
    showForm("login");
    showMessage("");
});

// SIGNUP: account creation only; no OTP sent here.
signupForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const button = signupForm.querySelector('button[type="submit"]');
    button.disabled = true;
    showMessage("Creating your account...");

    try {
        const { response, result } =
            await postForm("signup.php", new FormData(signupForm));

        if (!response.ok || !result.success) {
            showMessage(result.message || "Account creation failed.", true);
            return;
        }

        signupForm.reset();
        showForm("login");
        showMessage("Account created successfully! Please go to Login.");
    } catch (error) {
        showMessage("Could not contact the server. Try again.", true);
    } finally {
        button.disabled = false;
    }
});

// LOGIN: check the account and send OTP.
loginForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const button = document.getElementById("send-otp-button");
    button.disabled = true;
    showMessage("Checking account and sending OTP...");

    try {
        const { response, result } =
            await postForm("send_otp.php", new FormData(loginForm));

        if (!response.ok || !result.success) {
            showMessage(result.message || "Could not send OTP.", true);
            return;
        }

        otpForm.dataset.email =
            loginForm.querySelector('[name="email"]').value.trim();

        otpForm.reset();
        showForm("otp");
        showMessage(result.message);
        document.getElementById("otp-code").focus();

    } catch (error) {
        showMessage("Could not contact the server. Try again.", true);
    } finally {
        button.disabled = false;
    }
});

// OTP VERIFICATION.
otpForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const otp = document.getElementById("otp-code").value.trim();

    if (!/^\d{6}$/.test(otp)) {
        showMessage("Enter the six-digit OTP.", true);
        return;
    }

    const data = new FormData();
    data.append("email", otpForm.dataset.email || "");
    data.append("otp", otp);

    const button = document.getElementById("verify-otp-button");
    button.disabled = true;
    showMessage("Verifying OTP...");

    try {
        const { response, result } =
            await postForm("verify_otp.php", data);

        if (!response.ok || !result.success) {
            showMessage(result.message || "OTP verification failed.", true);
            return;
        }

        window.location.href = result.redirect || "dashboard.php";
    } catch (error) {
        showMessage("Could not verify the OTP. Try again.", true);
    } finally {
        button.disabled = false;
    }
});

backButton.addEventListener("click", () => {
    showForm("login");
    showMessage("Enter your registered name and email.");
});
