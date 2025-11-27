<script>
    (function() {
        // Create preloader element if it doesn't exist
        let preloader = document.getElementById('page-preloader');
        if (!preloader) {
            preloader = document.createElement('div');
            preloader.id = 'page-preloader';
            preloader.className = 'page-preloader';
            preloader.innerHTML = `
                <div class="preloader-content">
                    <div class="preloader-spinner">
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                    </div>
                    <div class="preloader-text">Loading...</div>
                </div>
            `;
            document.body.appendChild(preloader);
        }

        function showPreloader() {
            if (preloader) {
                preloader.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function hidePreloader() {
            if (preloader) {
                preloader.classList.remove('active');
                setTimeout(() => {
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // Check if URL is a create page
        function isCreatePage(url) {
            if (!url) return false;
            return url.includes('/create') || url.endsWith('/create');
        }

        // Hide preloader when page is loaded
        function handlePageLoad() {
            setTimeout(() => {
                hidePreloader();
            }, 300);
        }

        // Initial page load - only show if it's a create page
        if (isCreatePage(window.location.pathname)) {
            if (document.readyState === 'loading') {
                showPreloader();
                document.addEventListener('DOMContentLoaded', handlePageLoad);
            } else {
                handlePageLoad();
            }
        }

        // Handle Livewire navigation - only for create pages
        if (typeof Livewire !== 'undefined') {
            // Show preloader on Livewire navigation to create pages
            document.addEventListener('livewire:navigate', (event) => {
                if (isCreatePage(event.detail.url)) {
                    showPreloader();
                }
            });
            
            // Hide preloader when Livewire finishes loading
            Livewire.hook('morph.updated', () => {
                handlePageLoad();
            });

            // Handle Livewire requests - only for create page navigation
            Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
                if (isCreatePage(uri)) {
                    showPreloader();
                    
                    succeed(({ status, preventDefault }) => {
                        setTimeout(() => {
                            hidePreloader();
                        }, 200);
                    });

                    fail(({ status, preventDefault, payload }) => {
                        hidePreloader();
                    });
                }
            });
        }

        // Handle link clicks - only show preloader for create page links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                    // Only show preloader if clicking a link that goes to a create page
                    if (isCreatePage(href)) {
                        showPreloader();
                    }
                }
            }

            // Also check for buttons that might navigate to create pages
            const button = e.target.closest('button');
            if (button) {
                const buttonText = button.textContent?.toLowerCase() || '';
                const buttonClass = button.className || '';
                // Check if it's a "Create" button
                if (buttonText.includes('create') || buttonClass.includes('create') || 
                    button.getAttribute('wire:click')?.includes('create')) {
                    // Check if current page is not already a create page
                    if (!isCreatePage(window.location.pathname)) {
                        showPreloader();
                    }
                }
            }
        });

        // Handle form submissions on create pages
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM' && !form.hasAttribute('wire:ignore')) {
                // Only show preloader if we're on a create page
                if (isCreatePage(window.location.pathname)) {
                    showPreloader();
                }
            }
        });

        // Fallback: hide preloader after a maximum time
        setTimeout(() => {
            hidePreloader();
        }, 3000);
    })();
</script>

