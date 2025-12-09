/**
 * TTS Player - Text-to-Speech Player usando Web Speech API
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 */

(function() {
    'use strict';

    // Verificar se o navegador suporta Web Speech API
    if (!('speechSynthesis' in window)) {
        console.warn('Text-to-Speech não suportado neste navegador');
        return;
    }

    const synth = window.speechSynthesis;
    let utterance = null;
    let isPaused = false;
    let currentText = '';
    let voices = [];

    // Elementos do DOM
    const player = document.getElementById('tts-player');
    if (!player) return;

    const playBtn = document.getElementById('tts-play');
    const pauseBtn = document.getElementById('tts-pause');
    const stopBtn = document.getElementById('tts-stop');
    const speedControl = document.getElementById('tts-speed');
    const speedValue = document.getElementById('tts-speed-value');
    const progressBar = document.getElementById('tts-progress');
    const statusText = document.getElementById('tts-status');
    const voiceSelect = document.getElementById('tts-voice');

    /**
     * Carrega as vozes disponíveis
     */
    function loadVoices() {
        voices = synth.getVoices();
        
        // Filtrar vozes em português
        const ptVoices = voices.filter(voice => 
            voice.lang.startsWith('pt') || 
            voice.lang.startsWith('pt-BR')
        );

        // Popular o select de vozes
        if (voiceSelect) {
            voiceSelect.innerHTML = '';
            
            if (ptVoices.length > 0) {
                ptVoices.forEach((voice, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = `${voice.name} (${voice.lang})`;
                    option.dataset.voiceName = voice.name;
                    option.dataset.voiceLang = voice.lang;
                    voiceSelect.appendChild(option);
                });
            } else {
                // Se não houver vozes em português, usar todas
                voices.forEach((voice, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = `${voice.name} (${voice.lang})`;
                    option.dataset.voiceName = voice.name;
                    option.dataset.voiceLang = voice.lang;
                    voiceSelect.appendChild(option);
                });
            }
        }

        return ptVoices.length > 0 ? ptVoices : voices;
    }

    /**
     * Extrai o texto do conteúdo da postagem
     */
    function extractContent() {
        const contentElement = document.querySelector('.entry-content');
        if (!contentElement) {
            statusText.textContent = 'Conteúdo não encontrado';
            return '';
        }

        // Clone o elemento para não afetar o original
        const clone = contentElement.cloneNode(true);

        // Remove elementos que não devem ser lidos
        const elementsToRemove = clone.querySelectorAll('script, style, iframe, img, figure, .wp-caption');
        elementsToRemove.forEach(el => el.remove());

        // Pega apenas o texto
        let text = clone.textContent || clone.innerText;
        
        // Limpa espaços múltiplos e quebras de linha
        text = text.replace(/\s+/g, ' ').trim();
        
        // Limita a 5000 caracteres para não sobrecarregar
        if (text.length > 5000) {
            text = text.substring(0, 5000) + '...';
        }

        return text;
    }

    /**
     * Cria o utterance para falar
     */
    function createUtterance(text) {
        utterance = new SpeechSynthesisUtterance(text);
        
        // Selecionar voz
        const selectedVoiceIndex = voiceSelect ? parseInt(voiceSelect.value) : 0;
        const availableVoices = voices.length > 0 ? voices : synth.getVoices();
        
        if (availableVoices[selectedVoiceIndex]) {
            utterance.voice = availableVoices[selectedVoiceIndex];
        } else {
            // Tentar encontrar uma voz em português
            const ptVoice = availableVoices.find(voice => voice.lang.startsWith('pt'));
            if (ptVoice) {
                utterance.voice = ptVoice;
            }
        }

        // Configurações
        utterance.rate = parseFloat(speedControl.value);
        utterance.pitch = 1;
        utterance.volume = 1;
        utterance.lang = 'pt-BR';

        // Event handlers
        utterance.onstart = function() {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            stopBtn.disabled = false;
            statusText.textContent = 'Reproduzindo...';
            player.classList.add('playing');
        };

        utterance.onend = function() {
            resetPlayer();
            statusText.textContent = 'Reprodução concluída';
            progressBar.style.width = '100%';
            
            setTimeout(() => {
                progressBar.style.width = '0%';
                statusText.textContent = 'Pronto para reproduzir';
            }, 2000);
        };

        utterance.onerror = function(event) {
            console.error('Erro no TTS:', event);
            resetPlayer();
            statusText.textContent = 'Erro na reprodução';
        };

        utterance.onpause = function() {
            statusText.textContent = 'Pausado';
        };

        utterance.onresume = function() {
            statusText.textContent = 'Reproduzindo...';
        };

        // Simular progresso (Web Speech API não tem evento de progresso nativo)
        utterance.onboundary = function(event) {
            if (event.name === 'word' && currentText.length > 0) {
                const progress = (event.charIndex / currentText.length) * 100;
                progressBar.style.width = Math.min(progress, 100) + '%';
            }
        };
    }

    /**
     * Reset do player
     */
    function resetPlayer() {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
        stopBtn.disabled = true;
        isPaused = false;
        player.classList.remove('playing', 'paused');
    }

    /**
     * Play
     */
    function play() {
        if (isPaused && utterance) {
            // Retomar
            synth.resume();
            isPaused = false;
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            player.classList.remove('paused');
            player.classList.add('playing');
            statusText.textContent = 'Reproduzindo...';
        } else {
            // Iniciar nova reprodução
            currentText = extractContent();
            
            if (!currentText) {
                statusText.textContent = 'Nenhum conteúdo para reproduzir';
                return;
            }

            // Cancelar qualquer fala anterior
            synth.cancel();
            
            createUtterance(currentText);
            synth.speak(utterance);
            progressBar.style.width = '0%';
        }
    }

    /**
     * Pause
     */
    function pause() {
        if (synth.speaking && !isPaused) {
            synth.pause();
            isPaused = true;
            playBtn.style.display = 'inline-block';
            pauseBtn.style.display = 'none';
            player.classList.remove('playing');
            player.classList.add('paused');
            statusText.textContent = 'Pausado';
        }
    }

    /**
     * Stop
     */
    function stop() {
        synth.cancel();
        resetPlayer();
        progressBar.style.width = '0%';
        statusText.textContent = 'Reprodução interrompida';
        
        setTimeout(() => {
            statusText.textContent = 'Pronto para reproduzir';
        }, 2000);
    }

    /**
     * Atualizar velocidade
     */
    function updateSpeed() {
        const speed = parseFloat(speedControl.value);
        speedValue.textContent = speed.toFixed(1) + 'x';
        
        // Se estiver reproduzindo, atualizar em tempo real
        if (synth.speaking && utterance) {
            utterance.rate = speed;
        }
    }

    /**
     * Inicialização
     */
    function init() {
        // Carregar vozes
        loadVoices();
        
        // Chrome precisa deste evento
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        }

        // Event listeners
        if (playBtn) {
            playBtn.addEventListener('click', play);
        }

        if (pauseBtn) {
            pauseBtn.addEventListener('click', pause);
        }

        if (stopBtn) {
            stopBtn.addEventListener('click', stop);
        }

        if (speedControl) {
            speedControl.addEventListener('input', updateSpeed);
        }

        if (voiceSelect) {
            voiceSelect.addEventListener('change', function() {
                // Se estiver tocando, parar e avisar para tocar novamente
                if (synth.speaking) {
                    stop();
                    statusText.textContent = 'Voz alterada. Clique em Play para ouvir com a nova voz.';
                }
            });
        }

        // Teclas de atalho
        document.addEventListener('keydown', function(e) {
            // Apenas se o player estiver visível
            if (player.offsetParent === null) return;

            // Space: Play/Pause
            if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                if (synth.speaking && !isPaused) {
                    pause();
                } else {
                    play();
                }
            }

            // Escape: Stop
            if (e.code === 'Escape' && synth.speaking) {
                e.preventDefault();
                stop();
            }
        });

        // Limpar ao sair da página
        window.addEventListener('beforeunload', function() {
            if (synth.speaking) {
                synth.cancel();
            }
        });

        // Status inicial
        statusText.textContent = 'Pronto para reproduzir';
    }

    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
