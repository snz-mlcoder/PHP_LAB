<?php
// Detect language from URL (?lang=en or ?lang=it)
$lang = $_GET['lang'] ?? 'en';

// Load language file (fallback to English)
$langFile = "languages/$lang.php";
$text = file_exists($langFile)
    ? require $langFile
    : require "languages/en.php";

// Handle form submission
$username = "";

if (isset($_GET['submit'])) {
    $username = trim($_GET['name'] ?? "");
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <title>PHP Input Recorder</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f5f5;
        }

        .app {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lang-switch {
            margin-bottom: 20px;
        }

        .lang-switch a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .hero {
            font-size: 28px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="text"] {
            height: 35px;
            width: 250px;
            font-size: 18px;
            border: none;
            border-bottom: 2px solid green;
            background: transparent;
            outline: none;
        }

        input[type="submit"] {
            height: 35px;
            width: 250px;
            background: crimson;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="app">

        <!-- Language Switch -->
        <div class="lang-switch">
            <a href="?lang=en"><?php echo $text['lang_en']; ?></a> |
            <a href="?lang=it"><?php echo $text['lang_it']; ?></a>
        </div>

        <!-- Dynamic Title -->
        <h1 class="hero">
            <?php
                if (isset($_GET['submit']) && $username !== "") {
                    echo $text['title_result'] . " " . htmlspecialchars($username);
                } elseif (isset($_GET['submit']) && $username === "") {
                    echo $text['error'];
                } else {
                    echo $text['title_default'];
                }
            ?>
        </h1>

        <!-- Form -->
        <form action="" method="get">
            <input type="hidden" name="lang" value="<?php echo htmlspecialchars($lang); ?>">

            <input
                type="text"
                name="name"
                placeholder="<?php echo $text['placeholder']; ?>"
                value="<?php echo htmlspecialchars($username); ?>"
            >

            <input
                type="submit"
                name="submit"
                value="<?php echo $text['submit']; ?>"
            >
        </form>

        <!-- Footer -->
        <h4 class="footer">
            <?php echo $text['made_with']; ?>
        </h4>

    </div>
</body>
</html>
