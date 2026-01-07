<!-- Popup de Cookies -->
<aside class="cookie-consent" id="cookieConsent" role="dialog" aria-labelledby="cookie-title" aria-describedby="cookie-description" aria-live="polite">
    <div class="cookie-consent__container">
        <div class="cookie-consent__content">
            <h2 id="cookie-title" class="cookie-consent__title">Cookies e Privacidade</h2>
            <p id="cookie-description" class="cookie-consent__text">
                Nós usamos cookies e outras tecnologias semelhantes para melhorar a sua experiência em nossos serviços, 
                personalizar publicidade e recomendar conteúdo de seu interesse. Ao utilizar nossos serviços, você está 
                ciente dessa funcionalidade. Informamos ainda que atualizamos nosso 
                <a href="<?php echo esc_url(home_url('/politica-de-privacidade')); ?>" class="cookie-consent__link" aria-label="Ler Aviso de Privacidade">Aviso de Privacidade</a>. 
                Conheça nosso Portal da Privacidade e veja o nosso 
                <a href="<?php echo esc_url(home_url('/politica-de-privacidade')); ?>" class="cookie-consent__link" aria-label="Ler novo Aviso">novo Aviso</a>.
            </p>
        </div>
        
        <div class="cookie-consent__actions">
            <button 
                type="button" 
                class="cookie-consent__button cookie-consent__button--accept" 
                id="acceptCookies"
                aria-label="Aceitar cookies e fechar aviso"
            >
                Aceitar
            </button>
            <button 
                type="button" 
                class="cookie-consent__button cookie-consent__button--reject" 
                id="rejectCookies"
                aria-label="Rejeitar cookies e fechar aviso"
            >
                Rejeitar
            </button>
        </div>
    </div>
</aside>

<script>
(function() {
    'use strict';
    
    const cookieConsent = document.getElementById('cookieConsent');
    const acceptBtn = document.getElementById('acceptCookies');
    const rejectBtn = document.getElementById('rejectCookies');
    
    // Verificar se já existe consentimento
    function checkConsent() {
        const consent = localStorage.getItem('cookieConsent');
        if (consent) {
            cookieConsent.setAttribute('aria-hidden', 'true');
            cookieConsent.style.display = 'none';
        } else {
            cookieConsent.setAttribute('aria-hidden', 'false');
            cookieConsent.style.display = 'block';
        }
    }
    
    // Aceitar cookies
    function acceptCookies() {
        localStorage.setItem('cookieConsent', 'accepted');
        localStorage.setItem('cookieConsentDate', new Date().toISOString());
        hideCookieConsent();
    }
    
    // Rejeitar cookies
    function rejectCookies() {
        localStorage.setItem('cookieConsent', 'rejected');
        localStorage.setItem('cookieConsentDate', new Date().toISOString());
        hideCookieConsent();
    }
    
    // Esconder popup
    function hideCookieConsent() {
        cookieConsent.classList.add('cookie-consent--hidden');
        cookieConsent.setAttribute('aria-hidden', 'true');
        
        setTimeout(function() {
            cookieConsent.style.display = 'none';
        }, 300);
    }
    
    // Event listeners
    if (acceptBtn) {
        acceptBtn.addEventListener('click', acceptCookies);
    }
    
    if (rejectBtn) {
        rejectBtn.addEventListener('click', rejectCookies);
    }
    
    // Verificar ao carregar
    checkConsent();
})();
</script>
