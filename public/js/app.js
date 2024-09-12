function passwordVisibility(button) {
    var passwordField = button.parentElement.previousElementSibling.previousElementSibling;
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        button.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
    } else {
        passwordField.type = 'password';
        button.innerHTML = '<i class="fa-solid fa-eye"></i>';
    }
}

function formatPhoneNumber(input) {
    var phoneNumber = input.value.replace(/\D/g, '');
    var formattedPhoneNumber = '';
    for (var i = 0; i < phoneNumber.length; i++) {
        if (i > 0 && i % 3 === 0) {
            formattedPhoneNumber += ' ';
        }
        formattedPhoneNumber += phoneNumber[i];
    }

    input.value = formattedPhoneNumber.trim();
}
