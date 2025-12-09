<?php
/**
 * TTS Player - Template do Player de Text-to-Speech
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
        <h3 class="tts-player-title">Ouça esta postagem</h3>
    </div>

    <p id="tts-status" class="tts-player-status">Carregando...</p>

    <div class="tts-player-controls">
        <button id="tts-play" class="tts-btn" aria-label="Reproduzir postagem">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
            Reproduzir
        </button>

        <button id="tts-pause" class="tts-btn" aria-label="Pausar reprodução">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
            Pausar
        </button>

        <button id="tts-stop" class="tts-btn" aria-label="Parar reprodução" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M6 6h12v12H6z"/>
            </svg>
            Parar
        </button>
    </div>

    <div class="tts-player-settings">
        <div class="tts-setting-group">
            <label for="tts-speed" class="tts-setting-label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14">
                    <path fill="currentColor" d="M20.38 8.57l-1.23 1.85a8 8 0 0 1-.22 7.58H5.07A8 8 0 0 1 15.58 6.85l1.85-1.23A10 10 0 0 0 3.35 19a2 2 0 0 0 1.72 1h13.85a2 2 0 0 0 1.74-1 10 10 0 0 0-.27-10.44zm-9.79 6.84a2 2 0 0 0 2.83 0l5.66-8.49-8.49 5.66a2 2 0 0 0 0 2.83z"/>
                </svg>
                Velocidade
            </label>
            <div class="tts-speed-control">
                <input 
                    type="range" 
                    id="tts-speed" 
                    min="0.5" 
                    max="2" 
                    step="0.1" 
                    value="1"
                    aria-label="Controle de velocidade de reprodução"
                >
                <span id="tts-speed-value" class="tts-speed-value" aria-live="polite">1.0x</span>
            </div>
        </div>

        <div class="tts-setting-group">
            <label for="tts-voice" class="tts-setting-label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14">
                    <path fill="currentColor" d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
                </svg>
                Voz
            </label>
            <select id="tts-voice" aria-label="Selecione a voz para reprodução">
                <option>Carregando vozes...</option>
            </select>
        </div>
    </div>

    <div class="tts-progress-container" role="progressbar" aria-label="Progresso da reprodução" aria-valuemin="0" aria-valuemax="100">
        <div id="tts-progress" class="tts-progress-bar"></div>
    </div>

    <div class="tts-player-info">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"/>
            </svg>
            Use Espaço para play/pause
        </span>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M9 5v2h6.59L4 18.59 5.41 20 17 8.41V15h2V5z"/>
            </svg>
            Esc para parar
        </span>
    </div>
</div>
