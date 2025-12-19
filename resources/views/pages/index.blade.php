<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Testing Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            max-height: 400px;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <div class="gradient-bg text-white py-6 shadow-lg">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold"><i class="fas fa-vial mr-3"></i>API Testing Dashboard</h1>
                    <p class="text-gray-200 mt-2">Test all API endpoints without Postman</p>
                </div>
                <a href="{{ url('/') }}" class="bg-white text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-home mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sidebar - API Endpoints -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list text-purple-600 mr-2"></i>API Endpoints
                    </h2>
                    
                    <!-- Sync APIs -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-sync text-blue-500 mr-2"></i>Sync APIs
                        </h3>
                        <div class="space-y-2">
                            <button onclick="testAPI('POST', '/sync/execute?pages=2', 'Sync Movies')" 
                                    class="w-full text-left px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm transition">
                                <span class="font-semibold text-blue-600">POST</span> Sync Movies
                            </button>
                            <button onclick="testAPI('POST', '/sync/test-connection', 'Test Connection')" 
                                    class="w-full text-left px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm transition">
                                <span class="font-semibold text-blue-600">POST</span> Test Connection
                            </button>
                            <button onclick="testAPI('GET', '/sync/api/last', 'Last Sync')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Last Sync
                            </button>
                            <button onclick="testAPI('GET', '/sync/api/status', 'Sync Status')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Sync Status
                            </button>
                        </div>
                    </div>

                    <!-- Dashboard APIs -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-chart-bar text-purple-500 mr-2"></i>Dashboard APIs
                        </h3>
                        <div class="space-y-2">
                            <button onclick="testAPI('GET', '/dashboard/api/statistics', 'Statistics')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Statistics
                            </button>
                            <button onclick="testAPI('GET', '/dashboard/api/genre-chart', 'Genre Chart')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Genre Chart
                            </button>
                            <button onclick="testAPI('GET', '/dashboard/api/date-chart', 'Date Chart')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Date Chart
                            </button>
                            <button onclick="testAPI('GET', '/dashboard/api/top-rated?limit=10', 'Top Rated')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Top Rated
                            </button>
                            <button onclick="testAPI('GET', '/dashboard/api/recent?limit=10', 'Recent Movies')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Recent Movies
                            </button>
                        </div>
                    </div>

                    <!-- Movies APIs -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-film text-red-500 mr-2"></i>Movies APIs
                        </h3>
                        <div class="space-y-2">
                            <button onclick="testAPI('GET', '/movies/api/list?per_page=10', 'Movies List')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Movies List
                            </button>
                            <button onclick="testAPI('GET', '/movies/api/genres', 'Genres')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Genres
                            </button>
                            <button onclick="testAPI('GET', '/movies/api/search?q=avengers', 'Search Movies')" 
                                    class="w-full text-left px-4 py-2 bg-green-50 hover:bg-green-100 rounded text-sm transition">
                                <span class="font-semibold text-green-600">GET</span> Search Movies
                            </button>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="border-t pt-4">
                        <button onclick="clearResults()" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
                            <i class="fas fa-trash mr-2"></i>Clear Results
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content - Results -->
            <div class="lg:col-span-2">
                <!-- Status Card -->
                <div id="statusCard" class="bg-white rounded-lg shadow-lg p-6 mb-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800" id="statusTitle">Testing API...</h3>
                            <p class="text-gray-600 text-sm" id="statusEndpoint"></p>
                        </div>
                        <div id="statusIcon" class="text-3xl">
                            <div class="loading"></div>
                        </div>
                    </div>
                </div>

                <!-- Results Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-code text-purple-600 mr-2"></i>API Response
                        </h2>
                        <button onclick="copyResponse()" class="text-gray-600 hover:text-purple-600 transition" title="Copy Response">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    
                    <div id="results" class="min-h-[400px]">
                        <div class="text-center py-20 text-gray-400">
                            <i class="fas fa-flask text-6xl mb-4"></i>
                            <p class="text-lg">Select an API endpoint to test</p>
                            <p class="text-sm mt-2">Results will appear here</p>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800 mb-2">Testing Tips:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Start with "Sync Movies"</strong> to populate the database first</li>
                                <li>• <strong>Test Connection</strong> to verify TMDB API key is working</li>
                                <li>• All GET requests can be tested directly in browser</li>
                                <li>• POST requests are handled via fetch API</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentResponse = '';

        async function testAPI(method, endpoint, name) {
            const statusCard = document.getElementById('statusCard');
            const statusTitle = document.getElementById('statusTitle');
            const statusEndpoint = document.getElementById('statusEndpoint');
            const statusIcon = document.getElementById('statusIcon');
            const results = document.getElementById('results');

            // Show loading
            statusCard.classList.remove('hidden');
            statusTitle.textContent = `Testing: ${name}`;
            statusEndpoint.textContent = `${method} ${endpoint}`;
            statusIcon.innerHTML = '<div class="loading"></div>';
            results.innerHTML = '<div class="text-center py-10"><div class="loading mx-auto"></div><p class="text-gray-600 mt-4">Loading...</p></div>';

            try {
                const startTime = Date.now();
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                };

                const response = await fetch(endpoint, options);
                const data = await response.json();
                const endTime = Date.now();
                const duration = endTime - startTime;

                currentResponse = JSON.stringify(data, null, 2);

                // Show success
                statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                statusTitle.textContent = `Success: ${name}`;
                
                results.innerHTML = `
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                ${response.status} ${response.statusText}
                            </span>
                            <span class="text-gray-600 text-sm">
                                <i class="fas fa-clock mr-1"></i>${duration}ms
                            </span>
                        </div>
                    </div>
                    <pre>${currentResponse}</pre>
                `;

            } catch (error) {
                statusIcon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                statusTitle.textContent = `Error: ${name}`;
                
                results.innerHTML = `
                    <div class="mb-4">
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                            ERROR
                        </span>
                    </div>
                    <pre>${JSON.stringify({ error: error.message }, null, 2)}</pre>
                `;
            }
        }

        function clearResults() {
            document.getElementById('statusCard').classList.add('hidden');
            document.getElementById('results').innerHTML = `
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-flask text-6xl mb-4"></i>
                    <p class="text-lg">Select an API endpoint to test</p>
                    <p class="text-sm mt-2">Results will appear here</p>
                </div>
            `;
        }

        function copyResponse() {
            if (currentResponse) {
                navigator.clipboard.writeText(currentResponse);
                alert('Response copied to clipboard!');
            }
        }
    </script>

</body>
</html>
