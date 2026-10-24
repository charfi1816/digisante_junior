// Update the total screen time while typing
const total = document.querySelector('.screen-total');

if (total) {
    const totalScreenInputs = document.querySelectorAll(
        'input[type="number"]');

    function updateTotal() {
        let minutes = 0;

        totalScreenInputs.forEach(function (input) {
            minutes += Number(input.value) || 0;
        });

        total.textContent = minutes + ' minutes';
    }

    totalScreenInputs.forEach(function (input) {
        input.addEventListener('input', updateTotal);
    });

    updateTotal();
}

