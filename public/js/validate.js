document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.querySelector('form[action="/auth/register"]');
    const loginForm = document.querySelector('form[action="/auth/login"]');
    const uploadForm = document.querySelector('form[action="/upload"]');

    if (registerForm) attachValidation(registerForm, validateRegister);
    if (loginForm) attachValidation(loginForm, validateLogin);
    if (uploadForm) attachValidation(uploadForm, validateUpload);
});

function attachValidation(form, validatorFn) {
    form.addEventListener('submit', function (e) {
        clearErrors(form);
        const errors = validatorFn(form);

        if (Object.keys(errors).length > 0) {
            e.preventDefault(); // block the request
            showErrors(form, errors);
        }
        // no errors -> nothing to do, the browser sends the request as normal
    });
}

function clearErrors(form) {
    form.querySelectorAll('.js-error').forEach(el => el.remove());
}

function showErrors(form, errors) {
    for (const field in errors) {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) continue;
        const span = document.createElement('span');
        span.className = 'error-text js-error';
        span.textContent = errors[field];
        input.insertAdjacentElement('afterend', span);
    }
}

function validateRegister(form) {
    const errors = {};
    const get = name => form.querySelector(`[name="${name}"]`).value.trim();

    if (!get('first_name')) errors.first_name = 'First name is required.';
    if (!get('last_name')) errors.last_name = 'Last name is required.';

    const email = get('email');
    if (!email) {
        errors.email = 'Email is required.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.email = 'Please enter a valid email address.';
    }

    const password = get('password');
    const confirmPassword = get('confirm_password');
    const passwordErrors = getPasswordErrors(password);

    if (passwordErrors.length) errors.password = passwordErrors[0];
    if (password !== confirmPassword) errors.confirm_password = 'Passwords do not match.';

    return errors;
}

function validateLogin(form) {
    const errors = {};
    const get = name => form.querySelector(`[name="${name}"]`).value.trim();

    const email = get('email');
    if (!email) {
        errors.email = 'Email is required.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.email = 'Please enter a valid email address.';
    }

    if (!get('password')) errors.password = 'Password is required.';

    return errors;
}

function validateUpload(form) {
    const errors = {};
    const title = form.querySelector('[name="title"]').value.trim();
    const fileInput = form.querySelector('[name="photo"]');

    if (!title) errors.title = 'Photo title is required.';
    if (!fileInput.files || fileInput.files.length === 0) {
        errors.photo = 'Please choose a photo to upload.';
    }

    return errors;
}

function getPasswordErrors(password) {
    const errors = [];
    if (password.length < 8) errors.push('Password must be at least 8 characters long.');
    if (!/[A-Z]/.test(password)) errors.push('Password must contain at least one uppercase letter.');
    if (!/[a-z]/.test(password)) errors.push('Password must contain at least one lowercase letter.');
    if (!/[0-9]/.test(password)) errors.push('Password must contain at least one number.');
    if (!/[\W]/.test(password)) errors.push('Password must contain at least one special character.');
    return errors;
}