<!DOCTYPE html>
<html>
<head>
    <title>Debug Fetch</title>
</head>
<body>
    <h1>Debug API Connection</h1>
    <div id="output">Loading...</div>

    <script>
        async function testAPI() {
            const output = document.getElementById('output');
            try {
                output.innerHTML = 'Testing API...';
                const response = await fetch('/api/stats');
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                const data = await response.json();
                output.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                output.innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
                console.error('Fetch error:', error);
            }
        }

        testAPI();
    </script>
</body>
</html>