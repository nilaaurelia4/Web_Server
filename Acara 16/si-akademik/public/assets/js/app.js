document.addEventListener('DOMContentLoaded', function () {

    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(function (alert) {

        setTimeout(function () {

            if (alert.classList.contains('show')) {

                const closeButton =
                    alert.querySelector('.btn-close');

                if (closeButton) {
                    closeButton.click();
                }
            }

        }, 4000);

    });

});