window.addEventListener('load', () => {
    const modal = document.querySelector('.pain-modal');
    if (!modal) return;

    const title = modal.querySelector('.pain-modal-title');
    let selectedZone = '';
    const painZones = {};

    // Update the title when the modal opens
    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const zone = button.textContent.trim();
        selectedZone = button.dataset.zone;

        title.textContent = 'Intensité de la douleur : ' + zone;
    });

    // Find all intensity buttons
    const intensityButtons = modal.querySelectorAll('.pain-intensity');

    // Listen for clicks on each button
    for (const button of intensityButtons) {
        button.addEventListener('click', () => {
            const intensity = button.dataset.intensity;
            painZones[selectedZone] = Number(intensity);
            console.log(painZones);
        });
    }
});
