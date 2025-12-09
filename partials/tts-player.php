<?php
/**
 * TTS Player - Template do Player de Text-to-Speech (Versão Compacta)
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="tts-player" class="tts-player" role="region" aria-label="Player de áudio para ouvir a postagem">
    <div class="tts-player-header">
        <svg class="tts-player-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
        </svg>
        <span class="tts-player-title">Ouça esta postagem</span>
        <span id="tts-status" class="tts-player-status">Carregando...</span>
    </div>

    <div class="tts-player-controls">
        <button id="tts-play" class="tts-btn" aria-label="Reproduzir postagem" title="Reproduzir (Espaço)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>

        <button id="tts-pause" class="tts-btn" aria-label="Pausar reprodução" title="Pausar (Espaço)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
        </button>

        <button id="tts-stop" class="tts-btn" aria-label="Parar reprodução" title="Parar (Esc)" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M6 6h12v12H6z"/>
            </svg>
        </button>

        <div class="tts-speed-control">
            <input 
                type="range" 
                id="tts-speed" 
                min="0.5" 
                max="2" 
                step="0.1" 
                value="1"
                aria-label="Velocidade"
                title="Velocidade"
            >
            <span id="tts-speed-value" class="tts-speed-value" aria-live="polite">1.0x</span>
        </div>
    </div>

    <div class="tts-progress-container" role="progressbar" aria-label="Progresso da reprodução" aria-valuemin="0" aria-valuemax="100">
        <div id="tts-progress" class="tts-progress-bar"></div>
    </div>
</div>
