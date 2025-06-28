document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const field = document.getElementById('password-field');
    const realPassword = document.getElementById('real-password');

    if (toggle && passwordInput) {
        // Edit page: toggle input type
        toggle.addEventListener('click', function () {
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        });
    } else if (toggle && field && realPassword) {
        // Show page: toggle text content
        toggle.addEventListener('click', function () {
            if (field.textContent.includes('•')) {
                field.textContent = realPassword.value;
            } else {
                field.textContent = '••••••••';
            }
        });
    }
});