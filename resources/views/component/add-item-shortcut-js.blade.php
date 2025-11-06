<script>
    (function() {
        function handleAddItemShortcut(event) {
            // Check if Ctrl+I is pressed
            if (event.altKey && event.key === 'i') {
                event.preventDefault();
                event.stopPropagation();

                // Find the "Add Item" button in the repeater
                const addItemButton = findAddItemButton();

                if (addItemButton) {
                    addItemButton.click();

                    // Optional: Focus on the first input in the new repeater item
                    setTimeout(() => {
                        focusFirstInputInNewItem();
                    }, 100);
                }
            }
        }

        function findAddItemButton() {
            // Try multiple selectors to find the "Add Item" button
            const selectors = [
                'button[data-repeater-create-button]',
                '[data-filament-repeatable-create-button]',
                'button:contains("Add Item")',
                'button:has(span:contains("Add Item"))',
                'button.fi-repeater-create-button',
                '.fi-repeater .fi-btn:last-child'
            ];

            for (const selector of selectors) {
                if (selector.includes('contains')) {
                    // For text-based selection
                    const buttons = document.querySelectorAll('button');
                    for (const button of buttons) {
                        if (button.textContent.includes('Add Item')) {
                            return button;
                        }
                    }
                } else {
                    const button = document.querySelector(selector);
                    if (button) return button;
                }
            }

            // Fallback: Look for any button with "Add Item" text in the form
            const allButtons = document.querySelectorAll('button');
            for (const button of allButtons) {
                if (button.textContent.trim() === 'Add Item' ||
                    button.textContent.includes('Add Item')) {
                    return button;
                }
            }

            return null;
        }

        function focusFirstInputInNewItem() {
            // Find the newest repeater item (usually the last one)
            const repeaterItems = document.querySelectorAll('[data-repeater-item], [data-filament-repeatable-item]');
            if (repeaterItems.length === 0) return;

            const lastItem = repeaterItems[repeaterItems.length - 1];

            // Find focusable elements in the new item
            const focusableSelectors = [
                'input:not([disabled]):not([readonly]):not([type="hidden"])',
                'select:not([disabled])',
                'textarea:not([disabled]):not([readonly])'
            ];

            for (const selector of focusableSelectors) {
                const element = lastItem.querySelector(selector);
                if (element) {
                    element.focus();
                    if (typeof element.select === 'function') {
                        setTimeout(() => element.select(), 10);
                    }
                    break;
                }
            }
        }

        function initializeAddItemShortcut() {
            document.removeEventListener('keydown', handleAddItemShortcut);
            document.addEventListener('keydown', handleAddItemShortcut);
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAddItemShortcut);
        } else {
            initializeAddItemShortcut();
        }

        // Re-initialize for Livewire/dynamic content
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:load', initializeAddItemShortcut);
            document.addEventListener('livewire:update', () => {
                setTimeout(initializeAddItemShortcut, 100);
            });
        }

        // Observe for new repeater items
        const observer = new MutationObserver((mutations) => {
            let shouldReinit = false;
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 &&
                        (node.matches && (node.matches('[data-repeater-item]') ||
                            node.matches('[data-filament-repeatable-item]') ||
                            (node.querySelector && (node.querySelector('[data-repeater-item]') ||
                                node.querySelector('[data-filament-repeatable-item]')))))) {
                        shouldReinit = true;
                    }
                });
            });
            if (shouldReinit) {
                setTimeout(initializeAddItemShortcut, 50);
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });

    })();
</script>
