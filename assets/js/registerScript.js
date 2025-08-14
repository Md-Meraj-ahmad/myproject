// Show/hide login and sign up forms based on selected tab
function showTab(tab) {
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const loginTab = document.getElementById('login-tab');
    const signupTab = document.getElementById('signup-tab');
    
    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        signupForm.classList.add('hidden');
        loginTab.classList.add('active');
        signupTab.classList.remove('active');
    } else {
        signupForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        signupTab.classList.add('active');
        loginTab.classList.remove('active');
    }
}
// Set initial state to show the login form
showTab('login');

// Login validation function
function validateLogin() {
    let isValid = true;
    const email = document.getElementById('login-email');
    const password = document.getElementById('login-password');
    const emailError = email.nextElementSibling;
    const passwordError = password.nextElementSibling;

    // Reset errors
    email.classList.remove('border-red-500');
    password.classList.remove('border-red-500');
    emailError.classList.add('hidden');
    passwordError.classList.add('hidden');

    // Validate email
    if (!email.value || !email.value.includes('@')) {
        email.classList.add('border-red-500');
        emailError.classList.remove('hidden');
        isValid = false;
    }

    // Validate password
    if (!password.value) {
        password.classList.add('border-red-500');
        passwordError.classList.remove('hidden');
        isValid = false;
    }

    return isValid;
}

// Signup validation function
function validateSignup() {
    let isValid = true;
    const name = document.getElementById('signup-name');
    const email = document.getElementById('signup-email');
    const address = document.getElementById('signup-address');
    const password = document.getElementById('signup-password');
    const confirmPassword = document.getElementById('signup-confirm-password');
    const nameError = name.nextElementSibling;
    const emailError = email.nextElementSibling;
    const addressError = address.nextElementSibling;
    const passwordError = password.nextElementSibling;
    const confirmPasswordError = confirmPassword.nextElementSibling;

    // Reset errors
    name.classList.remove('border-red-500');
    email.classList.remove('border-red-500');
    address.classList.remove('border-red-500');
    password.classList.remove('border-red-500');
    confirmPassword.classList.remove('border-red-500');
    nameError.classList.add('hidden');
    emailError.classList.add('hidden');
    addressError.classList.add('hidden');
    passwordError.classList.add('hidden');
    confirmPasswordError.classList.add('hidden');

    // Validate name
    if (!name.value) {
        name.classList.add('border-red-500');
        nameError.classList.remove('hidden');
        isValid = false;
    }

    // Validate email
    if (!email.value || !email.value.includes('@')) {
        email.classList.add('border-red-500');
        emailError.classList.remove('hidden');
        isValid = false;
    }

    // Validate address
    if (!address.value) {
        address.classList.add('border-red-500');
        addressError.classList.remove('hidden');
        isValid = false;
    }

    // Validate password
    if (!password.value) {
        password.classList.add('border-red-500');
        passwordError.classList.remove('hidden');
        isValid = false;
    }

    // Validate confirm password
    if (password.value !== confirmPassword.value) {
        confirmPassword.classList.add('border-red-500');
        confirmPasswordError.classList.remove('hidden');
        isValid = false;
    }

    return isValid;
}