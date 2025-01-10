// Obtener los elementos de audio
const bgAudio = document.getElementById('background-music');
const bgAudio2 = document.getElementById('background-music-2');
const playPauseButton = document.getElementById('play-pause-button');
const muteButton = document.getElementById('mute-button');
const nextButton = document.getElementById('next-button');
const volumeSlider = document.getElementById('volume-slider');

// Variable para controlar cuál pista está sonando (1 o 2)
let currentTrack = 1;

// Función para reproducir la pista de audio
function playAudio(track) {
    if (track === 1) {
        bgAudio.play();
        bgAudio2.pause();
        playPauseButton.textContent = 'Pause';
    } else {
        bgAudio2.play();
        bgAudio.pause();
        playPauseButton.textContent = 'Pause';
    }
}

// Función para pausar la pista de audio
function pauseAudio() {
    bgAudio.pause();
    bgAudio2.pause();
    playPauseButton.textContent = 'Play';
}

// Función para manejar el botón de Play/Pause
playPauseButton.addEventListener('click', () => {
    if (bgAudio.paused && bgAudio2.paused) {
        playAudio(currentTrack);
    } else {
        pauseAudio();
    }
});

// Función para silenciar o desactivar el sonido
muteButton.addEventListener('click', () => {
    if (bgAudio.muted) {
        bgAudio.muted = false;
        bgAudio2.muted = false;
        muteButton.textContent = 'Mute';
    } else {
        bgAudio.muted = true;
        bgAudio2.muted = true;
        muteButton.textContent = 'Unmute';
    }
});

// Función para cambiar de pista (track 1 o 2)
nextButton.addEventListener('click', () => {
    currentTrack = currentTrack === 1 ? 2 : 1;
    playAudio(currentTrack);
});

// Función para controlar el volumen
volumeSlider.addEventListener('input', (e) => {
    bgAudio.volume = e.target.value;
    bgAudio2.volume = e.target.value;
});

// Evento para el fin de la canción
bgAudio.addEventListener('ended', () => {
    currentTrack = currentTrack === 1 ? 2 : 1;
    playAudio(currentTrack);
});

bgAudio2.addEventListener('ended', () => {
    currentTrack = currentTrack === 1 ? 2 : 1;
    playAudio(currentTrack);
});

// Iniciar con la primera pista
playAudio(currentTrack);


// Obtener los elementos
const toggleButton = document.getElementById("toggle-btn");
const formCard = document.querySelector(".form-card");

// Alternar entre Log In y Sign Up
toggleButton.addEventListener("click", () => {
    formCard.classList.toggle("flip");
});

