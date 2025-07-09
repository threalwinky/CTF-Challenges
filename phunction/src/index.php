<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Phunction</title>
  <style>
    body {
      background-color: #111;
      color: #33ff77;
      font-family: monospace;
      margin: 0;
      padding: 30px;
    }

    .terminal {
      max-width: 700px;
      margin: auto;
      background-color: #000;
      border: 2px solid #33ff77;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 20px #33ff77;
    }

    h1 {
      text-align: center;
      color: #33ff77;
      font-size: 1.8em;
    }

    .alert {
      background-color: #222;
      border-left: 5px solid #ffcc00;
      padding: 15px;
      margin-bottom: 20px;
      color: #ffcc00;
    }

    label, input, button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
      font-size: 1em;
    }

    input[type="text"] {
      background: #111;
      color: #33ff77;
      border: 1px solid #33ff77;
      padding: 10px;
      border-radius: 5px;
    }

    button {
      background: #33ff77;
      color: #000;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .output {
      background: #111;
      border: 1px dashed #33ff77;
      padding: 15px;
      margin-top: 20px;
      white-space: pre-wrap;
      color: #99ffbb;
    }

    .info {
      font-style: italic;
      color: #aaa;
    }

    .success {
      color: #00ff99;
    }

    .error {
      color: #ff4d4d;
    }

    .filtered-code {
      color: #ccc;
    }
  </style>
</head>
<body>
  <div class="terminal">
    <h1>Phunction 🚦</h1>

    <div class="alert">
      [!] Caution: Only a curated list of harmless PHP functions are allowed.<br>
      Dangerous symbols will be filtered.
    </div>

    <form method="GET">
      <label for="ph">Enter PHP Function Name:</label>
      <input type="text" id="ph" name="ph" placeholder="try: strlen, time, phpinfo" />
      <button type="submit">🧪 Run</button>
    </form>

    <?php
    if (isset($_GET['ph'])) {
        echo '<div class="output">';

        $raw = $_GET['ph'];
        $clean = str_replace(
            ['$', '(', ')', '`', '"', "'", '+', ':', '/', '!', '?', '[', ']', '.'],
            '',
            $raw
        );
        $payload = $clean . '();';

        echo "🧹 <span class=\"info\">Filtered Input:</span>\n";
        echo "<code class=\"filtered-code\">$payload</code>\n\n";

        echo "🔧 <span class=\"info\">Function Output:</span>\n";
        echo "<div style=\"margin-left:1em;\">\n";

        try {
            ob_start();
            eval($payload);
            $output = ob_get_clean();

            if (!empty($output)) {
                echo "<span class=\"success\">✔️ Executed Successfully:</span>\n";
                echo htmlspecialchars($output);
            } else {
                echo "<span class=\"success\">✔️ Executed, but returned no output.</span>";
            }
        } catch (Error $e) {
            echo "<span class=\"error\">❌ PHP Error: " . htmlspecialchars($e->getMessage()) . "</span>";
        } catch (Exception $e) {
            echo "<span class=\"error\">❌ Exception Thrown: " . htmlspecialchars($e->getMessage()) . "</span>";
        }

        echo "</div>\n</div>";
    }
    ?>
  </div>
</body>
</html>
