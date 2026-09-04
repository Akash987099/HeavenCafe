<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Heaven Cafe || POS</title>
  <!-- Tailwind + Inter + FontAwesome -->
  <link rel="manifest" href="{{asset('manifest.json')}}">
  <meta name="theme-color" content="#F97316">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">

  <link
        rel="apple-touch-icon"
        href="{{ asset('images/pwa/icon-192.png') }}"
    >
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * { font-family: 'Inter', sans-serif; }
    :root {
      --cafe-orange: #F97316;
      --cafe-orange-dark: #C2410C;
      --cafe-red: #B91C1C;
      --cafe-cream: #FFF7ED;
      --cafe-ink: #431407;
    }
    body { background: var(--cafe-cream); }
    .glass { background: rgba(255,255,255,0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.7); }
    .glass-dark { background: rgba(15,23,42,0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
    .gradient-primary { background: linear-gradient(135deg, #FB923C 0%, #C2410C 100%); }
    .card-hover { transition: all 0.2s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
    .progress-ring { transition: stroke-dashoffset 0.6s ease; }
    .step-card { transition: all 0.15s ease; }
    .step-card:hover { background: rgba(255,255,255,0.8); border-color: #FED7AA; }
    .onboarding-illustration { background: linear-gradient(145deg, #FFF7ED, #FFEDD5); border-radius: 40px; }
    .sidebar-link { transition: all 0.15s; }
    .sidebar-link:hover { background: #FFF0E4; color: var(--cafe-orange-dark); }
    .sidebar-link.active { background: #FFEDD5; color: var(--cafe-orange-dark); font-weight: 600; }
    .pos-sidebar {
      border-right: 1px solid #FED7AA !important;
      box-shadow: 2px 0 10px rgba(194, 65, 12, 0.06);
    }
    .pos-topbar {
      border-bottom: 1px solid #FED7AA !important;
      box-shadow: 0 2px 10px rgba(194, 65, 12, 0.05);
    }
    .pos-mobile-sidebar { border-right: 1px solid #FED7AA; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: var(--cafe-orange); border-radius: 10px; }

    /* Apply the Heaven Cafe palette to existing Tailwind utility classes. */
    [class~="bg-[#128C7E]"] { background-color: var(--cafe-orange-dark) !important; }
    [class~="text-[#128C7E]"] { color: var(--cafe-orange-dark) !important; }
    [class~="border-[#128C7E]"], [class~="focus:border-[#128C7E]"] { border-color: var(--cafe-orange) !important; }
    [class~="focus:ring-[#128C7E]/20"]:focus { --tw-ring-color: rgb(249 115 22 / 0.25) !important; }
    [class~="bg-[#25D366]"] { background-color: var(--cafe-orange) !important; }
    [class~="text-[#25D366]"] { color: var(--cafe-orange) !important; }
    [class~="focus:border-[#25D366]"] { border-color: var(--cafe-orange) !important; }
    [class~="hover:bg-[#0f766e]"]:hover, [class~="hover:bg-[#128C7E]"]:hover { background-color: var(--cafe-red) !important; }
    [class~="bg-emerald-50"], [class~="bg-emerald-100"] { background-color: #FFF0E4 !important; }
  </style>
</head>
<body class="antialiased text-[#431407] bg-orange-50 flex h-screen overflow-hidden">

<script>
if ('serviceWorker' in navigator) {

    window.addEventListener('load', function () {

        navigator.serviceWorker.register(
            "{{ asset('sw.js') }}"
        )
        .then(function (registration) {

            console.log(
                'PWA registered:',
                registration.scope
            );

        })
        .catch(function (error) {

            console.error(
                'PWA registration failed:',
                error
            );

        });

    });

}
</script>
