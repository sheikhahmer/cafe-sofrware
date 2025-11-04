<script>
    (function () {
        function handleKitchenPrintShortcut(event) {
            // ALT + K
            if (event.altKey && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                event.stopPropagation();

                const kitchenPrintButton = findEnabledKitchenPrintButton();

                if (kitchenPrintButton) {
                    kitchenPrintButton.click();
                } else {
                    console.warn('No available Kitchen Print button found.');
                }
            }
        }

        function findEnabledKitchenPrintButton() {
            // Look for enabled Kitchen Print buttons in the Filament table
            // Filament v4 uses data attributes for action buttons
            const selectors = [
                'button[data-action="kitchenPrint"]:not([disabled])',
                '[wire\\:click*="kitchenPrint"]:not([disabled])',
                'button:contains("Kitchen Print"):not([disabled])',
            ];

            for (const selector of selectors) {
                if (selector.includes('contains')) {
                    // Fallback for browsers that don't support :contains
                    const allButtons = document.querySelectorAll('button');
                    for (const btn of allButtons) {
                        if (btn.textContent.trim().includes('Kitchen Print') && !btn.disabled) {
                            return btn;
                        }
                    }
                } else {
                    const btn = document.querySelector(selector);
                    if (btn) return btn;
                }
            }

            // Fallback — look for any button labeled "Kitchen Print" that isn’t disabled
            const fallback = [...document.querySelectorAll('button')]
                .find(b => b.textContent.includes('Kitchen Print') && !b.disabled);
            return fallback || null;
        }

        function initializeKitchenPrintShortcut() {
            document.removeEventListener('keydown', handleKitchenPrintShortcut);
            document.addEventListener('keydown', handleKitchenPrintShortcut);
        }

        // Initialize when DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeKitchenPrintShortcut);
        } else {
            initializeKitchenPrintShortcut();
        }

        // Re-init for Livewire updates
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:load', initializeKitchenPrintShortcut);
            document.addEventListener('livewire:update', () => {
                setTimeout(initializeKitchenPrintShortcut, 100);
            });
        }

    })();
</script>
