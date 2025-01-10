document.addEventListener('DOMContentLoaded', () => {
    const raceSelect = document.getElementById('race');
    const customRace = document.getElementById('custom_race');
    const classSelect = document.getElementById('class');
    const customClass = document.getElementById('custom_class');

    raceSelect.addEventListener('change', () => {
        customRace.style.display = raceSelect.value === 'Other' ? 'block' : 'none';
    });

    classSelect.addEventListener('change', () => {
        customClass.style.display = classSelect.value === 'Other' ? 'block' : 'none';
    });
});
