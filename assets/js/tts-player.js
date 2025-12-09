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

    /**
     * Carrega as vozes disponíveis e seleciona Luciana como padrão
     */
    function loadVoices() {
        voices = synth.getVoices();
        
        // Procurar pela voz Luciana (Google pt-BR)
        const lucianaVoice = voices.find(voice => 
            voice.name.toLowerCase().includes('luciana') || 
            (voice.name.toLowerCase().includes('google') && voice.lang === 'pt-BR')
        );

        // Se não encontrar Luciana, procurar qualquer voz pt-BR
        const ptBRVoice = voices.find(voice => voice.lang === 'pt-BR' || voice.lang.startsWith('pt-BR'));

        // Definir voz padrão (preferência: Luciana > pt-BR > primeira disponível)
        const defaultVoice = lucianaVoice || ptBRVoice || voices[0];
        
        // Log para debug (pode remover depois)
        if (lucianaVoice) {
            console.log('✅ Voz Luciana encontrada:', lucianaVoice.name);
        } else if (ptBRVoice) {
            console.log('⚠️ Luciana não encontrada. Usando:', ptBRVoice.name);
        } else {
            console.log('⚠️ Nenhuma voz pt-BR encontrada. Usando primeira voz disponível');
        }

        return defaultVoice;
    }

    /**
     * Extrai o texto do conteúdo da postagem (article completo)
     */
    function extractContent() {
        // Tentar pegar o article inteiro primeiro
        let contentElement = document.querySelector('article');
        
        // Fallback para .entry-content se não encontrar article
        if (!contentElement) {
            contentElement = document.querySelector('.entry-content');
        }
        
        if (!contentElement) {
            statusText.textContent = 'Conteúdo não encontrado';
            return '';
        }

        // Clone o elemento para não afetar o original
        const clone = contentElement.cloneNode(true);

        // Remove elementos que não devem ser lidos
        const elementsToRemove = clone.querySelectorAll(
            'script, style, iframe, img, figure, svg, ' +
            '.wp-caption, .share-buttons, .post-thumbnail, ' +
            '#tts-player, nav, .adsMobile'
        );
        elementsToRemove.forEach(el => el.remove());

        // Pega apenas o texto
        let text = clone.textContent || clone.innerText;
        
        // Limpa espaços múltiplos e quebras de linha
        text = text.replace(/\s+/g, ' ').trim();
        
        // Limita a 8000 caracteres (aumentado para artigos maiores)
        if (text.length > 8000) {
            text = text.substring(0, 8000) + '...';
        }

        return text;
    }

    /**
     * Cria o utterance para falar
     */
    function createUtterance(text) {
        utterance = new SpeechSynthesisUtterance(text);
        
        // Selecionar voz Luciana ou padrão pt-BR
        const defaultVoice = loadVoices();
        
        if (defaultVoice) {
            utterance.voice = defaultVoice;
            console.log('🎙️ Usando voz:', defaultVoice.name);
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
