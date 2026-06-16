document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.relative button');

    toggleButtons.forEach(button => {
        const passwordIcon = button.querySelector('.fa-eye, .fa-eye-slash');
        if (!passwordIcon) return;

        button.addEventListener('click', function () {
            const container = button.closest('.relative');
            if (!container) return;

            const passwordInput = container.querySelector('input');
            if (passwordInput) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                }
            }
        });
    });
});
