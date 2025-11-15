<?php

namespace Picobaz\GridView\Traits;

/**
 * InlineEditable Trait - Enable inline editing for GridView columns
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView\Traits
 */
trait InlineEditable
{
    /**
     * Render an inline editable cell
     *
     * @param mixed $model
     * @param array $column
     * @return string
     */
    public function renderEditableCell($model, $column)
    {
        $attribute = $column['attribute'];
        $value = is_object($model) ? $model->$attribute : $model[$attribute];
        $primaryKey = $column['primaryKey'] ?? 'id';
        $pkValue = is_object($model) ? $model->$primaryKey : $model[$primaryKey];

        $type = $column['editableType'] ?? 'text';
        $url = $column['editableUrl'] ?? route('gridview.inline.update');
        $options = $column['editableOptions'] ?? [];

        $dataAttrs = 'data-pk="' . htmlspecialchars($pkValue) . '" 
                      data-attribute="' . htmlspecialchars($attribute) . '" 
                      data-url="' . htmlspecialchars($url) . '" 
                      data-type="' . htmlspecialchars($type) . '"';

        if ($type === 'select' && isset($options['source'])) {
            $dataAttrs .= ' data-source=\'' . json_encode($options['source']) . '\'';
        }

        $cssClass = 'gridview-editable ' . ($column['editableClass'] ?? '');

        return '<span class="' . $cssClass . '" 
                      ' . $dataAttrs . ' 
                      style="cursor: pointer; border-bottom: 1px dashed #007bff;"
                      title="Click to edit">'
            . htmlspecialchars($value) .
            '</span>';
    }

    /**
     * Generate JavaScript for inline editing
     *
     * @return string
     */
    public function getInlineEditScript()
    {
        return <<<'JS'
<script>
(function() {
    'use strict';
    
    // Handle inline edit click
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('gridview-editable')) return;
        
        const span = e.target;
        const originalValue = span.textContent.trim();
        const type = span.dataset.type;
        const pk = span.dataset.pk;
        const attribute = span.dataset.attribute;
        const url = span.dataset.url;
        
        let input;
        
        if (type === 'select') {
            input = document.createElement('select');
            input.className = 'form-control form-control-sm';
            const source = JSON.parse(span.dataset.source || '[]');
            source.forEach(item => {
                const option = document.createElement('option');
                option.value = item.value;
                option.textContent = item.text;
                if (item.value == originalValue) option.selected = true;
                input.appendChild(option);
            });
        } else if (type === 'textarea') {
            input = document.createElement('textarea');
            input.className = 'form-control form-control-sm';
            input.rows = 3;
            input.value = originalValue;
        } else {
            input = document.createElement('input');
            input.type = type;
            input.className = 'form-control form-control-sm';
            input.value = originalValue;
        }
        
        input.style.width = '100%';
        input.dataset.originalValue = originalValue;
        
        span.replaceWith(input);
        input.focus();
        
        // Save on blur or Enter
        const saveEdit = async () => {
            const newValue = input.value;
            
            if (newValue === input.dataset.originalValue) {
                input.replaceWith(span);
                return;
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pk: pk,
                        attribute: attribute,
                        value: newValue
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    span.textContent = newValue;
                    input.replaceWith(span);
                    
                    // Show success message
                    showNotification('Updated successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            } catch (error) {
                showNotification(error.message || 'Error updating value', 'error');
                input.replaceWith(span);
            }
        };
        
        input.addEventListener('blur', saveEdit);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && type !== 'textarea') {
                e.preventDefault();
                saveEdit();
            } else if (e.key === 'Escape') {
                input.replaceWith(span);
            }
        });
    });
    
    // Simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} gridview-notification`;
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
})();
</script>
JS;
    }
}