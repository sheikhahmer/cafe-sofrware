<script>
    (function () {
        // Function to handle the shortcuts
        function handleShortcuts(event) {
            // ALT + K for Kitchen Print
            if (event.altKey && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                event.stopPropagation();
                const kitchenPrintButton = findEnabledButton('kitchenPrint');
                if (kitchenPrintButton) {
                    kitchenPrintButton.click();
                } else {
                    console.warn('No available Kitchen Print button found.');
                }
            }

            // ALT + T for Print Only
            if (event.altKey && event.key.toLowerCase() === 't') {
                event.preventDefault();
                event.stopPropagation();
                const printOnlyButton = findEnabledButton('print_only');
                if (printOnlyButton) {
                    printOnlyButton.click();
                } else {
                    console.warn('No available Print Only button found.');
                }
            }

            // ALT + P for Mark as Paid
            if (event.altKey && event.key.toLowerCase() === 'p') {
                event.preventDefault();
                event.stopPropagation();
                const markPaidButton = findEnabledButton('mark_paid');
                if (markPaidButton) {
                    markPaidButton.click();
                } else {
                    console.warn('No available Mark as Paid button found.');
                }
            }

            if (event.altKey && event.key.toLowerCase() === 'n') {
                event.preventDefault();
                event.stopPropagation();

                // Find the New Order button by its classes
                const newOrderButton = document.querySelector('.fi-ac-btn-action.fi-btn.fi-size-md:not([disabled])');

                if (newOrderButton) {
                    newOrderButton.click();  // Simulate click on the New Order button
                } else {
                    console.warn('New Order button not found.');
                }
            }
            document.addEventListener('keydown', function(event) {
                if (event.altKey && event.key.toLowerCase() === 'n') {   // ALT + n for Expense
                    event.preventDefault();
                    event.stopPropagation();

                    const expenseButton = document.querySelector('.fi-ac-btn-action.fi-btn.fi-size-md:not([disabled])');

                    if (expenseButton) {
                        expenseButton.click();
                    } else {
                        console.warn('Expense New button not found.');
                    }
                }
            });


        }

        // Helper function to find enabled action buttons
        function findEnabledButton(action) {
            // Look for enabled action buttons in the Filament table
            const selectors = [
                `button[data-action="${action}"]:not([disabled])`,
                `[wire\\:click*="${action}"]:not([disabled])`,
                `button:contains("${action.replace('_', ' ')}"):not([disabled])`,
            ];

            for (const selector of selectors) {
                const btn = document.querySelector(selector);
                if (btn) return btn;
            }

            // Fallback: Find any button with the correct label and is not disabled
            const fallback = [...document.querySelectorAll('button')]
                .find(b => b.textContent.trim().toLowerCase().includes(action.replace('_', ' ').toLowerCase()) && !b.disabled);
            return fallback || null;
        }

        // Initialize the shortcut listener
        function initializeShortcuts() {
            document.removeEventListener('keydown', handleShortcuts);
            document.addEventListener('keydown', handleShortcuts);
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeShortcuts);
        } else {
            initializeShortcuts();
        }

        // Re-init for Livewire updates
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:load', initializeShortcuts);
            document.addEventListener('livewire:update', () => {
                setTimeout(initializeShortcuts, 100);
            });
        }

    })();
</script>
