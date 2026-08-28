<div id="alert-container" class="fixed top-5 right-5 space-y-3 z-50"></div>

<script>
    (function() {
        const storageKey = 'theme-mode';

        function applyTheme(theme) {
            const activeTheme = theme === 'light' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', activeTheme);

            if (document.body) {
                document.body.classList.toggle('dark-mode', activeTheme === 'dark');
            }

            const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
            if (!toggleButtons.length) {
                return;
            }

            const switchLabel = activeTheme === 'dark' ? 'light' : 'dark';

            toggleButtons.forEach(function(toggleButton) {
                const icon = toggleButton.querySelector('i');
                const label = toggleButton.querySelector('.theme-toggle-text');
                const isMobileToggle = toggleButton.classList.contains('topbar-theme-toggle-mobile');

                toggleButton.setAttribute('aria-label', 'Switch to ' + switchLabel + ' mode');
                toggleButton.setAttribute('title', 'Switch to ' + switchLabel + ' mode');

                if (icon) {
                    icon.className = activeTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                }

                if (label) {
                    if (isMobileToggle) {
                        label.textContent = activeTheme === 'dark' ? 'Light' : 'Dark';
                    } else {
                        label.textContent = activeTheme === 'dark' ? 'Light Mode' : 'Dark Mode';
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            applyTheme(localStorage.getItem(storageKey) || 'dark');

            const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
            if (!toggleButtons.length) {
                return;
            }

            toggleButtons.forEach(function(toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
                    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem(storageKey, nextTheme);
                    applyTheme(nextTheme);
                });
            });
        });
    })();
</script>
<script>
   // Custom function to show top-right alert box safely
        function showNotification(type, message) {
            let container = document.getElementById('custom-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'custom-toast-container';
                container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
                document.body.appendChild(container);
            }

            const alertBox = document.createElement('div');
            const bgColor = type === 'success' ? '#2dce89' : '#f5365c';
            alertBox.style.cssText = `background-color: ${bgColor}; color: white; padding: 15px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: sans-serif; font-size: 14px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease-in-out; display: inline-block; pointer-events: auto;`;
            alertBox.innerHTML = `<strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> &nbsp; ${message}`;
            
            container.appendChild(alertBox);

            setTimeout(() => {
                alertBox.style.opacity = '1';
                alertBox.style.transform = 'translateX(0)';
            }, 10);

            setTimeout(() => {
                alertBox.style.opacity = '0';
                alertBox.style.transform = 'translateX(100%)';
                setTimeout(() => alertBox.remove(), 300);
            }, 3000);
        }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            showAlert('success', 'Success!', "{{ session('success') }}");
        @elseif (session('error'))
            showAlert('danger', 'Error!', "{{ session('error') }}");
        @elseif (session('warning'))
            showAlert('warning', 'Warning!', "{{ session('warning') }}");
        @elseif (session('info'))
            showAlert('info', 'Info!', "{{ session('info') }}");
        @endif

        @if ($errors->any())
            showAlert('danger', 'Validation Error!', '{{ $errors->first() }}');
        @endif

        document.querySelectorAll('.table-responsive').forEach(function(wrapper) {
            if (wrapper.dataset.gridReady === '1') {
                return;
            }

            const table = wrapper.querySelector('table');
            const pagination = wrapper.querySelector(':scope > .mt-4');

            if (!table) {
                return;
            }

            const scrollArea = document.createElement('div');
            scrollArea.className = 'table-scroll-area';

            wrapper.insertBefore(scrollArea, table);
            scrollArea.appendChild(table);

            if (pagination) {
                pagination.classList.add('table-pagination-footer');
                wrapper.appendChild(pagination);
            }

            wrapper.dataset.gridReady = '1';
        });

    });
</script>

<footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="" class="font-weight-bold" target="_blank">Arya web coding</a>
                for a better web.
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
  
  <!--   Core JS Files   -->
  <script src="{{asset('assets/admin/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/admin/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/admin/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/admin/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/admin/js/plugins/chartjs.min.js')}}"></script>
  
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('assets/admin/js/soft-ui-dashboard.min.js?v=1.0.7')}}"></script>

</body>

</html>
