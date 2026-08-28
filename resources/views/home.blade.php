<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>API Explorer | API & Delivery Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .expandable-row {
      transition: all 0.2s ease;
    }
    .detail-panel {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .method-badge {
      font-family: 'SF Mono', 'Fira Code', monospace;
    }
    pre {
      white-space: pre-wrap;
      word-wrap: break-word;
      font-family: 'SF Mono', 'Fira Code', monospace;
      font-size: 0.75rem;
    }
    .api-row:hover {
      background-color: #f8fafc;
      cursor: pointer;
    }
    .active-row {
      background-color: #eef2ff;
      border-left: 3px solid #4f46e5;
    }
    /* custom scroll */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #e2e8f0;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
      background: #94a3b8;
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-slate-100 antialiased">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-slate-800 flex items-center gap-3">
            <i class="fas fa-bolt text-indigo-500"></i> 
            API Workspace
          </h1>
          <p class="mt-2 text-sm text-slate-500">api.php aur delivery.php ke endpoints yahin explore aur test kar sakte hain.</p>
        </div>
        <div class="flex gap-2">
          <div class="relative">
            <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="globalAuthToken" placeholder="Enter Bearer Token (if required)" class="pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm w-64 bg-white shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 outline-none">
          </div>
          <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Search API name or URL..." class="pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm w-64 bg-white shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 outline-none">
          </div>
          @if(Auth::guard('admin')->check())
            <a href="{{ url('/home') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition flex items-center gap-2">
              Home
            </a>
          @else
            <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition flex items-center gap-2">
              Login
            </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">API Name</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Method</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Endpoint URL</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Group / Auth</th>
              <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">
                <i class="fas fa-chevron-down text-slate-400"></i>
              </th>
            </tr>
          </thead>
          <tbody id="apiTableBody" class="bg-white divide-y divide-slate-100">
            <!-- Dynamic rows will be injected via JS -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Info note -->
    <div class="mt-6 text-center text-sm text-slate-400 flex justify-center gap-4">
      <span><i class="fas fa-mouse-pointer text-indigo-400"></i> Click any row for endpoint details, headers, request body aur live response</span>
    </div>
  </div>

  <script>
    // ---------- API DATASET (Each API contains full details: URL, method, request body example, response example, headers, description) ----------
    const apisData = @json($apisData ?? []);

    // Helper to format JSON nicely for display
    function formatJSON(obj) {
      if (!obj) return "null";
      try {
        return JSON.stringify(obj, null, 2);
      } catch(e) {
        return String(obj);
      }
    }

    function buildQueryString(params) {
      if (!params) return "";
      return Object.entries(params).map(([k,v]) => `${k}=${encodeURIComponent(v)}`).join("&");
    }

    // Function to actually hit the API and show response
    async function testApi(id) {
      const api = apisData.find(a => a.id === id);
      const token = document.getElementById('globalAuthToken').value.trim();
      const url = document.getElementById(`endpoint-${id}`).value.trim();
      const bodyEl = document.getElementById(`req-body-${id}`);
      
      const resContainer = document.getElementById(`live-response-${id}`);
      const statusContainer = document.getElementById(`live-status-${id}`);
      
      resContainer.innerHTML = "Sending request... 🚀";
      statusContainer.innerHTML = '<i class="fas fa-spinner fa-spin text-indigo-500"></i>';

      let headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      };

      if (token) {
        headers['Authorization'] = 'Bearer ' + token;
      }

      let options = { method: api.method, headers };

      if (bodyEl && bodyEl.value.trim() !== '') {
        options.body = bodyEl.value; // Taking whatever user has typed in JSON
      }

      try {
        const response = await fetch(url, options);
        const contentType = response.headers.get("content-type");
        const data = (contentType && contentType.includes("application/json")) ? await response.json() : await response.text();
        
        statusContainer.innerHTML = `<span class="${response.ok ? 'text-emerald-600' : 'text-red-500'} font-bold px-2 py-1 bg-white rounded shadow-sm text-xs">${response.status} ${response.statusText}</span>`;
        resContainer.innerHTML = formatJSON(data);
      } catch (err) {
        statusContainer.innerHTML = `<span class="text-red-500 font-bold px-2 py-1 bg-white rounded shadow-sm text-xs">Network Error</span>`;
        resContainer.innerHTML = String(err);
      }
    }

    // Render full endpoint details with URL, headers, request/response
    function renderDetailPanel(api) {
      const fullUrl = api.queryParams ? `${api.endpoint}?${buildQueryString(api.queryParams)}` : api.endpoint;
      const methodColor = {
        GET: "bg-emerald-100 text-emerald-700",
        POST: "bg-blue-100 text-blue-700",
        PUT: "bg-amber-100 text-amber-700",
        DELETE: "bg-red-100 text-red-700"
      };
      const methodClass = methodColor[api.method] || "bg-gray-100 text-gray-700";
      
      return `
        <div class="detail-panel bg-slate-50 border-t border-slate-200 px-6 py-5 space-y-5">
          <!-- URL & Method Section -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-100 px-4 py-2 border-b border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <i class="fas fa-link text-indigo-500 text-sm"></i>
                <span class="font-mono text-xs font-semibold text-slate-600">ENDPOINT DETAILS</span>
              </div>
              <button onclick="testApi(${api.id})" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded shadow-sm text-xs font-bold transition flex items-center gap-2"><i class="fas fa-paper-plane"></i> Send Request</button>
            </div>
            <div class="p-4 space-y-3">
              <div class="flex flex-wrap items-center gap-3">
                <span class="px-2.5 py-1 rounded-md text-xs font-bold method-badge ${methodClass}">${api.method}</span>
                <input type="text" id="endpoint-${api.id}" value="${fullUrl}" class="text-sm bg-slate-50 px-3 py-1.5 rounded-lg font-mono text-slate-800 border border-slate-300 flex-1 focus:ring-2 focus:ring-indigo-200 outline-none transition" title="You can edit {id} or parameters here before hitting Send">
              </div>
              <div class="text-sm text-slate-600"><span class="font-semibold">Description:</span> ${api.description}</div>
              <div class="flex flex-wrap gap-4 text-xs">
                <span><span class="font-semibold">Group:</span> <code class="bg-slate-100 px-2 py-0.5 rounded">${api.group ?? 'api.php'}</code></span>
                <span><span class="font-semibold">Auth:</span> <code class="bg-slate-100 px-2 py-0.5 rounded">${api.auth}</code></span>
                <span class="text-emerald-600"><i class="fas fa-circle text-[8px]"></i> ${api.status}</span>
              </div>
            </div>
          </div>

          <!-- Request Body (Editable) -->
          ${api.requestBody !== null ? `
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="bg-slate-100 px-4 py-2 border-b border-slate-200 flex items-center gap-2">
              <i class="fas fa-paper-plane text-indigo-400"></i>
              <span class="font-mono text-xs font-semibold text-slate-600">REQUEST BODY (Editable JSON)</span>
            </div>
            <div class="p-0">
              <textarea id="req-body-${api.id}" class="w-full bg-slate-900 text-slate-100 p-4 text-xs font-mono border-0 rounded-b-xl outline-none" rows="5" spellcheck="false">${formatJSON(api.requestBody)}</textarea>
            </div>
          </div>
          ` : ''}

          <!-- Live Response -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="bg-slate-100 px-4 py-2 border-b border-slate-200 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500"></i>
                <span class="font-mono text-xs font-semibold text-slate-600">LIVE RESPONSE</span>
              </div>
              <div id="live-status-${api.id}"></div>
            </div>
            <div class="p-4">
              <pre id="live-response-${api.id}" class="bg-slate-900 text-slate-100 p-3 rounded-lg text-xs overflow-x-auto">Hit "Send Request" to see the live response here...</pre>
            </div>
          </div>
          <div class="text-right text-xs text-slate-400 italic"><i class="fas fa-info-circle"></i> Click again on the row to collapse</div>
        </div>
      `;
    }

    let expandedRowId = null;

    function renderTable(filterText = "") {
      const tbody = document.getElementById("apiTableBody");
      const filteredApis = apisData.filter(api => 
        api.name.toLowerCase().includes(filterText.toLowerCase()) || 
        api.endpoint.toLowerCase().includes(filterText.toLowerCase())
      );
      
      let html = "";
      filteredApis.forEach(api => {
        const isExpanded = (expandedRowId === api.id);
        const methodColor = {
          GET: "bg-emerald-100 text-emerald-700",
          POST: "bg-blue-100 text-blue-700",
          PUT: "bg-amber-100 text-amber-700",
          DELETE: "bg-red-100 text-red-700"
        };
        const methodClass = methodColor[api.method] || "bg-gray-100 text-gray-700";
        
        html += `
          <tr class="api-row transition-all duration-150 ${isExpanded ? 'active-row' : ''}" data-id="${api.id}">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3">
                  <i class="fas fa-plug text-indigo-500 text-xs"></i>
                </div>
                <div class="font-medium text-slate-800">${api.name}</div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 inline-flex text-xs leading-4 font-bold rounded-md ${methodClass} method-badge">${api.method}</span>
            </td>
            <td class="px-6 py-4">
              <code class="text-xs text-slate-600 bg-slate-50 px-2 py-1 rounded-md break-all">${api.endpoint.length > 60 ? api.endpoint.substring(0, 57) + '...' : api.endpoint}</code>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-sm text-slate-600">${api.group ?? 'api.php'}</span>
              </div>
            </td>
            <td class="px-6 py-4 text-right">
              <i class="fas fa-chevron-down transition-transform duration-200 ${isExpanded ? 'rotate-180 text-indigo-500' : 'text-slate-400'}"></i>
            </td>
          </tr>
        `;
        if (isExpanded) {
          html += `
            <tr class="detail-row">
              <td colspan="5" class="px-0 py-0 bg-slate-50">
                ${renderDetailPanel(api)}
              </td>
            </tr>
          `;
        }
      });
      
      if (filteredApis.length === 0) {
        html = `<tr><td colspan="5" class="text-center py-12 text-slate-400"><i class="fas fa-search text-3xl mb-2 block"></i>No APIs match your search</td></tr>`;
      }
      tbody.innerHTML = html;
      
      // Re-attach row click listeners
      document.querySelectorAll('.api-row').forEach(row => {
        row.addEventListener('click', (e) => {
          e.stopPropagation();
          const id = parseInt(row.getAttribute('data-id'));
          if (expandedRowId === id) {
            expandedRowId = null;
          } else {
            expandedRowId = id;
          }
          renderTable(filterText);
        });
      });
      
      // Attach copy functionality
      document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          const textToCopy = btn.getAttribute('data-copy');
          navigator.clipboard.writeText(textToCopy).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-green-500"></i> Copied!';
            setTimeout(() => {
              btn.innerHTML = originalHtml;
            }, 1500);
          }).catch(() => {
            alert('Could not copy');
          });
        });
      });
    }
    
    // Search handler
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', (e) => {
      expandedRowId = null; // collapse all on search
      renderTable(e.target.value);
    });
    
    renderTable("");
    
  </script>
</body>
</html>
