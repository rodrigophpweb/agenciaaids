document.addEventListener('DOMContentLoaded', function() {
    const btnContrast = document.querySelector('.btnContrast');
    const mnuContrast = document.querySelector('.mnuContrast');

    btnContrast.addEventListener('click', function() {
        mnuContrast.classList.toggle('open');
    });

    // Menu Mobile Dropdown
    const btnMenu = document.querySelector('.btnMobile');
    const mnuMobile = document.querySelector('.mnuDefault');

    function toggleMenu() {
        mnuMobile.classList.toggle('open');
    }

    // Event listener para o botão mobile
    if (btnMenu) {
        btnMenu.addEventListener('click', toggleMenu);
    }

    // Fechar menu ao redimensionar para desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            mnuMobile.classList.remove('open');
        }
    });

    function handlePagination(event) {
        event.preventDefault();
        const url = this.href;
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#' + this.closest('nav').previousElementSibling.id).innerHTML;
                const newPagination = doc.querySelector('.pagination').innerHTML;
                document.querySelector('#' + this.closest('nav').previousElementSibling.id).innerHTML = newContent;
                document.querySelector('.pagination').innerHTML = newPagination;
                window.history.pushState(null, '', url);
                attachPaginationEvents(); // Reattach events to new pagination links
            })
            .catch(error => console.error('Error fetching the page:', error));
    }

    function attachPaginationEvents() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', handlePagination);
        });
    }

    attachPaginationEvents(); // Initial attachment of events

    // Sistema de filtros para categorias
    initCategoryFilters();
    
    // Inicializar eventos de paginação para conteúdo já carregado
    initInitialPagination();
    
    // Inicializar FAQ AJAX
    initFaqAjax();
    
    // Inicializar comportamento de accordion para FAQ
    initFaqAccordion();

    // Inicializar filtro de dicionário (somente na página do dicionário)
    if (window.location.pathname === '/dicionario/' || window.location.pathname.includes('/dicionario')) {
        initDictionaryFilter();
    }
    
});

function initInitialPagination() {
    const paginator = document.querySelector('.paginator');
    const cardsGrid = document.getElementById('cards-grid');
    
    if (!paginator || !cardsGrid) {
        return;
    }
    
    const paginationLinks = paginator.querySelectorAll('a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Mostrar loading
            cardsGrid.innerHTML = '<div class="loading" style="text-align: center; padding: 40px; font-size: 16px; color: #666;">Carregando...</div>';
            
            // Fazer requisição AJAX para a página
            fetch(this.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extrair novo conteúdo
                    const newCardsGrid = doc.getElementById('cards-grid');
                    const newPaginator = doc.querySelector('.paginator');
                    
                    if (newCardsGrid) {
                        cardsGrid.innerHTML = newCardsGrid.innerHTML;
                    }
                    
                    if (newPaginator && paginator) {
                        paginator.innerHTML = newPaginator.innerHTML;
                        // Reattach events para os novos links
                        initInitialPagination();
                    }
                    
                    // Scroll suave para o topo dos cards
                    cardsGrid.scrollIntoView({ behavior: 'smooth' });
                    
                    // Atualizar URL
                    window.history.pushState({}, '', this.href);
                })
                .catch(error => {
                    cardsGrid.innerHTML = '<p>Erro ao carregar conteúdo. Tente novamente.</p>';
                });
        });
    });
}

