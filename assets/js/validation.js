document.addEventListener('DOMContentLoaded', function () {
    const serviceForm = document.getElementById('serviceForm');

    if (serviceForm) {
        serviceForm.addEventListener('submit', function (e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const price = document.getElementById('price').value.trim();
            const image = document.getElementById('image');

            let errors = [];

            if (title === '') {
                errors.push('Title is required.');
            }

            if (description === '') {
                errors.push('Description is required.');
            }

            if (price === '' || isNaN(price) || Number(price) <= 0) {
                errors.push('Price must be a valid number greater than 0.');
            }

            if (image && image.files.length > 0) {
                const file = image.files[0];
                const allowed = ['image/jpeg', 'image/png'];

                if (!allowed.includes(file.type)) {
                    errors.push('Only JPG and PNG files are allowed.');
                }

                if (file.size > 2 * 1024 * 1024) {
                    errors.push('Image size must be less than 2MB.');
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }
});