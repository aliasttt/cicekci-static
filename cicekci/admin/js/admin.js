// Image preview
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm delete
function confirmDelete(message) {
    return confirm(message || 'Bu öğeyi silmek istediğinizden emin misiniz?');
}

// Form validation
function validateForm(formId) {
    var form = document.getElementById(formId);
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    form.classList.add('was-validated');
}

// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    var sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    }
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 3000);
    });
});

// Dynamic form fields
function addFormField(containerId, template) {
    var container = document.getElementById(containerId);
    var newField = template.cloneNode(true);
    newField.style.display = 'block';
    container.appendChild(newField);
}

function removeFormField(button) {
    var field = button.closest('.form-field');
    field.remove();
}

// AJAX form submission
function submitFormAjax(formId, successCallback) {
    var form = document.getElementById(formId);
    var formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (successCallback) {
                successCallback(data);
            }
        } else {
            alert(data.message || 'Bir hata oluştu!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu!');
    });
}

// Sortable tables
document.addEventListener('DOMContentLoaded', function() {
    var tables = document.querySelectorAll('.sortable');
    tables.forEach(function(table) {
        var headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(function(header) {
            header.addEventListener('click', function() {
                var index = Array.from(header.parentNode.children).indexOf(header);
                var rows = Array.from(table.querySelectorAll('tbody tr'));
                var direction = header.dataset.direction === 'asc' ? -1 : 1;
                
                rows.sort(function(a, b) {
                    var aValue = a.children[index].textContent;
                    var bValue = b.children[index].textContent;
                    return direction * aValue.localeCompare(bValue);
                });
                
                header.dataset.direction = direction === 1 ? 'asc' : 'desc';
                rows.forEach(function(row) {
                    table.querySelector('tbody').appendChild(row);
                });
            });
        });
    });
}); 