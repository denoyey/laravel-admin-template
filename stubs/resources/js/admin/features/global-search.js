export function initGlobalSearch() {
    const triggerBtn = document.getElementById('global-search-trigger');
    const modal = document.getElementById('global-search-modal');

    if (!triggerBtn || !modal) return;

    const backdrop = document.getElementById('global-search-backdrop');
    const wrapper = document.getElementById('global-search-wrapper');
    const panel = document.getElementById('global-search-panel');
    const input = document.getElementById('global-search-input');

    const loadingState = document.getElementById('global-search-loading');
    const emptyState = document.getElementById('global-search-empty');
    const noResultsState = document.getElementById('global-search-no-results');
    const resultsList = document.getElementById('global-search-results');
    const queryText = document.getElementById('search-query-text');
    const template = document.getElementById('global-search-result-template');

    let debounceTimer;

    const getBaseUrl = () => window.location.origin;

    function openModal() {
        modal.classList.remove('hidden');


        void modal.offsetWidth;

        backdrop.classList.replace('opacity-0', 'opacity-100');
        panel.classList.replace('opacity-0', 'opacity-100');
        panel.classList.replace('scale-95', 'scale-100');

        setTimeout(() => {
            input.focus();
        }, 100);

        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        backdrop.classList.replace('opacity-100', 'opacity-0');
        panel.classList.replace('opacity-100', 'opacity-0');
        panel.classList.replace('scale-100', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            input.value = '';
            showState('empty');
        }, 200); // matches Tailwind transition duration
    }

    function showState(state) {
        loadingState.classList.add('hidden');
        emptyState.classList.add('hidden');
        noResultsState.classList.add('hidden');
        resultsList.classList.add('hidden');

        if (state === 'loading') loadingState.classList.remove('hidden');
        if (state === 'empty') emptyState.classList.remove('hidden');
        if (state === 'no-results') noResultsState.classList.remove('hidden');
        if (state === 'results') resultsList.classList.remove('hidden');
    }

    function performSearch(query) {
        if (!query || query.trim().length < 1) {
            showState('empty');
            return;
        }

        showState('loading');

        fetch(`/portal-admin/global-search?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
                queryText.textContent = query;
                showState('no-results');
            } else {
                renderResults(data, query);
                showState('results');
            }
        })
        .catch(err => {
            console.error("Search error:", err);
            showState('empty');
        });
    }

    function renderResults(data, query) {
        resultsList.innerHTML = '';


        const safeQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${safeQuery})`, 'gi');


        const groupedData = data.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});

        for (const [type, items] of Object.entries(groupedData)) {

            const header = document.createElement('div');
            header.className = 'px-4 pb-2 pt-5 first:pt-1 text-[10px] sm:text-[11px] font-bold text-gray-800 uppercase tracking-wider';
            header.textContent = type;
            resultsList.appendChild(header);

            items.forEach(item => {
                const clone = template.content.cloneNode(true);
                const link = clone.querySelector('.search-result-link');
                const title = clone.querySelector('.search-result-title');
                const iconWrapper = clone.querySelector('.search-result-icon-wrapper');

                link.href = item.url;
                link.addEventListener('mouseenter', () => {
                    link.focus();
                });

                const highlightedTitle = item.title.replace(regex, '<span class="text-hijau font-bold underline decoration-hijau decoration-2 underline-offset-2">$1</span>');
                title.innerHTML = highlightedTitle;

                if (item.icon_svg) {
                    iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">${item.icon_svg}</svg>`;
                }

                resultsList.appendChild(clone);
            });
        }
    }

    triggerBtn.addEventListener('click', openModal);

    backdrop.addEventListener('click', closeModal);
    wrapper.addEventListener('click', (e) => {
        if (e.target === wrapper) closeModal();
    });

    document.addEventListener('keydown', (e) => {

        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            openModal();
        }


        if (!modal.classList.contains('hidden')) {

            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }

            const links = Array.from(resultsList.querySelectorAll('.search-result-link'));

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const currentIndex = links.indexOf(document.activeElement);
                if (currentIndex === -1 && links.length > 0) {
                    links[0].focus(); // from input to first link
                } else if (currentIndex > -1 && currentIndex < links.length - 1) {
                    links[currentIndex + 1].focus(); // next link
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const currentIndex = links.indexOf(document.activeElement);
                if (currentIndex > 0) {
                    links[currentIndex - 1].focus(); // prev link
                } else if (currentIndex === 0) {
                    input.focus(); // back to input
                }
            } else if (e.key === 'Enter') {
                if (document.activeElement === input && links.length > 0) {
                    e.preventDefault();
                    links[0].click(); // If enter on input, go to first result
                }

            }
        }
    });

    input.addEventListener('input', (e) => {
        const query = e.target.value;

        clearTimeout(debounceTimer);

        if (!query || query.trim().length < 1) {
            showState('empty');
            return;
        }


        showState('loading');


        debounceTimer = setTimeout(() => {
            performSearch(query);
        }, 300);
    });


    backdrop.classList.add('opacity-0');
}