function initCategoryFilters() {
    const yearFilter = document.getElementById('year_filter');
    const monthFilter = document.getElementById('month_filter');
    const categoryFilter = document.getElementById('category_filter');
    const cardsGrid = document.getElementById('cards-grid');
    const paginator = document.querySelector('.paginator');
    
    if (!yearFilter || !monthFilter || !categoryFilter) {
        return; // Sai se não estiver na página de categoria
    }
    
    let isLoading = false;
    
    function showLoading() {
        if (cardsGrid) {
            cardsGrid.innerHTML = '<div class="loading" style="text-align: center; padding: 40px; font-size: 16px; color: #666;">Carregando...</div>';
        }
        if (paginator) {
            paginator.innerHTML = '';
        }
    }
    
    function filterPosts(page = 1) {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();
        
        const currentCategory = document.getElementById('current_category')?.value || '';
        
        const formData = new FormData();
        formData.append('action', 'filter_posts');
        formData.append('nonce', ajax_object.nonce);
        formData.append('year', yearFilter.value);
        formData.append('month', monthFilter.value);
        formData.append('category', categoryFilter.value);
        formData.append('current_category', currentCategory);
        formData.append('paged', page);
        
        fetch(ajax_object.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (cardsGrid) {
                    cardsGrid.innerHTML = data.data.content;
                }
                if (paginator) {
                    paginator.innerHTML = data.data.pagination;
                    attachFilterPaginationEvents();
                }
            } else {
                if (cardsGrid) {
                    cardsGrid.innerHTML = '<p>Erro ao carregar conteúdo. Tente novamente.</p>';
                }
            }
        })
        .catch(error => {
            if (cardsGrid) {
                cardsGrid.innerHTML = '<p>Erro ao carregar conteúdo. Tente novamente.</p>';
            }
        })
        .finally(() => {
            isLoading = false;
        });
    }
    
    function attachFilterPaginationEvents() {
        const paginationLinks = document.querySelectorAll('.paginator a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Extrair número da página da URL ou do texto do link
                let page = 1;
                const url = new URL(this.href);
                
                // Tentar pegar da query string primeiro
                page = url.searchParams.get('paged') || url.searchParams.get('page') || 1;
                
                // Se não encontrou na query string, tentar extrair do pathname
                if (page === 1) {
                    const pathMatch = url.pathname.match(/\/page\/(\d+)/);
                    if (pathMatch) {
                        page = pathMatch[1];
                    }
                }
                
                // Se ainda não encontrou, tentar extrair do texto do link (para números)
                if (page === 1 && this.textContent.match(/^\d+$/)) {
                    page = this.textContent;
                }
                
                filterPosts(page);
                
                // Scroll suave para o topo dos cards
                if (cardsGrid) {
                    cardsGrid.scrollIntoView({ behavior: 'smooth' });
                }
                
                // Atualizar URL sem recarregar a página
                const newUrl = new URL(window.location);
                if (page > 1) {
                    newUrl.searchParams.set('paged', page);
                } else {
                    newUrl.searchParams.delete('paged');
                }
                window.history.pushState({}, '', newUrl);
            });
        });
    }
    
    // Event listeners para os selects
    yearFilter.addEventListener('change', () => {
        filterPosts(1);
        updateUrlParams();
    });
    monthFilter.addEventListener('change', () => {
        filterPosts(1);
        updateUrlParams();
    });
    categoryFilter.addEventListener('change', () => {
        filterPosts(1);
        updateUrlParams();
    });
    
    // Função para atualizar parâmetros da URL
    function updateUrlParams() {
        const url = new URL(window.location);
        url.searchParams.delete('paged'); // Remove paginação ao filtrar
        window.history.pushState({}, '', url);
    }
}

/**
 * Dictionary Filter functionality
 * Handles filtering dictionary posts by letter using fetch API
 */
