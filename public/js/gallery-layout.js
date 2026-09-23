document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('galleryGrid');
    const buttons = document.querySelectorAll('.layout-btn');

    if (!grid || buttons.length === 0) return;

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove all known layout classes, then add the chosen one.
            grid.classList.remove('grid-3', 'grid-4', 'list', 'slider');
            grid.classList.add(button.dataset.layout);

            // Update which button looks "active".
            buttons.forEach(b => b.classList.remove('active'));
            button.classList.add('active');
        });
    });
});