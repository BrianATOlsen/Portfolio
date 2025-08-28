<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fsa = strtoupper(trim($_POST['fsa']));
    $response = [];

    // Helper function to check FSA ranges
    function inFsaRange($fsa, $start, $end) {
        $fsa = strtoupper($fsa);
        $start = strtoupper($start);
        $end = strtoupper($end);

        $prefixFsa = $fsa[0];
        $numFsa = $fsa[1];
        $letterFsa = $fsa[2];

        $prefixStart = $start[0];
        $numStart = $start[1];
        $letterStart = $start[2];

        $prefixEnd = $end[0];
        $numEnd = $end[1];
        $letterEnd = $end[2];

        if ($prefixFsa !== $prefixStart || $prefixFsa !== $prefixEnd) return false;

        $fsaCode = ord($numFsa) * 26 + ord($letterFsa);
        $startCode = ord($numStart) * 26 + ord($letterStart);
        $endCode = ord($numEnd) * 26 + ord($letterEnd);

        return $fsaCode >= $startCode && $fsaCode <= $endCode;
    }

    // Validate format (Letter-Digit-Letter)
    if (preg_match('/^[A-Z][0-9][A-Z]$/', $fsa)) {

        // --- G rules ---
        if (
            inFsaRange($fsa,'G0C','G0C') || inFsaRange($fsa,'G0X','G0X') ||
            inFsaRange($fsa,'G1A','G2Z') || inFsaRange($fsa,'G3A','G3K') ||
            inFsaRange($fsa,'G6V','G6X') || inFsaRange($fsa,'G8T','G8Z') ||
            inFsaRange($fsa,'G9A','G9X')
        ) {
            $response=['status'=>'success','destination'=>'QUEBEC CITY'];
        }
        elseif ($fsa[0]==='G') {
            $response=['status'=>'success','destination'=>'QUEBEC FWD'];
        }

        // --- H rules ---
        elseif (inFsaRange($fsa,'H0A','H0A') || preg_match('/^H[1-9][A-Z]/',$fsa)) {
            $response=['status'=>'success','destination'=>'MONTREAL CITY'];
        }

        // --- J rules ---
        elseif (
            inFsaRange($fsa,'J0A','J3Z') || inFsaRange($fsa,'J4A','J4B') || inFsaRange($fsa,'J4G','J4S') ||
            inFsaRange($fsa,'J4V','J4Z') || inFsaRange($fsa,'J5A','J7Z') || inFsaRange($fsa,'J8A','J8H') ||
            inFsaRange($fsa,'J8L','J8N') || inFsaRange($fsa,'J8P','J8Z') || inFsaRange($fsa,'J9A','J9Z')
        ) {
            if (
                inFsaRange($fsa,'J0A','J0W') || inFsaRange($fsa,'J1A','J3Z') || inFsaRange($fsa,'J4A','J4B') ||
                inFsaRange($fsa,'J5A','J7Z') || inFsaRange($fsa,'J8A','J8H')
            ) {
                $response=['status'=>'success','destination'=>'MONTREAL FWD'];
            } elseif (inFsaRange($fsa,'J8L','J8N')) {
                $response=['status'=>'success','destination'=>'OTTAWA FWD'];
            } else {
                $response=['status'=>'success','destination'=>'OTTAWA CITY'];
            }
        }

        // --- K rules ---
        elseif (inFsaRange($fsa,'K0H','K0M')||$fsa==='K7G'||inFsaRange($fsa,'K7K','K7P')||$fsa==='K7R'||inFsaRange($fsa,'K8N','K8V')||$fsa==='K9A'||inFsaRange($fsa,'K9H','K9V')) {
            $response=['status'=>'success','destination'=>'TORONTO FWD'];
        }
        elseif ($fsa[0]==='K') { $response=['status'=>'success','destination'=>'OTTAWA']; }

        // --- L rules ---
        elseif (
            inFsaRange($fsa,'L0A','L0B')||inFsaRange($fsa,'L0C','L0G')||inFsaRange($fsa,'L0H','L0J')||inFsaRange($fsa,'L0N','L0P')||
            inFsaRange($fsa,'L0X','L0X')||inFsaRange($fsa,'L1A','L1E')||inFsaRange($fsa,'L4G','L4G')||inFsaRange($fsa,'L4P','L4P')||
            inFsaRange($fsa,'L7B','L7B')||inFsaRange($fsa,'L7C','L7K')||inFsaRange($fsa,'L9L','L9L')||inFsaRange($fsa,'L9N','L9R')||
            inFsaRange($fsa,'L9T','L9W')
        ) { $response=['status'=>'success','destination'=>'TORONTO FWD']; }
        elseif (
            inFsaRange($fsa,'L0K','L0M')||inFsaRange($fsa,'L1G','L1Z')||inFsaRange($fsa,'L3P','L3T')||inFsaRange($fsa,'L3V','L3V')||
            inFsaRange($fsa,'L4A','L4E')||inFsaRange($fsa,'L4H','L4L')||inFsaRange($fsa,'L4M','L4N')||inFsaRange($fsa,'L4R','L4R')||
            inFsaRange($fsa,'L5A','L7A')||inFsaRange($fsa,'L9M','L9M')||inFsaRange($fsa,'L9S','L9S')
        ) { $response=['status'=>'success','destination'=>'TORONTO CITY']; }
        elseif (inFsaRange($fsa,'L0R','L0S')||inFsaRange($fsa,'L2A','L3M')||inFsaRange($fsa,'L7L','L9K')) { $response=['status'=>'success','destination'=>'HAMILTON FWD']; }

        // --- M rules ---
        elseif ($fsa[0]==='M') { $response=['status'=>'success','destination'=>'TORONTO CITY']; }

        // --- N rules ---
        elseif ($fsa==='N0A'||$fsa==='N1A'||inFsaRange($fsa,'N0E','N0E')||inFsaRange($fsa,'N3L','N4B')) { $response=['status'=>'success','destination'=>'HAMILTON']; }
        elseif (inFsaRange($fsa,'N0B','N0C')||inFsaRange($fsa,'N0G','N0H')||inFsaRange($fsa,'N1C','N3H')||inFsaRange($fsa,'N4Z','N5A')) { $response=['status'=>'success','destination'=>'KITCHENER']; }
        elseif (inFsaRange($fsa,'N0J','N0L')||$fsa==='N4G'||inFsaRange($fsa,'N4S','N4V')||inFsaRange($fsa,'N5C','N8A')) { $response=['status'=>'success','destination'=>'LONDON']; }
        elseif (inFsaRange($fsa,'N0R','N0R')||inFsaRange($fsa,'N8H','N9Y')) { $response=['status'=>'success','destination'=>'WINDSOR']; }

        // --- P rules ---
        elseif (inFsaRange($fsa,'P0A','P0G')||inFsaRange($fsa,'P1H','P2A')||inFsaRange($fsa,'P2N','P6C')||inFsaRange($fsa,'P7A','P9N')) { $response=['status'=>'success','destination'=>'TORONTO FWD']; }
        elseif ($fsa==='P0H'||inFsaRange($fsa,'P0J','P0S')||inFsaRange($fsa,'P1A','P1C')||$fsa==='P2B') { $response=['status'=>'success','destination'=>'TORONTO FWD']; }
        elseif (inFsaRange($fsa,'P0T','P0X')) { $response=['status'=>'success','destination'=>'THUNDER BAY']; }
        elseif ($fsa==='P0Y') { $response=['status'=>'success','destination'=>'WINNIPEG']; }
        elseif ($fsa==='P2B') { $response=['status'=>'success','destination'=>'TORONTO FWD']; }
        elseif ($fsa==='P2B') { $response=['status'=>'success','destination'=>'TORONTO FWD']; }

        // --- R rules ---
        elseif ($fsa[0]==='R') { $response=['status'=>'success','destination'=>'WINNIPEG']; }

        // --- S rules ---
        elseif (inFsaRange($fsa,'S0A','S0C')||inFsaRange($fsa,'S0G','S0H')||$fsa==='S0N'||inFsaRange($fsa,'S2V','S6K')) {
            $response=['status'=>'success','destination'=>'REGINA'];
        }
        elseif ($fsa==='S0E'||inFsaRange($fsa,'S0J','S0M')||inFsaRange($fsa,'S6V','S9A')) {
            $response=['status'=>'success','destination'=>'SASKATOON'];
        }
        elseif ($fsa==='S0P') {
            $response=['status'=>'success','destination'=>'WINNIPEG'];
        }

        // --- T rules ---
        elseif (inFsaRange($fsa,'T0A','T0H')||inFsaRange($fsa,'T0P','T0V')||inFsaRange($fsa,'T4J','T4L')||inFsaRange($fsa,'T4V','T4X')||inFsaRange($fsa,'T7A','T9S')||inFsaRange($fsa,'T9W','T9X')) {
            $response=['status'=>'success','destination'=>'EDMONTON FWD'];
        }
        elseif (inFsaRange($fsa,'T0J','T0M')||inFsaRange($fsa,'T1A','T1W')||inFsaRange($fsa,'T1X','T3Z')||inFsaRange($fsa,'T4A','T4H')||inFsaRange($fsa,'T4M','T4T')) {
            $response=['status'=>'success','destination'=>'CALGARY FWD'];
        }
        elseif (preg_match('/^T5/',$fsa)||preg_match('/^T6/',$fsa)) {
            $response=['status'=>'success','destination'=>'EDMONTON CITY'];
        }
        elseif ($fsa==='T9V') {
            $response=['status'=>'success','destination'=>'SASKATOON'];
        }

        // --- V rules ---
        elseif ($fsa==='V0A') { $response=['status'=>'success','destination'=>'VANCOUVER']; }
        elseif ($fsa==='V0B'||inFsaRange($fsa,'V0T','V0X')||$fsa==='V1B') { $response=['status'=>'success','destination'=>'VANCOUVER FWD']; }
        elseif ($fsa==='V0C'||$fsa==='V1A'||$fsa==='V1C'||$fsa==='V1G'||$fsa==='V1J') { $response=['status'=>'success','destination'=>'EDMONTON FWD']; }
        elseif (inFsaRange($fsa,'V0E','V0N')||$fsa==='V1E'||inFsaRange($fsa,'V1K','V8J')) { $response=['status'=>'success','destination'=>'VANCOUVER']; }
        elseif (inFsaRange($fsa,'V0P','V0S')||inFsaRange($fsa,'V8K','V9Z')) { $response=['status'=>'success','destination'=>'VICTORIA FWD']; }

        // --- X rules ---
        elseif ($fsa==='X0A'||$fsa==='X1A'||inFsaRange($fsa,'X0E','X0G')) {
            $response=['status'=>'success','destination'=>'EDMONTON FWD'];
        }
        elseif ($fsa==='X0B') { $response=['status'=>'success','destination'=>'EDMONTON FWD']; }
        elseif ($fsa==='X0C') { $response=['status'=>'success','destination'=>'WINNIPEG FWD']; }

        // --- Y rules ---
        elseif ($fsa[0]==='Y') {
            $response=['status'=>'success','destination'=>'VANCOUVER'];
        }

        else {
            $response=['status'=>'error','message'=>'Postal Code not found.'];
        }

    } else {
        $response=['status'=>'error','message'=>'Invalid format. Enter like G0C.'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Canadian Postal Code Lookup</title>
<style>
body {
    font-family: Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
    background:#f0f0f0;
}
.container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 400px;
    text-align: center;
    box-sizing: border-box; /* ensures padding is included in width */
}
input, button {
    padding: 10px;
    width: calc(100% - 20px); /* accounts for container padding */
    margin: 5px 0; /* small spacing between elements */
    font-size: 16px;
    text-transform: uppercase;
    box-sizing: border-box;
}
input {
    padding:10px;
    width:100%;
    margin-bottom:10px;
    font-size:16px;
    text-transform:uppercase;
}
button {
    padding:10px;
    width:100%;
    background-color:#007bff;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-size:16px;
}
button:hover {
    background-color:#0056b3;
}
#result {
    margin-top:20px;
    font-size:18px;
}
.error { color:red; }
.success { color:green; }
</style>
</head>
<body>
<div class="container">
    <h2>Postal Code Lookup</h2>
    <input type="text" id="fsaInput" placeholder="Enter first 3 characters (e.g., G0C)" maxlength="3">
    <button id="lookupBtn">Find Destination</button>
    <div id="result" aria-live="polite"></div>
