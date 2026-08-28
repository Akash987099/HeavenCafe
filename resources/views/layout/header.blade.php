<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script>
        (function() {
            var storedTheme = localStorage.getItem('theme-mode');
            var theme = storedTheme === 'light' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon.png">
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>
        {{setting('web_name')->name}}
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/admin/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
    <script defer data-site="aryaai.cloud" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/message.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}?v={{ @filemtime(public_path('assets/js/search.js')) }}"></script>
    <script src="{{ asset('assets/js/delete.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    :root {
        --sidebar-width: 264px;
        --layout-gap: 0px;
        --topbar-height: 62px;
        --surface-radius: 20px;
        --surface-border: #d9e4ef;
        --surface-shadow: 0 16px 38px rgba(15, 23, 42, 0.08);
        --brand-navy: #163a63;
        --brand-gold: #c98a2e;
        --sidebar-text: #5b6f88;
        --sidebar-active-bg: linear-gradient(135deg, #eff5ff 0%, #f8fbff 100%);
        --theme-toggle-dark: #111827;
        --theme-toggle-light: #f8fafc;
    }

    body.bg-gray-100 {
        background: #f5f7fb;
        margin: 0;
        overflow-x: hidden;
    }

    html[data-theme="dark"] {
        color-scheme: dark;
    }

    html[data-theme="dark"] body.bg-gray-100,
    body.bg-gray-100.dark-mode {
        background: #0f172a !important;
        color: #e2e8f0;
    }

    html[data-theme="dark"] .navbar-main,
    html[data-theme="dark"] .card,
    html[data-theme="dark"] .table-responsive,
    html[data-theme="dark"] .dropdown-menu,
    body.dark-mode .navbar-main,
    body.dark-mode .card,
    body.dark-mode .table-responsive,
    body.dark-mode .dropdown-menu {
        background: #111827 !important;
        color: #e2e8f0 !important;
        border-color: #334155 !important;
    }

    html[data-theme="dark"] .text-dark,
    html[data-theme="dark"] h6,
    html[data-theme="dark"] .breadcrumb-item,
    html[data-theme="dark"] .nav-link,
    html[data-theme="dark"] .form-control,
    html[data-theme="dark"] td,
    html[data-theme="dark"] th,
    body.dark-mode .text-dark,
    body.dark-mode h6,
    body.dark-mode .breadcrumb-item,
    body.dark-mode .nav-link,
    body.dark-mode .form-control,
    body.dark-mode td,
    body.dark-mode th {
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] input,
    html[data-theme="dark"] select,
    html[data-theme="dark"] textarea,
    body.dark-mode input,
    body.dark-mode select,
    body.dark-mode textarea {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }

    .topbar-theme-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 0.9rem;
        border: 1px solid #dbe3ef;
        border-radius: 999px;
        background: var(--theme-toggle-light);
        color: #0f172a;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        transition: all 0.2s ease;
    }

    .topbar-theme-toggle:hover {
        transform: translateY(-1px);
    }

    .topbar-theme-toggle-mobile {
        display: none;
        min-height: 2.2rem;
        padding: 0.45rem 0.7rem;
        font-size: 0.72rem;
        line-height: 1;
        white-space: nowrap;
    }

    html[data-theme="dark"] .topbar-theme-toggle,
    body.dark-mode .topbar-theme-toggle {
        background: var(--theme-toggle-dark);
        color: #f8fafc;
        border-color: #334155;
        box-shadow: 0 12px 32px rgba(2, 6, 23, 0.35);
    }

    #sidenav-main {
        position: fixed !important;
        top: var(--layout-gap);
        left: var(--layout-gap);
        bottom: var(--layout-gap);
        width: var(--sidebar-width);
        min-width: var(--sidebar-width);
        max-width: var(--sidebar-width);
        height: calc(100vh - (var(--layout-gap) * 2));
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: none !important;
        z-index: 1030;
        background: #ffffff !important;
        border: 1px solid var(--surface-border) !important;
        border-right: 1px solid var(--surface-border) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    #sidenav-main .sidenav-header {
        padding: 0.85rem 0.85rem 0.65rem;
    }

    .sidebar-brand-panel {
        padding: 0;
        border-radius: 0;
        background: transparent;
        border: 0;
    }

    .sidebar-brand-copy {
        margin-top: 0.55rem;
        padding-left: 0.2rem;
        text-align: left;
    }

    .sidebar-brand-copy strong {
        display: block;
        color: var(--brand-navy);
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .sidebar-brand-copy span {
        display: block;
        margin-top: 0.2rem;
        color: #7a8798;
        font-size: 0.72rem;
        font-weight: 500;
    }

    #sidenav-main .navbar-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        min-height: 56px;
        margin: 0;
        padding: 0.35rem 0.2rem;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
    }

    #sidenav-main .navbar-brand-img {
        max-height: 46px;
        width: auto;
        object-fit: contain;
    }

    #sidenav-main hr.horizontal.dark {
        margin: 0 0.85rem 0.55rem;
        background-image: none;
        background-color: #e8eef6;
        height: 1px;
        opacity: 1;
    }

    #sidenav-main .sidebar-menu {
        flex: 1 1 auto;
        min-height: 0;
        display: block !important;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        height: auto;
        max-height: none;
        overscroll-behavior-y: contain;
        scrollbar-width: thin;
        scrollbar-color: #c7d3e2 transparent;
    }

    #sidenav-main .sidebar-menu::-webkit-scrollbar {
        width: 6px;
    }

    #sidenav-main .sidebar-menu::-webkit-scrollbar-thumb {
        background-color: #c7d3e2;
        border-radius: 999px;
    }

    #sidenav-main .navbar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        width: 100%;
        padding: 0 0.65rem 0.85rem;
        margin-bottom: 0;
    }

    #sidenav-main .nav-item {
        width: 100%;
        min-width: 0;
    }

    #sidenav-main .nav-item-section {
        padding: 0.7rem 0.7rem 0.35rem;
    }

    #sidenav-main .nav-item-section span {
        display: inline-flex;
        align-items: center;
        font-size: 0.67rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #8da0b6;
    }

    #sidenav-main .nav-link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        min-width: 0;
        min-height: 46px;
        margin: 0;
        padding: 0.62rem 0.75rem;
        border-radius: 12px;
        color: var(--sidebar-text);
        gap: 0.2rem;
        transition: background-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
    }

    #sidenav-main .nav-link:hover {
        background: #f5f8fd;
        color: #24446e;
    }

    #sidenav-main .nav-link.active {
        background: #eff5ff;
        color: #173a63;
        box-shadow: inset 0 0 0 1px #dbe6f7;
    }

    #sidenav-main .icon.icon-shape {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px;
        border-radius: 10px !important;
        box-shadow: none !important;
        background: #f7f9fc !important;
        border: 1px solid #e5edf6;
    }

    #sidenav-main .nav-link-text {
        color: inherit !important;
        font-size: 0.88rem;
        font-weight: 500;
        flex: 0 1 auto;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #sidenav-main .nav-link[data-bs-toggle="collapse"]::after {
        content: "";
        width: 0.5rem;
        height: 0.5rem;
        border-right: 2px solid #97a6b9;
        border-bottom: 2px solid #97a6b9;
        transform: rotate(45deg);
        color: #97a6b9;
        margin-left: auto;
        margin-right: 0.15rem;
        transition: transform 0.18s ease, border-color 0.18s ease;
    }

    #sidenav-main .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after {
        transform: rotate(225deg);
        border-color: #173a63;
    }

    .main-content {
        margin-left: var(--sidebar-width) !important;
        width: calc(100% - var(--sidebar-width)) !important;
        min-height: 100vh;
    }

    #navbarBlur {
        position: fixed !important;
        top: var(--layout-gap);
        left: var(--sidebar-width);
        right: 0;
        z-index: 1020;
        min-height: var(--topbar-height);
        background: #ffffff !important;
        border: 1px solid var(--surface-border) !important;
        border-left: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }

    #navbarBlur::before {
        display: none;
    }

    .main-content .navbar.navbar-main {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: auto !important;
    }

    #navbarBlur .container-fluid {
        min-height: var(--topbar-height);
        align-items: center;
    }

    .topbar-shell {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .topbar-title {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        min-width: 0;
    }

    .topbar-mobile-toggle .nav-link {
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #dbe4ef;
        color: #24446e !important;
        position: relative;
        font-size: 1rem;
    }

    .topbar-mobile-toggle .nav-link i {
        position: absolute;
        transition: opacity 0.18s ease, transform 0.18s ease;
    }

    .topbar-mobile-toggle .topbar-toggle-close {
        opacity: 0;
        transform: scale(0.8);
    }

    body.g-sidenav-pinned .topbar-mobile-toggle .topbar-toggle-open {
        opacity: 0;
        transform: scale(0.8);
    }

    body.g-sidenav-pinned .topbar-mobile-toggle .topbar-toggle-close {
        opacity: 1;
        transform: scale(1);
    }

    .topbar-title .breadcrumb-item,
    .topbar-title .breadcrumb-item a {
        color: #7c8da3 !important;
        font-size: 0.78rem;
        font-weight: 500;
    }

    .topbar-title h6 {
        color: var(--brand-navy);
        font-size: 1rem;
        letter-spacing: -0.01em;
    }

    .topbar-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
        width: 100%;
        overflow: visible;
    }

    .topbar-search {
        position: relative;
        width: min(100%, 360px);
        overflow: visible;
    }

    .topbar-search i {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: #8da0b6;
        font-size: 0.85rem;
    }

    .topbar-search .form-control {
        height: 40px;
        border: 1px solid #d5dfeb;
        border-radius: 12px;
        padding: 0.55rem 0.9rem 0.55rem 2.45rem;
        background: #ffffff;
        box-shadow: none;
    }

    .topbar-search .form-control:focus {
        border-color: #99b7e0;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .topbar-search-results {
        position: absolute;
        top: calc(100% + 0.45rem);
        left: 0;
        right: 0;
        display: none;
        padding: 0.35rem;
        background: #ffffff;
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        z-index: 1060;
    }

    .topbar-search-results.is-visible {
        display: block;
    }

    #navbar,
    #navbarBlur,
    .navbar-main,
    .navbar-main .container-fluid,
    .topbar-shell {
        overflow: visible !important;
    }

    .topbar-search-result-item,
    .topbar-search-state {
        display: flex;
        flex-direction: column;
        gap: 0.18rem;
        width: 100%;
        padding: 0.7rem 0.85rem;
        border-radius: 10px;
    }

    .topbar-search-result-item {
        text-decoration: none;
        transition: background-color 0.18s ease;
    }

    .topbar-search-result-item:hover {
        background: #f4f8fc;
    }

    .topbar-search-result-title {
        color: #1e293b;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .topbar-search-result-meta,
    .topbar-search-state {
        color: #64748b;
        font-size: 0.74rem;
        font-weight: 500;
    }

    .topbar-actions .navbar-nav {
        gap: 0.35rem;
    }

    .topbar-profile {
        display: inline-flex !important;
        align-items: center;
        gap: 0.75rem;
        padding: 0.25rem 0.4rem 0.25rem 0.25rem !important;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #dbe4ef;
    }

    .topbar-profile-avatar {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-navy), #2d5d8f);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(22, 58, 99, 0.2);
    }

    .topbar-profile-copy {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    .topbar-profile-copy small {
        color: #8ea0b6;
        font-size: 0.62rem;
        font-weight: 600;
    }

    .topbar-profile-copy span {
        color: #32465d;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .topbar-icon-link {
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #dbe4ef;
        color: #58708f !important;
    }

    .topbar-icon-link:hover {
        background: #eef4fb;
        color: var(--brand-navy) !important;
    }

    .main-content>.container-fluid.py-4 {
        padding-top: calc(var(--topbar-height) + 2.2rem) !important;
        padding-left: 1.25rem !important;
        padding-right: 1.25rem !important;
        padding-bottom: 1rem !important;
    }

    .main-content>.container-fluid.py-4>.row:first-child,
    .main-content>.container-fluid.py-4>.card:first-child,
    .main-content>.container-fluid.py-4>.container-fluid:first-child {
        margin-top: 1.15rem;
    }

    .side-submenu {
        margin: 0.15rem 0 0.45rem;
        width: 100%;
        overflow-x: hidden;
    }

    .side-submenu .nav-link {
        min-height: 38px;
        margin-left: 0;
        padding: 0.55rem 0.8rem 0.55rem 2.65rem;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    .side-submenu .nav-link i {
        width: 15px;
        margin-right: 0.45rem;
        text-align: center;
    }

    .card {
        border: 1px solid #d8e1eb;
        border-radius: 1rem !important;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05) !important;
        overflow: hidden;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
        gap: 0.75rem;
        padding: 0.95rem 1rem !important;
        background: linear-gradient(180deg, #fbfdff 0%, #f1f5f9 100%);
        border-bottom: 1px solid #dce4ee !important;
    }

    .card-header h6 {
        margin: 0;
        color: #334155;
        font-size: 0.84rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        display: flex;
        align-items: center;
        min-height: 2rem;
        flex: 0 0 auto;
    }

    .card-header .btn,
    .card-header .btn-sm {
        min-height: 2rem;
        padding: 0.38rem 0.78rem;
        border-radius: 0.5rem;
        font-size: 0.74rem;
        font-weight: 600;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.12) !important;
    }

    .card-header .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        border-color: #2563eb;
    }

    .card-header>.d-flex,
    .card-header .d-flex.align-items-center {
        display: flex !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        justify-content: flex-end;
        gap: 0.55rem !important;
        margin-left: auto;
        min-height: 2rem;
    }

    .card-header input[type="text"],
    .card-header input[type="search"] {
        display: block;
        min-width: 180px;
        max-width: 240px;
        margin: 0 !important;
    }

    .card-header-toolbar {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 0.55rem !important;
        width: auto !important;
        margin-left: auto !important;
        flex: 0 0 auto;
    }

    .card-header-search {
        width: min(100%, 220px);
    }

    .category-card-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: nowrap !important;
        gap: 0.75rem;
    }

    .category-card-header-top {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.75rem;
        width: auto;
        flex: 0 1 auto;
    }

    .category-card-add-btn {
        flex: 0 0 auto;
    }

    .card-header a.btn,
    .card-header button.btn,
    .card-header .btn-sm {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
        white-space: nowrap;
    }

    .card-body {
        padding: 1rem !important;
        background: transparent;
    }

    .card-body.px-0 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .card-body.pt-0 {
        padding-top: 0 !important;
    }

    .table-responsive {
        overflow: visible;
        max-height: none;
        border: 1px solid #dbe3ed;
        border-radius: 0.75rem;
        background: #fff;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        padding: 0;
        margin-top: 0.8rem;
    }

    .table-scroll-area {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 470px;
        border-bottom: 1px solid #e3eaf2;
        scrollbar-width: thin;
        scrollbar-color: #b8c4d6 #eef3f8;
    }

    .table-scroll-area::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-scroll-area::-webkit-scrollbar-track {
        background: #eef3f8;
        border-radius: 999px;
    }

    .table-scroll-area::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #c7d2df 0%, #aebdce 100%);
        border-radius: 999px;
        border: 1px solid #eef3f8;
    }

    .table-scroll-area::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #b7c4d3 0%, #99adc3 100%);
    }

    .table-scroll-area::-webkit-scrollbar-corner {
        background: #eef3f8;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        background: #fff;
    }

    .table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: linear-gradient(180deg, #f8fbff 0%, #edf3f8 100%);
        color: #64748b !important;
        font-size: 0.69rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.03em;
        text-transform: none;
        padding: 0.72rem 0.8rem !important;
        border-top: 0 !important;
        border-bottom: 1px solid #dbe3ed !important;
        border-right: 1px solid #dbe3ed !important;
        white-space: nowrap;
    }

    .table thead th:first-child {
        border-left: 0 !important;
    }

    .table thead th:last-child {
        border-right: 0 !important;
    }

    .table tbody td {
        background: #fff;
        color: #475569;
        font-size: 0.74rem !important;
        font-weight: 400 !important;
        line-height: 1.35;
        padding: 0.65rem 0.8rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #e6edf5 !important;
        border-right: 1px solid #e6edf5 !important;
        white-space: nowrap;
    }

    .table tbody tr:nth-child(even) td {
        background: #fcfdff;
    }

    .table tbody tr:hover td {
        background: #f6faff;
    }

    .table tbody td:last-child {
        border-right: 0 !important;
    }

    .table tbody td p,
    .table tbody td span,
    .table tbody td a,
    .table tbody td div,
    .table tbody td strong {
        font-size: inherit !important;
        font-weight: inherit !important;
        margin-bottom: 0 !important;
    }

    .table tbody td a {
        color: #2563eb !important;
        text-decoration: none;
        font-weight: 500 !important;
    }

    .table tbody td a:hover {
        color: #1d4ed8 !important;
        text-decoration: underline;
    }

    .dataTables_wrapper .dataTables_filter input,
    #searchInput {
        min-height: 2rem;
        border: 1px solid #d5deea !important;
        border-radius: 0.55rem !important;
        background: #fff !important;
        font-size: 0.74rem !important;
        color: #4b5d73 !important;
        padding: 0.42rem 0.72rem !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    }

    .dataTables_wrapper .dataTables_filter input:focus,
    #searchInput:focus {
        border-color: #90b4ff !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(41, 98, 255, 0.08) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_wrapper .dataTables_length select {
        font-size: 0.74rem !important;
    }

    .pagination-shell {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        padding: 0.85rem 1rem;
        margin-top: 0;
        border-top: 0;
        background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
    }

    .pagination-meta {
        color: #61748a;
        font-size: 0.73rem;
        font-weight: 400;
    }

    .pagination-meta strong {
        color: #405266;
        font-weight: 600;
    }

    .pagination-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .pagination-link,
    .pagination-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.8rem;
        height: 1.8rem;
        padding: 0 0.52rem;
        border: 1px solid #d5deea;
        border-radius: 0.45rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        color: #54657b;
        font-size: 0.71rem;
        font-weight: 500;
        text-decoration: none;
        line-height: 1;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .pagination-link:hover {
        background: #f4f8ff;
        color: #275df5;
        border-color: #b9cbf6;
    }

    .pagination-link.is-active {
        background: linear-gradient(180deg, #edf4ff 0%, #e0edff 100%);
        color: #275df5;
        border-color: #b9cbf6;
    }

    .pagination-link.is-disabled {
        color: #9aa8b8;
        background: #f8fafc;
        pointer-events: none;
    }

    .pagination-link.is-nav {
        min-width: 2.2rem;
        font-size: 0.82rem;
    }

    .mt-4:has(.pagination-shell) {
        margin-top: 0.75rem !important;
    }

    .sidenav-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.38);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s ease, visibility 0.22s ease;
        z-index: 1035;
    }

    html[data-theme="dark"] #sidenav-main,
    body.dark-mode #sidenav-main {
        background: linear-gradient(180deg, #0f172a 0%, #111c34 100%) !important;
        border-color: #22304a !important;
    }

    html[data-theme="dark"] #sidenav-main hr.horizontal.dark,
    body.dark-mode #sidenav-main hr.horizontal.dark {
        background-color: #22304a;
    }

    html[data-theme="dark"] .sidebar-brand-copy strong,
    body.dark-mode .sidebar-brand-copy strong {
        color: #f8fafc;
    }

    html[data-theme="dark"] .sidebar-brand-copy span,
    html[data-theme="dark"] #sidenav-main .nav-item-section span,
    body.dark-mode .sidebar-brand-copy span,
    body.dark-mode #sidenav-main .nav-item-section span {
        color: #8fa2bd;
    }

    html[data-theme="dark"] #sidenav-main .nav-link,
    body.dark-mode #sidenav-main .nav-link {
        color: #c7d2e3;
    }

    html[data-theme="dark"] #sidenav-main .nav-link:hover,
    body.dark-mode #sidenav-main .nav-link:hover {
        background: rgba(59, 130, 246, 0.14);
        color: #f8fafc;
    }

    html[data-theme="dark"] #sidenav-main .nav-link.active,
    body.dark-mode #sidenav-main .nav-link.active {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.22) 0%, rgba(37, 99, 235, 0.16) 100%);
        color: #eff6ff;
        box-shadow: inset 0 0 0 1px rgba(96, 165, 250, 0.26);
    }

    html[data-theme="dark"] #sidenav-main .icon.icon-shape,
    body.dark-mode #sidenav-main .icon.icon-shape {
        background: #15233d !important;
        border-color: #233555;
    }

    html[data-theme="dark"] #sidenav-main .nav-link[data-bs-toggle="collapse"]::after,
    body.dark-mode #sidenav-main .nav-link[data-bs-toggle="collapse"]::after {
        border-color: #8fa2bd;
    }

    html[data-theme="dark"] #sidenav-main .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after,
    body.dark-mode #sidenav-main .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after {
        border-color: #dbeafe;
    }

    html[data-theme="dark"] #navbarBlur,
    body.dark-mode #navbarBlur {
        background: rgba(9, 15, 29, 0.96) !important;
        border-color: #22304a !important;
    }

    html[data-theme="dark"] .topbar-mobile-toggle .nav-link,
    html[data-theme="dark"] .topbar-profile,
    html[data-theme="dark"] .topbar-icon-link,
    body.dark-mode .topbar-mobile-toggle .nav-link,
    body.dark-mode .topbar-profile,
    body.dark-mode .topbar-icon-link {
        background: #101a30;
        border-color: #263753;
        color: #d6e2f2 !important;
    }

    html[data-theme="dark"] .topbar-title .breadcrumb-item,
    html[data-theme="dark"] .topbar-title .breadcrumb-item a,
    body.dark-mode .topbar-title .breadcrumb-item,
    body.dark-mode .topbar-title .breadcrumb-item a {
        color: #8fa2bd !important;
    }

    html[data-theme="dark"] .topbar-title h6,
    body.dark-mode .topbar-title h6 {
        color: #f8fafc;
    }

    html[data-theme="dark"] .topbar-search i,
    body.dark-mode .topbar-search i {
        color: #8fa2bd;
    }

    html[data-theme="dark"] .topbar-search .form-control,
    body.dark-mode .topbar-search .form-control {
        background: #0f172a;
        border-color: #263753;
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] .topbar-search .form-control::placeholder,
    html[data-theme="dark"] .card-header input::placeholder,
    html[data-theme="dark"] #searchInput::placeholder,
    body.dark-mode .topbar-search .form-control::placeholder,
    body.dark-mode .card-header input::placeholder,
    body.dark-mode #searchInput::placeholder {
        color: #7f93af;
    }

    html[data-theme="dark"] .topbar-search .form-control:focus,
    body.dark-mode .topbar-search .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
    }

    html[data-theme="dark"] .topbar-search-results,
    body.dark-mode .topbar-search-results {
        background: #111827;
        border-color: #334155;
        box-shadow: 0 18px 44px rgba(2, 6, 23, 0.45);
    }

    html[data-theme="dark"] .topbar-search-result-item:hover,
    body.dark-mode .topbar-search-result-item:hover {
        background: #1e293b;
    }

    html[data-theme="dark"] .topbar-search-result-title,
    body.dark-mode .topbar-search-result-title {
        color: #e2e8f0;
    }

    html[data-theme="dark"] .topbar-search-result-meta,
    html[data-theme="dark"] .topbar-search-state,
    body.dark-mode .topbar-search-result-meta,
    body.dark-mode .topbar-search-state {
        color: #94a3b8;
    }

    html[data-theme="dark"] .topbar-profile-copy small,
    body.dark-mode .topbar-profile-copy small {
        color: #8fa2bd;
    }

    html[data-theme="dark"] .topbar-profile-copy span,
    body.dark-mode .topbar-profile-copy span {
        color: #f8fafc;
    }

    html[data-theme="dark"] .topbar-icon-link:hover,
    body.dark-mode .topbar-icon-link:hover {
        background: #16233d;
        color: #ffffff !important;
    }

    html[data-theme="dark"] .main-content > .container-fluid.py-4,
    body.dark-mode .main-content > .container-fluid.py-4 {
        background: transparent;
    }

    html[data-theme="dark"] .card,
    body.dark-mode .card {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%) !important;
        border-color: #22304a !important;
        box-shadow: 0 18px 45px rgba(2, 6, 23, 0.28) !important;
    }

    html[data-theme="dark"] .card-header,
    body.dark-mode .card-header {
        background: linear-gradient(180deg, #162033 0%, #121b2c 100%);
        border-bottom-color: #22304a !important;
    }

    html[data-theme="dark"] .card-header h6,
    body.dark-mode .card-header h6 {
        color: #f8fafc;
    }

    html[data-theme="dark"] .card-body,
    body.dark-mode .card-body {
        color: #d6e2f2;
    }

    html[data-theme="dark"] .table-responsive,
    body.dark-mode .table-responsive {
        background: #0f172a !important;
        border-color: #22304a !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
    }

    html[data-theme="dark"] .table-scroll-area,
    body.dark-mode .table-scroll-area {
        border-bottom-color: #22304a;
        scrollbar-color: #334866 #0f172a;
    }

    html[data-theme="dark"] .table-scroll-area::-webkit-scrollbar-track,
    html[data-theme="dark"] .table-scroll-area::-webkit-scrollbar-corner,
    body.dark-mode .table-scroll-area::-webkit-scrollbar-track,
    body.dark-mode .table-scroll-area::-webkit-scrollbar-corner {
        background: #0f172a;
    }

    html[data-theme="dark"] .table-scroll-area::-webkit-scrollbar-thumb,
    body.dark-mode .table-scroll-area::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #334866 0%, #425b82 100%);
        border-color: #0f172a;
    }

    html[data-theme="dark"] .table,
    body.dark-mode .table {
        background: #0f172a;
    }

    html[data-theme="dark"] .table thead th,
    body.dark-mode .table thead th {
        background: linear-gradient(180deg, #172235 0%, #131d2f 100%);
        color: #93a8c5 !important;
        border-bottom-color: #22304a !important;
        border-right-color: #22304a !important;
    }

    html[data-theme="dark"] .table tbody td,
    body.dark-mode .table tbody td {
        background: #0f172a;
        color: #d6e2f2;
        border-bottom-color: #1e293b !important;
        border-right-color: #1e293b !important;
    }

    html[data-theme="dark"] .table tbody tr:nth-child(even) td,
    body.dark-mode .table tbody tr:nth-child(even) td {
        background: #111b2d;
    }

    html[data-theme="dark"] .table tbody tr:hover td,
    body.dark-mode .table tbody tr:hover td {
        background: #16233a;
    }

    html[data-theme="dark"] .table tbody td a,
    body.dark-mode .table tbody td a {
        color: #60a5fa !important;
    }

    html[data-theme="dark"] .table tbody td a:hover,
    body.dark-mode .table tbody td a:hover {
        color: #93c5fd !important;
    }

    html[data-theme="dark"] .dataTables_wrapper .dataTables_filter input,
    html[data-theme="dark"] #searchInput,
    body.dark-mode .dataTables_wrapper .dataTables_filter input,
    body.dark-mode #searchInput {
        background: #101a30 !important;
        border-color: #263753 !important;
        color: #d6e2f2 !important;
        box-shadow: none !important;
    }

    html[data-theme="dark"] .dataTables_wrapper .dataTables_filter input:focus,
    html[data-theme="dark"] #searchInput:focus,
    body.dark-mode .dataTables_wrapper .dataTables_filter input:focus,
    body.dark-mode #searchInput:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.16) !important;
    }

    html[data-theme="dark"] .pagination-shell,
    body.dark-mode .pagination-shell {
        background: linear-gradient(180deg, #121b2c 0%, #0f172a 100%);
    }

    html[data-theme="dark"] .pagination-meta,
    body.dark-mode .pagination-meta {
        color: #8fa2bd;
    }

    html[data-theme="dark"] .pagination-meta strong,
    body.dark-mode .pagination-meta strong {
        color: #e2e8f0;
    }

    html[data-theme="dark"] .pagination-link,
    html[data-theme="dark"] .pagination-ellipsis,
    body.dark-mode .pagination-link,
    body.dark-mode .pagination-ellipsis {
        background: #101a30;
        border-color: #263753;
        color: #c7d2e3;
        box-shadow: none;
    }

    html[data-theme="dark"] .pagination-link:hover,
    body.dark-mode .pagination-link:hover {
        background: #16233d;
        color: #f8fafc;
        border-color: #3b82f6;
    }

    html[data-theme="dark"] .pagination-link.is-active,
    body.dark-mode .pagination-link.is-active {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #ffffff;
        border-color: #2563eb;
    }

    html[data-theme="dark"] .pagination-link.is-disabled,
    body.dark-mode .pagination-link.is-disabled {
        background: #0f172a;
        color: #60738f;
        border-color: #1f2c43;
    }

    html[data-theme="dark"] .footer .copyright,
    html[data-theme="dark"] .footer .copyright a,
    body.dark-mode .footer .copyright,
    body.dark-mode .footer .copyright a {
        color: #7f93af !important;
    }

    html[data-theme="dark"] .text-secondary,
    html[data-theme="dark"] .text-muted,
    html[data-theme="dark"] .opacity-6,
    body.dark-mode .text-secondary,
    body.dark-mode .text-muted,
    body.dark-mode .opacity-6 {
        color: #8fa2bd !important;
        opacity: 1 !important;
    }

    @media (max-width: 1199.98px) {
        #sidenav-main {
            width: min(280px, 86vw);
            min-width: min(280px, 86vw);
            max-width: min(280px, 86vw);
            left: 0;
            top: 0;
            bottom: 0;
            height: 100vh;
            border-right: 1px solid var(--surface-border) !important;
            border-top-right-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
            transform: translateX(-100%) !important;
            transition: transform 0.24s ease;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18) !important;
            z-index: 1045;
        }

        body.g-sidenav-pinned {
            overflow: hidden;
        }

        body.g-sidenav-pinned #sidenav-main {
            transform: translateX(0) !important;
        }

        body.g-sidenav-pinned .sidenav-backdrop {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }

        #navbarBlur {
            left: 0;
            right: 0;
            top: 0;
            margin-left: 0;
            border-top-left-radius: 1rem !important;
            border-bottom-left-radius: 1rem !important;
        }

        #navbarBlur::before {
            display: none;
        }

        .topbar-shell {
            flex-direction: row;
            align-items: center;
        }

        .topbar-actions {
            width: auto;
            margin-left: auto;
        }

        .topbar-title {
            width: 100%;
            min-width: 0;
        }

        .topbar-actions .navbar-nav {
            justify-content: flex-end;
        }

        .topbar-search {
            display: none;
        }

        .topbar-profile-copy {
            display: none;
        }

        .table-responsive {
            max-height: none;
        }

        .table-scroll-area {
            max-height: 420px;
        }

        .table thead th,
        .table tbody td {
            padding-left: 0.65rem !important;
            padding-right: 0.65rem !important;
        }

        .pagination-shell {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 767.98px) {
        #navbarBlur .container-fluid {
            padding-left: 0.9rem !important;
            padding-right: 0.9rem !important;
        }

        .topbar-title .breadcrumb {
            display: none;
        }

        .topbar-title h6 {
            font-size: 0.92rem;
            margin-bottom: 0;
        }

        .topbar-theme-toggle-mobile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.2rem;
            margin-right: 0.15rem;
        }

        .topbar-actions .topbar-theme-toggle {
            display: none;
        }

        .topbar-actions .navbar-nav {
            gap: 0.45rem;
        }

        .main-content>.container-fluid.py-4 {
            padding-left: 0.9rem !important;
            padding-right: 0.9rem !important;
        }

        .card-header {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            flex-wrap: nowrap;
            padding: 0.9rem !important;
            gap: 0.75rem;
        }

        .card-header h6 {
            min-height: auto;
            width: 100%;
            font-size: 0.76rem;
        }

        .card-header:not(.category-card-header) {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
        }

        .card-header:not(.category-card-header) > h6 {
            width: auto;
            flex: 0 1 auto;
            margin-bottom: 0;
        }

        .card-header:not(.category-card-header) > .d-flex,
        .card-header:not(.category-card-header) > .d-flex.align-items-center {
            display: contents !important;
        }

        .card-header:not(.category-card-header) > .d-flex .btn-sm,
        .card-header:not(.category-card-header) > .d-flex .btn,
        .card-header:not(.category-card-header) > .d-flex.align-items-center .btn-sm,
        .card-header:not(.category-card-header) > .d-flex.align-items-center .btn {
            grid-column: 2;
            grid-row: 1;
            width: auto;
            min-width: 4.6rem;
            justify-self: end;
        }

        .card-header:not(.category-card-header) > .d-flex input[type="text"],
        .card-header:not(.category-card-header) > .d-flex input[type="search"],
        .card-header:not(.category-card-header) > .d-flex.align-items-center input[type="text"],
        .card-header:not(.category-card-header) > .d-flex.align-items-center input[type="search"] {
            grid-column: 1 / -1;
            grid-row: 2;
            width: 100% !important;
            max-width: none !important;
        }

        .category-card-header {
            gap: 0.6rem;
        }

        .category-card-header-top {
            gap: 0.6rem;
        }

        .category-card-header-top .card-header h6,
        .category-card-header-top h6 {
            width: auto;
            flex: 1 1 auto;
            margin-bottom: 0;
        }

        .card-header-toolbar,
        .card-header>.d-flex,
        .card-header .d-flex.align-items-center {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr);
            align-items: stretch !important;
            gap: 0.55rem !important;
            width: 100%;
            margin-left: 0 !important;
            flex: 0 0 auto;
            padding: 0.55rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background: #f8fbff;
        }

        .category-card-add-btn {
            width: auto !important;
            min-width: 4.6rem;
            justify-self: end;
        }

        .card-header .btn-sm,
        .card-header .btn {
            min-height: 2.35rem;
            padding-left: 0.85rem;
            padding-right: 0.85rem;
            border-radius: 0.65rem;
        }

        .card-header-toolbar .btn-sm,
        .card-header-toolbar .btn,
        .card-header>.d-flex .btn-sm,
        .card-header>.d-flex .btn,
        .card-header .d-flex.align-items-center .btn-sm,
        .card-header .d-flex.align-items-center .btn {
            width: 100%;
        }

        .card-header-search,
        .card-header input[type="text"],
        .card-header input[type="search"],
        #searchInput {
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            min-height: 2.35rem;
            border-radius: 0.65rem !important;
            font-size: 0.72rem;
        }

        .table thead th {
            font-size: 0.64rem !important;
            padding-top: 0.65rem !important;
            padding-bottom: 0.65rem !important;
        }
    }
