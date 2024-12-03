function validateForm() {
    let firstName = document.getElementById('first-name').value;
    let lastName = document.getElementById('last-name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirm-password').value;

    let firstNameError = document.getElementById('first-name-error');
    let lastNameError = document.getElementById('last-name-error');
    let emailError = document.getElementById('email-error');
    let passwordError = document.getElementById('password-error');
    let confirmPasswordError = document.getElementById('confirm-password-error');
    let submitButton = document.getElementById("sign-up-button");
    
    let valid = true;

    // Reset error messages
    firstNameError.textContent = '';
    lastNameError.textContent = '';
    emailError.textContent = '';
    passwordError.textContent = '';
    confirmPasswordError.textContent = ''; 

    // Name validation
    var namePattern = /^[a-zA-Z .]+$/;
    if (!namePattern.test(firstName)) {
        firstNameError.textContent = "This field allows only letters, spaces, and dots for upper and lower case letters";
        return false;
    } else {
        firstNameError.textContent = "";
    }

    if (!namePattern.test(lastName)) {
        lastNameError.textContent = "This field allows only letters, spaces, and dots for upper and lower case letters";
        return false;
    } else {
        lastNameError.textContent = "";
    }

    //Email validation
    let emailPattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    email = email.toLowerCase();
    if (!emailPattern.test(email)) {
        emailError.textContent = "Please enter a valid email address";
        return false;
    } else {
        emailError.textContent = "";
    }

    // Validate password
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{8,15}$/;
    if (!passwordPattern.test(password)) {
        passwordError.textContent = 'Password must be 8-15 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        return false;
    } else {
        passwordError.textContent = "";
    }

    // Validate passwords match
    if (password !== confirmPassword) {
        confirmPasswordError.textContent = 'Passwords do not match.';
        return false;
    } else {
        confirmPasswordError.textContent = "";
    }

    return true;
}

// Handle the form subbmission
// document.getElementById("sign-up").addEventListener("submit", function(event) {
//     event.preventDefault();
//     // Reset the form if it is valid
//     if(validateForm()) {
//         document.location.href;
//     }
// });