function initDictionaryFilter() {
    const letterLinks = document.querySelectorAll('.alphabet-list a');
    const letterSelect = document.getElementById('letters');
    const dictionaryContent = document.querySelector('.faqDictionary');
    
    if (!dictionaryContent) {
        return;
    }
    
    // Verificar se as variáveis AJAX estão disponíveis
    if (typeof ajax_object === 'undefined') {
        return;
    }
    
    let isLoading = false;
    
    // Show loading state
    function showLoading() {
        dictionaryContent.innerHTML = '<div class="dictionary-loading" style="text-align: center; padding: 40px; font-size: 16px; color: #666;"><p>Carregando termos...</p></div>';
        dictionaryContent.setAttribute('aria-busy', 'true');
    }
    
    // Hide loading state
    function hideLoading() {
        dictionaryContent.setAttribute('aria-busy', 'false');
    }
    
    // Update active letter in navigation
    function updateActiveLetter(letter) {
        letterLinks.forEach(link => {
            link.classList.remove('is-active');
            link.setAttribute('aria-current', 'false');
        });
        
        letterLinks.forEach(link => {
            const linkLetter = link.textContent.trim();
            if (linkLetter === letter) {
                link.classList.add('is-active');
                link.setAttribute('aria-current', 'page');
            }
        });
    }
    
    // Load dictionary posts by letter
    async function loadDictionaryByLetter(letter) {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();
        
        try {
            const formData = new FormData();
            formData.append('action', 'filter_dictionary');
            formData.append('nonce', ajax_object.nonce);
            formData.append('letter', letter);
            
            const response = await fetch(ajax_object.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                dictionaryContent.innerHTML = data.data.content;
                updateActiveLetter(letter);
                
                // Update select value
                if (letterSelect) {
                    letterSelect.value = letter;
                }
                
                // Animate content
                dictionaryContent.style.opacity = '0';
                setTimeout(() => {
                    dictionaryContent.style.opacity = '1';
                }, 100);
                
                // Update URL
                const url = new URL(window.location);
                url.searchParams.set('letra', letter);
                window.history.pushState({}, '', url);
                
            } else {
                throw new Error(data.data || 'Erro ao carregar termos');
            }
            
        } catch (error) {
            dictionaryContent.innerHTML = `
                <div class="dictionary-error" style="text-align: center; padding: 40px;">
                    <p style="color: #d32f2f; margin-bottom: 1rem;">Erro ao carregar os termos. Tente novamente.</p>
                    <button onclick="location.reload()" class="retry-btn" style="padding: 0.5rem 1rem; background: var(--charlestonGreen); color: white; border: none; border-radius: 4px; cursor: pointer;">Recarregar página</button>
                </div>
            `;
        } finally {
            isLoading = false;
            hideLoading();
        }
    }
    
    // Add click event listeners to letter links
    if (letterLinks.length > 0) {
        letterLinks.forEach((link, index) => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const letter = this.textContent.trim();
                
                if (letter && letter.length === 1) {
                    loadDictionaryByLetter(letter);
                }
            });
        });
    }
    
    // Add change event listener to select
    if (letterSelect) {
        letterSelect.addEventListener('change', function() {
            const letter = this.value;
            
            if (letter && letter !== 'Selecione uma letra') {
                loadDictionaryByLetter(letter);
                
                // Scroll suave para o conteúdo em mobile
                dictionaryContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
    
    // Add keyboard navigation support for letter links
    letterLinks.forEach((link, index) => {
        link.addEventListener('keydown', function(e) {
            let nextIndex;
            
            switch(e.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    nextIndex = (index + 1) % letterLinks.length;
                    letterLinks[nextIndex].focus();
                    break;
                    
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    nextIndex = (index - 1 + letterLinks.length) % letterLinks.length;
                    letterLinks[nextIndex].focus();
                    break;
                    
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    this.click();
                    break;
            }
        });
    });
}

/**
 * FAQ AJAX functionality
 * Handles loading FAQ answers by category using fetchAPI
 */
function initFaqAjax() {
    const categoryButtons = document.querySelectorAll('.category-link');
    const faqContent = document.getElementById('faq-content');
    
    if (!categoryButtons.length || !faqContent) {
        return;
    }
    
    // Verificar se as variáveis AJAX estão disponíveis
    if (typeof faq_ajax === 'undefined') {
        // Criar fallback usando variáveis globais do WordPress
        window.faq_ajax = {
            ajax_url: '/wp-admin/admin-ajax.php',
            nonce: 'fallback_nonce' // Em produção, isso deve ser gerado pelo WordPress
        };
    }
    
    // Add loading state functionality
    function showLoading() {
        faqContent.innerHTML = '<div class="faq-loading"><p>Carregando respostas...</p></div>';
    }
    
    function hideLoading() {
        const loading = faqContent.querySelector('.faq-loading');
        if (loading) {
            loading.remove();
        }
    }
    
    // Update active button state
    function updateActiveButton(activeButton) {
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        activeButton.classList.add('active');
    }
    
    // Load FAQ answers for a specific category
    async function loadFaqAnswers(termId, button) {
        try {
            showLoading();
            updateActiveButton(button);
            
            const formData = new FormData();
            formData.append('action', 'get_respostas_by_assunto');
            formData.append('term_id', termId);
            formData.append('nonce', faq_ajax.nonce);
            
            const response = await fetch(faq_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                faqContent.innerHTML = data.data;
                
                // Animate the new content
                faqContent.style.opacity = '0';
                setTimeout(() => {
                    faqContent.style.opacity = '1';
                }, 100);
                
            } else {
                throw new Error(data.data || 'Erro ao carregar respostas');
            }
            
        } catch (error) {
            faqContent.innerHTML = `
                <div class="faq-error">
                    <p>Erro ao carregar as respostas. Tente novamente.</p>
                    <button onclick="location.reload()" class="retry-btn">Recarregar página</button>
                </div>
            `;
        } finally {
            hideLoading();
        }
    }
    
    // Add click event listeners to category buttons
    categoryButtons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const termId = this.dataset.categoryId;
            
            if (!termId) {
                return;
            }
            
            loadFaqAnswers(termId, this);
        });
    });
    
    // Set first button as active by default
    if (categoryButtons.length > 0) {
        categoryButtons[0].classList.add('active');
    }
    
    // Add keyboard navigation support
    categoryButtons.forEach((button, index) => {
        button.addEventListener('keydown', function(e) {
            let nextIndex;
            
            switch(e.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    nextIndex = (index + 1) % categoryButtons.length;
                    categoryButtons[nextIndex].focus();
                    break;
                    
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    nextIndex = (index - 1 + categoryButtons.length) % categoryButtons.length;
                    categoryButtons[nextIndex].focus();
                    break;
                    
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    this.click();
                    break;
            }
        });
    });
}