</style>


<body class="bg-gray-100">
    <aside
        class="sidenav overflow-hidden"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <div class="sidebar-brand-panel">
                <a class="navbar-brand m-0" href="{{ route('index') }}">
                    <img src="{{ asset(setting('web_name')->image ?? '') }}" class="navbar-brand-img h-auto" alt="main_logo">
                </a>
            </div>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="sidebar-menu" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item-section">
                    <span>Navigation</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-gauge text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('client.*') || request()->routeIs('category.*') || request()->routeIs('sub_category.*') || request()->routeIs('brand.*') || request()->routeIs('discount.*') || request()->routeIs('product.*') || request()->routeIs('store.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarManagement" role="button"
                        aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('client.*') || request()->routeIs('category.*') || request()->routeIs('sub_category.*') || request()->routeIs('brand.*') || request()->routeIs('discount.*') || request()->routeIs('product.*') || request()->routeIs('store.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarManagement">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-layer-group text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Management</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('client.*') || request()->routeIs('category.*') || request()->routeIs('sub_category.*') || request()->routeIs('brand.*') || request()->routeIs('discount.*') || request()->routeIs('product.*') || request()->routeIs('store.*') ? 'show' : '' }}"
                        id="sidebarManagement">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}"><i class="fas fa-users"></i>Users</a>
                            <a class="nav-link {{ request()->routeIs('client.*') ? 'active' : '' }}"
                                href="{{ route('client.index') }}"><i class="fas fa-user-tie"></i>Client</a>
                            <a class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
                                href="{{ route('category.index') }}"><i class="fas fa-list"></i>Category</a>
                            <a class="nav-link {{ request()->routeIs('sub_category.*') ? 'active' : '' }}"
                                href="{{ route('sub_category.index') }}"><i class="fas fa-list-ul"></i>Sub Category</a>
                            <a class="nav-link {{ request()->routeIs('brand.*') ? 'active' : '' }}"
                                href="{{ route('brand.index') }}"><i class="fas fa-tags"></i>Brands</a>
                            <a class="nav-link {{ request()->routeIs('table.*') ? 'active' : '' }}"
                                href="{{ route('table.index') }}"><i class="fas fa-list"></i>Tables</a>
                            <a class="nav-link {{ request()->routeIs('points.*') ? 'active' : '' }}"
                                href="{{ route('points.index') }}"><i class="fas fa-list"></i>Points</a>
                            <a class="nav-link {{ request()->routeIs('payment_method.*') ? 'active' : '' }}"
                                href="{{ route('payment_method.index') }}"><i class="fas fa-credit-card"></i>Payment Method</a>
                            <a class="nav-link {{ request()->routeIs('card_type.*') ? 'active' : '' }}"
                                href="{{ route('card_type.index') }}"><i class="fas fa-credit-card"></i>Card Type</a>
                            <a class="nav-link {{ request()->routeIs('type.*') ? 'active' : '' }}"
                                href="{{ route('type.index') }}"><i class="fas fa-tags"></i>Type</a>
                            <a class="nav-link {{ request()->routeIs('discount.*') ? 'active' : '' }}"
                                href="{{ route('discount.index') }}"><i class="fas fa-percent"></i>Discount</a>
                            <a class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}"
                                href="{{ route('product.index') }}"><i class="fas fa-box-open"></i>Product</a>
                            <a class="nav-link {{ request()->routeIs('combo.*') ? 'active' : '' }}"
                                href="{{ route('combo.index') }}"><i class="fas fa-box-open"></i>Combo Product</a>
                            <a class="nav-link {{ request()->routeIs('store.*') ? 'active' : '' }}"
                                href="{{ route('store.index') }}"><i class="fas fa-store"></i>Store</a>
                            <a class="nav-link {{ request()->routeIs('attribute.*') ? 'active' : '' }}"
                                href="{{ route('attribute.index') }}"><i class="fas fa-store"></i>Attribute</a>
                            <a class="nav-link {{ request()->routeIs('attribute_value.*') ? 'active' : '' }}"
                                href="{{ route('attribute_value.index') }}"><i class="fas fa-store"></i>Attribute Value</a>
                            <a class="nav-link {{ request()->routeIs('offer.*') ? 'active' : '' }}"
                                href="{{ route('offer.index') }}"><i class="fas fa-tags"></i>Offer</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('status.*') || request()->routeIs('country.*') || request()->routeIs('state.*') || request()->routeIs('district.*') || request()->routeIs('tehsil.*') || request()->routeIs('block.*') || request()->routeIs('village.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarMaster" role="button"
                        aria-expanded="{{ request()->routeIs('status.*') || request()->routeIs('country.*') || request()->routeIs('state.*') || request()->routeIs('district.*') || request()->routeIs('tehsil.*') || request()->routeIs('block.*') || request()->routeIs('village.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarMaster">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sitemap text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Master</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('status.*') || request()->routeIs('country.*') || request()->routeIs('state.*') || request()->routeIs('district.*') || request()->routeIs('tehsil.*') || request()->routeIs('block.*') || request()->routeIs('village.*') ? 'show' : '' }}"
                        id="sidebarMaster">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('status.*') ? 'active' : '' }}"
                                href="{{ route('status.index') }}"><i class="fas fa-toggle-on"></i>Status</a>
                            <a class="nav-link {{ request()->routeIs('country.*') ? 'active' : '' }}"
                                href="{{ route('country.index') }}"><i class="fas fa-earth-asia"></i>Country</a>
                            <a class="nav-link {{ request()->routeIs('state.*') ? 'active' : '' }}"
                                href="{{ route('state.index') }}"><i class="fas fa-map"></i>State</a>
                            <a class="nav-link {{ request()->routeIs('district.*') ? 'active' : '' }}"
                                href="{{ route('district.index') }}"><i class="fas fa-location-dot"></i>District</a>
                            <a class="nav-link {{ request()->routeIs('tehsil.*') ? 'active' : '' }}"
                                href="{{ route('tehsil.index') }}"><i class="fas fa-map-pin"></i>Tehsil</a>
                            <a class="nav-link {{ request()->routeIs('block.*') ? 'active' : '' }}"
                                href="{{ route('block.index') }}"><i class="fas fa-vector-square"></i>Block</a>
                            <a class="nav-link {{ request()->routeIs('village.*') ? 'active' : '' }}"
                                href="{{ route('village.index') }}"><i class="fas fa-house"></i>Village</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leads.*') || request()->routeIs('leads.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarLeads" role="button"
                        aria-expanded="{{ request()->routeIs('leads.*') || request()->routeIs('leads.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarLeads">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sitemap text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Leads</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('leads.*') || request()->routeIs('leads.*') ? 'show' : '' }}"
                        id="sidebarLeads">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}"
                                href="{{ route('leads.index') }}"><i class="fas fa-user-friends"></i>Leads</a>
                            <a class="nav-link {{ request()->routeIs('cards.*') ? 'active' : '' }}"
                                href="{{ route('cards.index') }}"><i class="fas fa-user-friends"></i>Cards</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('summer.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sun text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Summer Section</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('supplier.*') || request()->is('buyer*') || request()->is('demand*') || request()->is('sell*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarTrading" role="button"
                        aria-expanded="{{ request()->routeIs('supplier.*') || request()->is('buyer*') || request()->is('demand*') || request()->is('sell*') ? 'true' : 'false' }}"
                        aria-controls="sidebarTrading">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-briefcase text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Trading</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('supplier.*') || request()->is('buyer*') || request()->is('demand*') || request()->is('sell*') ? 'show' : '' }}"
                        id="sidebarTrading">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}"
                                href="{{ route('supplier.index') }}"><i class="fas fa-truck-field"></i>Supplier</a>
                            <a class="nav-link" href="{{ route('buyer.index') }}"><i class="fas fa-user-tie"></i>Buyer</a>
                            <a class="nav-link" href="#"><i class="fas fa-bullhorn"></i>Demand</a>
                            <a class="nav-link" href="#"><i class="fas fa-cart-shopping"></i>Sell</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('order.*') || request()->is('buyer*') || request()->is('demand*') || request()->is('sell*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarOrder" role="button"
                        aria-expanded="{{ request()->routeIs('order.*') || request()->is('buyer*') || request()->is('demand*') || request()->is('sell*') ? 'true' : 'false' }}"
                        aria-controls="sidebarOrder">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-briefcase text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Order Management</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('order.*') || request()->is('barcode*') || request()->is('demand*') || request()->is('sell*') ? 'show' : '' }}"
                        id="sidebarOrder">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}"
                                href="{{ route('order.index') }}"><i class="fas fa-truck-field"></i>Order</a>

                            <a class="nav-link {{ request()->routeIs('barcodes.*') ? 'active' : '' }}"
                                href="{{ route('order.barcodes') }}"><i class="fas fa-truck-field"></i>Barcode</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tax.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>office</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF"
                                        fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g id="office" transform="translate(153.000000, 2.000000)">
                                                <path class="color-background opacity-6"
                                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                                </path>
                                                <path class="color-background"
                                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Taxes</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a class="nav-link {{ request()->routeIs('setting.*') || request()->routeIs('faq.*') || request()->routeIs('cms.*') || request()->routeIs('slider.*') || request()->routeIs('promotional.*') || request()->routeIs('email_template.*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sidebarSettings" role="button"
                        aria-expanded="{{ request()->routeIs('setting.*') || request()->routeIs('faq.*') || request()->routeIs('cms.*') || request()->routeIs('slider.*') || request()->routeIs('promotional.*') || request()->routeIs('email_template.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarSettings">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-gear text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('setting.*') || request()->routeIs('faq.*') || request()->routeIs('cms.*') || request()->routeIs('slider.*') || request()->routeIs('promotional.*') || request()->routeIs('email_template.*') ? 'show' : '' }}"
                        id="sidebarSettings">
                        <div class="side-submenu">
                            <a class="nav-link {{ request()->routeIs('setting.*') ? 'active' : '' }}"
                                href="{{ route('setting.index') }}"><i class="fas fa-sliders"></i>Settings</a>
                            <a class="nav-link {{ request()->routeIs('faq.*') ? 'active' : '' }}"
                                href="{{ route('faq.index') }}"><i class="fas fa-circle-question"></i>FAQ</a>
                            <a class="nav-link {{ request()->routeIs('cms.*') ? 'active' : '' }}"
                                href="{{ route('cms.index') }}"><i class="fas fa-file-lines"></i>CMS</a>
                            <a class="nav-link {{ request()->routeIs('slider.*') ? 'active' : '' }}"
                                href="{{ route('slider.index') }}"><i class="fas fa-images"></i>Slider</a>
                            <a class="nav-link {{ request()->routeIs('promotional.*') ? 'active' : '' }}"
                                href="{{ route('promotional.index') }}"><i class="fas fa-bullhorn"></i>Promotional</a>
                            <a class="nav-link {{ request()->routeIs('email_template.*') ? 'active' : '' }}"
                                href="{{ route('email_template.index') }}"><i class="fas fa-envelope"></i>Email Template</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item mt-3">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#sidebarReports" role="button"
                        aria-expanded="false" aria-controls="sidebarReports">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reports</span>
                    </a>
                    <div class="collapse" id="sidebarReports">
                        <div class="side-submenu">
                            <a class="nav-link" href="{{ route('transaction') }}">
                                <i class="fas fa-receipt"></i>Transaction
                            </a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="14px" height="14px" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#344767" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Sign Out</span>
                    </a>
                </li>

            </ul>
        </div>

    </aside>
    <div class="sidenav-backdrop d-xl-none" id="sidenav-backdrop"></div>
