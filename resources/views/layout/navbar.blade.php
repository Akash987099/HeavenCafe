<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <nav class="navbar navbar-main navbar-expand-lg px-0 border shadow-none" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
      <div class="topbar-shell w-100">
        <div class="topbar-title">
          <div class="topbar-mobile-toggle d-xl-none">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav" aria-label="Toggle sidebar" aria-controls="sidenav-main" aria-expanded="false">
              <i class="fas fa-bars topbar-toggle-open"></i>
              <i class="fas fa-xmark topbar-toggle-close"></i>
            </a>
          </div>
          <button type="button" class="btn btn-sm mb-0 topbar-theme-toggle topbar-theme-toggle-mobile d-xl-none" data-theme-toggle aria-label="Switch to light mode" title="Switch to light mode">
            <i class="fas fa-sun" aria-hidden="true"></i>
            <span class="theme-toggle-text">Light</span>
          </button>
          <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-0 px-0">

        <li class="breadcrumb-item text-sm">
            <a class="opacity-6 text-dark" href="{{ route('index') }}">Admin</a>
        </li>

        @foreach(generateBreadcrumb() as $key => $crumb)
            <li class="breadcrumb-item text-sm {{ $loop->last ? 'text-dark active' : '' }}">
                
                @if(!$loop->last)
                    <a class="opacity-6 text-dark" href="{{ $crumb['url'] }}">
                        {{ $crumb['name'] }}
                    </a>
                @else
                    {{ $crumb['name'] }}
                @endif

            </li>
        @endforeach

    </ol>

    <h6 class="font-weight-bolder mb-0">
        {{ ucfirst(last(explode('.', request()->route()->getName()))) }}
    </h6>