/**
 * FAQ Accordion functionality
 * Ensures only one details element is open at a time
 */
function initFaqAccordion() {
    // Função para gerenciar o accordion dos details
    function setupAccordion() {
        const allDetails = document.querySelectorAll('#faq-content details');
        
        if (allDetails.length === 0) {
            return;
        }
        
        allDetails.forEach(details => {
            details.addEventListener('toggle', function() {
                // Se este details foi aberto
                if (this.open) {
                    // Fechar todos os outros details
                    allDetails.forEach(otherDetails => {
                        if (otherDetails !== this && otherDetails.open) {
                            otherDetails.open = false;
                        }
                    });
                }
            });
        });
    }
    
    // Configurar accordion inicialmente
    setupAccordion();
    
    // Observar mudanças no conteúdo do FAQ (para quando carrega via AJAX)
    const faqContent = document.getElementById('faq-content');
    if (faqContent) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Verificar se foram adicionados novos details
                    const hasNewDetails = Array.from(mutation.addedNodes).some(node => 
                        node.nodeType === Node.ELEMENT_NODE && 
                        (node.tagName === 'DETAILS' || node.querySelector('details'))
                    );
                    
                    if (hasNewDetails) {
                        // Reconfigurar o accordion para os novos elementos
                        setTimeout(setupAccordion, 100);
                    }
                }
            });
        });
        
        observer.observe(faqContent, {
            childList: true,
            subtree: true
        });
    }
}