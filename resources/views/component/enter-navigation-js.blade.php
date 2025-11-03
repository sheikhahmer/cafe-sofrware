<script>
    (function() {
        let isHandlingEnter = false;

        function handleEnterNavigation(event) {
            if (isHandlingEnter) return;
            if (event.key !== 'Enter') return;

            const target = event.target;
            const focusables = getFocusableElements();
            if (!focusables.includes(target)) return; // only act for recognized focusable fields

            // ignore Enter when inside Filament select search input if you mean to select option via Enter
            // but still allow moving forward from other inputs
            isHandlingEnter = true;
            event.preventDefault();
            event.stopPropagation();

            if (event.shiftKey) {
                moveToPreviousField(target);
            } else {
                moveToNextField(target);
            }

            setTimeout(() => { isHandlingEnter = false; }, 50);
        }

        function moveToNextField(currentField) {
            const allInputs = getFocusableElements();
            const currentIndex = allInputs.indexOf(currentField);

            if (currentIndex === -1) return;

            if (currentIndex < allInputs.length - 1) {
                const next = allInputs[currentIndex + 1];

                // If next is Filament select button -> open dropdown and focus its search input
                if (next.classList && next.classList.contains('fi-select-input-btn')) {
                    openFilamentSelectAndFocusSearch(next);
                    return;
                }

                // If next is already the search input inside Filament dropdown -> focus it
                if (next.closest && next.closest('.fi-select-input-search-ctn')) {
                    focusField(next);
                    return;
                }

                // Otherwise focus normally
                focusField(next);
            } else {
                // last element -> submit
                submitForm(currentField);
            }
        }

        function moveToPreviousField(currentField) {
            const allInputs = getFocusableElements();
            const currentIndex = allInputs.indexOf(currentField);

            if (currentIndex > 0) {
                const prev = allInputs[currentIndex - 1];

                // If previous is Filament select button -> focus the button (don't open)
                if (prev.classList && prev.classList.contains('fi-select-input-btn')) {
                    prev.focus();
                    return;
                }

                focusField(prev);
            }
        }

        function focusField(field) {
            if (!field) return;
            try {
                field.focus();
                if (typeof field.select === 'function' && !field.matches('select')) {
                    setTimeout(() => field.select(), 10);
                }
            } catch (e) {
                // ignore focus errors
            }
        }

        function openFilamentSelectAndFocusSearch(button) {
            if (!button) return;

            // Click the Filament select button to open dropdown
            try {
                button.click();
            } catch (e) {
                // fallback: dispatch MouseEvent
                button.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
                button.dispatchEvent(new MouseEvent('mouseup', { bubbles: true }));
                button.dispatchEvent(new MouseEvent('click', { bubbles: true }));
            }

            // Closest wrapper for scope
            const wrapper = button.closest('.fi-select-input') || button.parentElement;

            // Wait for the dropdown search input to appear.
            // Use MutationObserver with a short fallback polling interval to be robust.
            let disconnected = false;

            const tryFocusSearch = () => {
                const search = wrapper && wrapper.querySelector('.fi-select-input-search-ctn input.fi-input, .fi-select-input-search-ctn input');
                if (search) {
                    setTimeout(() => {
                        try { search.focus(); search.select(); } catch (e) {}
                    }, 30);
                    return true;
                }
                return false;
            };

            if (tryFocusSearch()) return;

            const obs = new MutationObserver((mutations, observer) => {
                if (tryFocusSearch()) {
                    observer.disconnect();
                    disconnected = true;
                }
            });

            obs.observe(wrapper, { childList: true, subtree: true });

            // fallback polling for up to ~1 second
            let tries = 0;
            const iv = setInterval(() => {
                tries++;
                if (disconnected || tryFocusSearch() || tries > 20) {
                    clearInterval(iv);
                    try { obs.disconnect(); } catch (e) {}
                }
            }, 50);
        }

        function getFocusableElements() {
            // build list in DOM order
            const selectors = [
                'input:not([disabled]):not([readonly]):not([type="hidden"]):not([type="submit"]):not([type="button"])',
                'textarea:not([disabled]):not([readonly])',
                'select:not([disabled])',
                // Filament select button (this is what tab naturally focuses in your UI)
                '.fi-select-input-btn',
                // Filament dropdown search input (when opened)
                '.fi-select-input-search-ctn input:not([disabled]):not([readonly])'
            ];

            const elements = [];
            selectors.forEach(sel => {
                const found = document.querySelectorAll(sel);
                found.forEach(el => {
                    // ignore invisible elements
                    const rect = el.getBoundingClientRect();
                    const isVisible = rect.width > 0 && rect.height > 0 && el.offsetParent !== null;
                    if (isVisible) elements.push(el);
                });
            });

            // sort by document position to keep natural tab order
            return elements.sort((a, b) => {
                if (a === b) return 0;
                const pos = a.compareDocumentPosition(b);
                if (pos & Node.DOCUMENT_POSITION_FOLLOWING) return -1;
                if (pos & Node.DOCUMENT_POSITION_PRECEDING) return 1;
                return 0;
            });
        }

        function submitForm(element) {
            const form = element.closest && element.closest('form');
            if (form) {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.click();
                } else {
                    try { form.submit(); } catch (e) {}
                }
            }
        }

        // Initialize navigation
        function initializeNavigation() {
            document.removeEventListener('keydown', handleEnterNavigation, true);
            document.addEventListener('keydown', handleEnterNavigation, true);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeNavigation);
        } else {
            initializeNavigation();
        }

        // Re-init for Livewire / dynamic content
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:load', initializeNavigation);
            document.addEventListener('livewire:update', () => {
                setTimeout(initializeNavigation, 100);
            });
        }

        // Observe new nodes (repeaters) and re-init
        const globalObserver = new MutationObserver((mutations) => {
            let reinit = false;
            mutations.forEach(m => {
                m.addedNodes.forEach(node => {
                    if (node.nodeType === 1 &&
                        (node.matches('[data-repeater-item], [data-filament-repeatable-item], .fi-select-input') ||
                            node.querySelector && node.querySelector('[data-repeater-item], [data-filament-repeatable-item], .fi-select-input'))) {
                        reinit = true;
                    }
                });
            });
            if (reinit) {
                setTimeout(initializeNavigation, 50);
            }
        });

        globalObserver.observe(document.body, { childList: true, subtree: true });

    })();
</script>