</nav>
        </div>

        <div class="collapse navbar-collapse mt-0" id="navbar">
          <div class="topbar-actions ms-auto">
            <form class="topbar-search" method="GET" action="{{ route('product.index') }}">
              <i class="fas fa-search" aria-hidden="true"></i>
              <input
                type="text"
                class="form-control"
                id="topbarProductSearch"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search products..."
                autocomplete="off"
                data-product-search-input="true"
                data-search-url="{{ route('product.search') }}">
              <div class="topbar-search-results" id="topbarProductSearchResults" data-product-search-results="true"></div>
            </form>

            <ul class="navbar-nav justify-content-end align-items-center flex-row">
              <li class="nav-item pe-2 d-flex align-items-center">
                <button type="button" class="btn btn-sm mb-0 topbar-theme-toggle" data-theme-toggle aria-label="Switch to light mode" title="Switch to light mode">
                  <i class="fas fa-sun" aria-hidden="true"></i>
                  <span class="theme-toggle-text">Light Mode</span>
                </button>
              </li>

              <li class="nav-item d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body font-weight-bold px-0 topbar-profile">
                  <span class="topbar-profile-avatar">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                  </span>
                  <span class="topbar-profile-copy">
                    <small>Signed in as</small>
                    <span>{{ Auth::guard('admin')->user()->name }}</span>
                  </span>
                </a>
              </li>

              <li class="nav-item px-2 px-lg-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0 topbar-icon-link" title="Settings">
                  <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                </a>
              </li>

              <li class="nav-item dropdown d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0 topbar-icon-link" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                  <i class="fa fa-bell cursor-pointer"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
              <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                      <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">New message</span> from Laur
                      </h6>
                      <p class="text-xs text-secondary mb-0 ">
                        <i class="fa fa-clock me-1"></i>
                        13 minutes ago
                      </p>
                    </div>
                  </div>
                </a>
              </li>
              <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                      <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">New album</span> by Travis Scott
                      </h6>
                      <p class="text-xs text-secondary mb-0 ">
                        <i class="fa fa-clock me-1"></i>
                        1 day
                      </p>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                      <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>credit-card</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                              <g transform="translate(453.000000, 454.000000)">
                                <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                              </g>
                            </g>
                          </g>
                        </g>
                      </svg>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        Payment successfully completed
                      </h6>
                      <p class="text-xs text-secondary mb-0 ">
                        <i class="fa fa-clock me-1"></i>
                        2 days
                      </p>
                    </div>
                  </div>
                </a>
              </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <script>
    $(document).ready(function() {
      let currentUrl = window.location.href;
      $('a').each(function() {
        if (this.href === currentUrl) {
          $(this).addClass('active');
          $(this).closest('li').addClass('active');
        }
      });

      const body = document.body;
      const toggleButton = document.getElementById('iconNavbarSidenav');
      const closeButton = document.getElementById('iconSidenav');
      const backdrop = document.getElementById('sidenav-backdrop');
      const mobileBreakpoint = window.matchMedia('(max-width: 1199.98px)');

      function syncMobileSidenavState() {
        if (!toggleButton) {
          return;
        }

        const isOpen = mobileBreakpoint.matches && body.classList.contains('g-sidenav-pinned');
        toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }

      function openMobileSidenav() {
        if (!mobileBreakpoint.matches) {
          return;
        }

        body.classList.add('g-sidenav-pinned');
        syncMobileSidenavState();
      }

      function closeMobileSidenav() {
        if (!mobileBreakpoint.matches) {
          return;
        }

        body.classList.remove('g-sidenav-pinned');
        syncMobileSidenavState();
      }

      function toggleMobileSidenav(event) {
        if (!mobileBreakpoint.matches) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (body.classList.contains('g-sidenav-pinned')) {
          closeMobileSidenav();
          return;
        }

        openMobileSidenav();
      }

      if (toggleButton) {
        toggleButton.addEventListener('click', toggleMobileSidenav, true);
      }

      if (closeButton) {
        closeButton.addEventListener('click', function(event) {
          event.preventDefault();
          closeMobileSidenav();
        });
      }

      if (backdrop) {
        backdrop.addEventListener('click', closeMobileSidenav);
      }

      document.querySelectorAll('#sidenav-main .nav-link').forEach(function(link) {
        link.addEventListener('click', function() {
          if (!this.hasAttribute('data-bs-toggle')) {
            closeMobileSidenav();
          }
        });
      });

      window.addEventListener('resize', function() {
        if (!mobileBreakpoint.matches) {
          body.classList.remove('g-sidenav-pinned');
        }

        syncMobileSidenavState();
      });

      syncMobileSidenavState();

      if (!window.topbarProductSearchBound) {
        window.topbarProductSearchBound = true;

        const $searchForm = $('.topbar-search');
        const $searchInput = $('#topbarProductSearch');
        const $searchResults = $('#topbarProductSearchResults');
        let searchTimer = null;
        let activeRequest = null;
        const minimumCharacters = 3;

        function escapeHtml(value) {
          return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        function showSearchMessage(message) {
          if (!$searchResults.length) {
            return;
          }

          $searchResults
            .html('<div class="topbar-search-state">' + escapeHtml(message) + '</div>')
            .addClass('is-visible');
        }

        function hideSearchResults() {
          if (!$searchResults.length) {
            return;
          }

          $searchResults.removeClass('is-visible').empty();
        }

        function renderSearchResults(products) {
          if (!products.length) {
            showSearchMessage('No record found.');
            return;
          }

          const items = products.map(function(product) {
            const metaParts = [];

            if (product.sku_product_id) {
              metaParts.push('ID: ' + escapeHtml(product.sku_product_id));
            }

            if (product.sku_code) {
              metaParts.push('SKU: ' + escapeHtml(product.sku_code));
            }

            return (
              '<a class="topbar-search-result-item" href="' + escapeHtml(product.edit_url) + '">' +
                '<span class="topbar-search-result-title">' + escapeHtml(product.name) + '</span>' +
                '<span class="topbar-search-result-meta">' + (metaParts.join(' | ') || 'Open edit page') + '</span>' +
              '</a>'
            );
          }).join('');

          $searchResults.html(items).addClass('is-visible');
        }

        function requestProductSearch(query) {
          const searchUrl = $searchInput.data('search-url');

          if (!searchUrl) {
            showSearchMessage('Search route not available.');
            return;
          }

          if (activeRequest && activeRequest.readyState !== 4) {
            activeRequest.abort();
          }

          showSearchMessage('Searching...');

          activeRequest = $.ajax({
            url: searchUrl,
            type: 'GET',
            dataType: 'json',
            data: { q: query }
          }).done(function(response) {
            renderSearchResults(Array.isArray(response.data) ? response.data : []);
          }).fail(function(xhr, status) {
            if (status === 'abort') {
              return;
            }

            showSearchMessage('Search failed. Please try again.');
          });
        }

        if ($searchInput.length && $searchResults.length) {
          $searchInput.on('focus', function() {
            const query = ($(this).val() || '').trim();

            if (query.length < minimumCharacters) {
              showSearchMessage('Enter at least 3 characters.');
              return;
            }

            if ($searchResults.html().trim() !== '') {
              $searchResults.addClass('is-visible');
            }
          });

          $searchInput.on('input', function() {
            const query = ($(this).val() || '').trim();
            clearTimeout(searchTimer);

            if (query.length < minimumCharacters) {
              showSearchMessage('Enter at least 3 characters.');
              return;
            }

            searchTimer = setTimeout(function() {
              requestProductSearch(query);
            }, 250);
          });

          $searchInput.on('keydown', function(event) {
            if (event.key !== 'Enter') {
              return;
            }

            const firstResult = $searchResults.find('.topbar-search-result-item').first();
            if (firstResult.length && $searchResults.hasClass('is-visible')) {
              event.preventDefault();
              window.location.href = firstResult.attr('href');
            }
          });

          $(document).on('click.topbarProductSearch', function(event) {
            if (!$(event.target).closest('.topbar-search').length) {
              hideSearchResults();
            }
          });

          $searchForm.on('submit', function() {
            const query = ($searchInput.val() || '').trim();
            if (query.length === 0) {
              return false;
            }
          });
        }
      }
    });
  </script>
  
  <div class="container-fluid py-4 ">

   <div id="alert-container" class="fixed bottom-5 right-5 space-y-3 z-50"></div>
