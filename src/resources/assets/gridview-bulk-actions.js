/**
 * GridView Bulk Actions Script
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView
 */

(function() {
    'use strict';

    // Select All functionality
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('gridview-select-all')) {
            const checkboxes = document.querySelectorAll('.gridview-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateBulkActionsState();
        }
    });

    // Individual checkbox change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('gridview-checkbox')) {
            updateBulkActionsState();
            updateSelectAllState();
        }
    });

    // Update bulk actions button state
    function updateBulkActionsState() {
        const selected = getSelectedIds();
        const bulkActionBtn = document.querySelector('.gridview-bulk-action-btn');
        const selectedCount = document.querySelector('.gridview-selected-count');

        if (bulkActionBtn) {
            bulkActionBtn.disabled = selected.length === 0;
        }

        if (selectedCount) {
            selectedCount.textContent = selected.length;
        }
    }

    // Update "Select All" checkbox state
    function updateSelectAllState() {
        const selectAll = document.querySelector('.gridview-select-all');
        const checkboxes = document.querySelectorAll('.gridview-checkbox');
        const checkedBoxes = document.querySelectorAll('.gridview-checkbox:checked');

        if (selectAll && checkboxes.length > 0) {
            selectAll.checked = checkboxes.length === checkedBoxes.length;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkboxes.length !== checkedBoxes.length;
        }
    }

    // Get selected IDs
    function getSelectedIds() {
        const checkboxes = document.querySelectorAll('.gridview-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    // Handle bulk action execution
    window.executeGridViewBulkAction = async function(action, confirmMessage) {
        const selectedIds = getSelectedIds();

        if (selectedIds.length === 0) {
            showNotification('Please select at least one item', 'warning');
            return;
        }

        if (confirmMessage && !confirm(confirmMessage)) {
            return;
        }

        const modelClass = document.querySelector('[data-gridview-model]')?.dataset.gridviewModel;

        if (!modelClass) {
            showNotification('Model class not specified', 'error');
            return;
        }

        try {
            const response = await fetch('/gridview/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    ids: selectedIds,
                    model: modelClass
                })
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');

                // Reload the page or remove rows
                if (action === 'delete') {
                    selectedIds.forEach(id => {
                        const checkbox = document.querySelector(`.gridview-checkbox[value="${id}"]`);
                        if (checkbox) {
                            checkbox.closest('tr').remove();
                        }
                    });
                } else {
                    location.reload();
                }
            } else {
                throw new Error(data.message || 'Action failed');
            }
        } catch (error) {
            showNotification(error.message || 'An error occurred', 'error');
        }
    };

    // Notification helper
    function showNotification(message, type) {
        const notification = document.createElement('div');
        const bgColor = {
            'success': '#28a745',
            'error': '#dc3545',
            'warning': '#ffc107'
        }[type] || '#007bff';

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 25px;
            background: ${bgColor};
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Initialize on page load
    updateBulkActionsState();
})();