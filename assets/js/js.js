const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');
const sidebar = document.getElementById('sidebar');

allDropdown.forEach(item => {
    const a = item.parentElement.querySelector('a:first-child');
    a.addEventListener('click', function (e) {
        e.preventDefault();

        if (!this.classList.contains('active')) {
            allDropdown.forEach(i => {
                const aLink = i.parentElement.querySelector('a:first-child');

                aLink.classList.remove('active');
                i.classList.remove('show');
            })
        }

        this.classList.toggle('active');
        item.classList.toggle('show');
    })
})





const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

if (sidebar.classList.contains('hide')) {
    allSideDivider.forEach(item => {
        item.textContent = '-'
    })
    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.remove('active');
        item.classList.remove('show');
    })
} else {
    allSideDivider.forEach(item => {
        item.textContent = item.dataset.text;
    })
}

toggleSidebar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');

    if (sidebar.classList.contains('hide')) {
        allSideDivider.forEach(item => {
            item.textContent = '-'
        })

        allDropdown.forEach(item => {
            const a = item.parentElement.querySelector('a:first-child');
            a.classList.remove('active');
            item.classList.remove('show');
        })
    } else {
        allSideDivider.forEach(item => {
            item.textContent = item.dataset.text;
        })
    }
})

// Initialize Select2 globally
function initSelect2() {
    if (typeof jQuery !== 'undefined' && $.fn.select2) {
        $('select:not(.no-select2)').each(function() {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) return; // Already initialized
            
            var options = {
                theme: 'bootstrap-5',
                width: '100%'
            };
            
            var $modal = $select.closest('.modal');
            if ($modal.length > 0) {
                options.dropdownParent = $modal;
            }
            
            $select.select2(options).on('select2:select select2:unselect select2:clear', function () {
                this.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', initSelect2);