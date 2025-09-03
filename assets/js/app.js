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
    
});