<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Heaven Cafe || POS Login</title>
  <!-- Inter Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    body { background: #FFF7ED; }
    .glass-card {
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .gradient-primary { background: linear-gradient(145deg, #FB923C, #C2410C); }
    .text-primary { color: #F97316; }
    .border-primary { border-color: #F97316; }
    .shadow-soft { box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.12); }
    .card-rounded { border-radius: 24px; }
    .btn-google {
      background: white;
      border: 1px solid #e2e8f0;
      transition: all 0.2s ease;
    }
    .btn-google:hover {
      border-color: #F97316;
      box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15);
      transform: translateY(-1px);
    }
    .input-focus:focus {
      border-color: #F97316;
      box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
      outline: none;
    }
    .floating-msg {
      animation: floatY 5s ease-in-out infinite;
    }
    .floating-msg:nth-child(2) { animation-delay: 1.8s; }
    @keyframes floatY {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
  </style>
</head>
<body>

  <!-- ===== LOGIN PAGE (full viewport) ===== -->
  <div class="min-h-screen flex items-center justify-center px-4 py-8 relative overflow-hidden">


    <!-- subtle background decoration -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-[-20%] left-[-10%] w-72 h-72 bg-orange-300/20 rounded-full blur-3xl"></div>
      <div class="absolute bottom-[-20%] right-[-10%] w-96 h-96 bg-red-300/20 rounded-full blur-3xl"></div>
    </div>

     <div id="alert-container" class="fixed bottom-5 right-5 space-y-3 z-50"></div>

    <!-- main login card -->
    <div class="w-full max-w-md relative z-10">

      <!-- Card -->
      <div class="glass-card card-rounded p-6 md:p-8 shadow-2xl border border-white/50">

        <div class="text-center mb-7">
          <p class="text-2xl font-bold text-[#431407] tracking-tight">The Heaven <span class="text-primary">Cafe</span></p>
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-700 mt-1">POS Staff Login</p>
        </div>

        <!-- Email / Password form (optional, but still clean) -->
        <form class="space-y-4" id="loginForm" method="POST" action="{{ route('pos.logins') }}">
         @csrf
          <div>
            <label class="block text-sm font-medium text-[#1E293B] mb-1">Email address</label>
            <input type="email" name="email" id="email" placeholder="you@company.com" class="w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-xl text-sm placeholder-gray-400 input-focus transition" />
          </div>
          <div>
            <label class="block text-sm font-medium text-[#1E293B] mb-1">Password</label>
            <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-xl text-sm placeholder-gray-400 input-focus transition" />
          </div>
          <!-- <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-[#475569]"><input type="checkbox" class="rounded border-gray-300 text-[#25D366] focus:ring-[#25D366]" /> Remember me</label>
            <a href="#" class="text-[#25D366] hover:underline font-medium">Forgot password?</a>
          </div> -->
          <button type="submit" class="w-full gradient-primary text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-orange-500/20 hover:shadow-xl transition hover:scale-[1.02]">
            <i class="fas fa-arrow-right-to-bracket mr-2"></i> Sign In
          </button>
        </form>

        <!-- subtle note -->
        <div class="mt-4 text-center text-xs text-gray-400 border-t border-gray-200/60 pt-4">
          <i class="fas fa-shield-alt text-primary mr-1"></i> Secure cafe POS access
        </div>
      </div>

    </div>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var adminLoginUrl = "{{ route('pos.logins') }}";
        var adminIndexUrl = "{{ route('pos.index') }}";
    </script>
    <script src="{{ asset('assets/js/login.js') }}"></script>

</body>
</html>
