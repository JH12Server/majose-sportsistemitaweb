// Authentication functions
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-hide', 'bx-show');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-show', 'bx-hide');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const userData = {
                username: formData.get('username'),
                password: formData.get('password')
            };

            // Simulate login - Replace with actual authentication
            if (userData.username && userData.password) {
                localStorage.setItem('user', JSON.stringify(userData));
                window.location.href = 'index.html';
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if (formData.get('password') !== formData.get('confirmPassword')) {
                alert('Las contraseñas no coinciden');
                return;
            }

            const userData = {
                firstName: formData.get('firstName'),
                lastName: formData.get('lastName'),
                email: formData.get('email'),
                username: formData.get('username'),
                password: formData.get('password')
            };

            // Simulate registration - Replace with actual registration
            localStorage.setItem('user', JSON.stringify(userData));
            window.location.href = 'index.html';
        });
    }
});

// Authentication check
function checkAuth() {
    const user = localStorage.getItem('user');
    if (!user) {
        window.location.href = 'login.html';
    }
    return JSON.parse(user);
}

function logout() {
    localStorage.removeItem('user');
    window.location.href = 'login.html';
}