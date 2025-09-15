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

    const categoryLinks = document.querySelectorAll('.category-link');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            fetchPostsByCategory(categoryId);
        });
    });

    function fetchPostsByCategory(categoryId) {
        fetch(`/wp-json/wp/v2/posts?categories=${categoryId}`)
            .then(response => response.json())
            .then(posts => {
                const questionsSection = document.querySelector('.faq .questions');
                questionsSection.innerHTML = '';

                posts.forEach(post => {
                    const questionSection = document.createElement('section');
                    questionSection.setAttribute('itemscope', '');
                    questionSection.setAttribute('itemprop', 'mainEntity');
                    questionSection.setAttribute('itemtype', 'https://schema.org/Question');
                    questionSection.classList.add('question');

                    const summary = document.createElement('summary');
                    summary.setAttribute('itemprop', 'name');
                    summary.textContent = post.title.rendered;

                    const details = document.createElement('details');
                    details.setAttribute('itemscope', '');
                    details.setAttribute('itemprop', 'acceptedAnswer');
                    details.setAttribute('itemtype', 'https://schema.org/Answer');

                    const contentDiv = document.createElement('div');
                    contentDiv.setAttribute('itemprop', 'text');
                    contentDiv.innerHTML = post.content.rendered;

                    details.appendChild(contentDiv);
                    questionSection.appendChild(summary);
                    questionSection.appendChild(details);
                    questionsSection.appendChild(questionSection);
                });
            })
            .catch(error => console.error('Error fetching posts:', error));
    }

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
                    console.error('Erro ao carregar página:', error);
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
                console.error('Erro ao filtrar posts:', data);
                if (cardsGrid) {
                    cardsGrid.innerHTML = '<p>Erro ao carregar conteúdo. Tente novamente.</p>';
                }
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
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
 * FAQ AJAX functionality
 * Handles loading FAQ answers by category using fetchAPI
 */
function initFaqAjax() {
    console.log('FAQ AJAX: Iniciando função');
    
    const categoryButtons = document.querySelectorAll('.category-link');
    const faqContent = document.getElementById('faq-content');
    
    console.log('FAQ AJAX: Botões encontrados:', categoryButtons.length);
    console.log('FAQ AJAX: Container encontrado:', !!faqContent);
    
    if (!categoryButtons.length || !faqContent) {
        console.log('FAQ AJAX: Elementos não encontrados, saindo da função');
        return;
    }
    
    // Verificar se as variáveis AJAX estão disponíveis
    if (typeof faq_ajax === 'undefined') {
        console.warn('FAQ AJAX: variáveis não encontradas, criando fallback');
        // Criar fallback usando variáveis globais do WordPress
        window.faq_ajax = {
            ajax_url: '/wp-admin/admin-ajax.php',
            nonce: 'fallback_nonce' // Em produção, isso deve ser gerado pelo WordPress
        };
    }
    
    console.log('FAQ AJAX: Variáveis disponíveis:', window.faq_ajax);
    
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
            console.error('Erro ao carregar FAQ:', error);
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
        console.log(`FAQ AJAX: Adicionando listener ao botão ${index + 1}:`, button.textContent.trim());
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            console.log('FAQ AJAX: Botão clicado:', this.textContent.trim());
            
            const termId = this.dataset.categoryId;
            
            console.log('FAQ AJAX: Term ID:', termId);
            
            if (!termId) {
                console.error('ID do termo não encontrado');
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