</div>
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('fsaInput');
    const resultDiv = document.getElementById('result');
    const button = document.getElementById('lookupBtn');

    function showMessage(message, type='success') {
        resultDiv.innerHTML = message;
        resultDiv.className = type;
    }

    async function lookupFSA() {
        const fsaInput = input.value.trim().toUpperCase();

        // Client-side validation: Letter-Digit-Letter
        if (!/^[A-Z][0-9][A-Z]$/.test(fsaInput)) {
            showMessage('Please enter a valid Postal Code<br>(e.g., G0C).', 'error');
            input.value = '';  // clear input
            input.focus();     // return focus
            return;
        }

        try {
            const response = await fetch(window.location.href, {
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:'fsa=' + encodeURIComponent(fsaInput)
            });

            if (!response.ok) throw new Error('Network response not ok');

            const data = await response.json();

            if (data.status === 'success') {
                showMessage(`${fsaInput} = ${data.destination}`, 'success');
            } else {
                showMessage(data.message || 'Unknown error.', 'error');
            }
        } catch (err) {
            console.error('Lookup failed:', err);
            showMessage('An error occurred. Please try again.', 'error');
        }

        // Clear input and focus for next lookup
        input.value = '';
        input.focus();
    }


    // Click button triggers lookup
    button.addEventListener('click', lookupFSA);

    // Press Enter triggers lookup
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            lookupFSA();
        }
    });

    // Focus input on page load
    input.focus();
});
</script>
</body>
</html>