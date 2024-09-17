document.addEventListener("DOMContentLoaded", function() {
    // Smooth scroll for internal links (only those with a '#' in the href)
    document.querySelectorAll('.nav-link[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                console.error('Target not found: ' + this.getAttribute('href'));
            }
        });
    });

    // Simple form validation
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            this.querySelectorAll('input[required]').forEach(function(input) {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill out all required fields.');
            }
        });
    });
});
