<?php
$dir = __DIR__ . '/res';
$files = scandir($dir);

// Filter image and text files
$images = [];
$texts = [];

foreach ($files as $file) {
    $path = "$dir/$file";
    if (is_file($path)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $name = pathinfo($file, PATHINFO_FILENAME);
        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $images[$name] = $file;
        } elseif (strtolower($ext) === 'txt') {
            $texts[$name] = $file;
        }
    }
}

// Find matching pairs
$pairs = array_intersect_key($images, $texts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Style Gallery</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            background: #f5f5f5;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 250px;
            background: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #444;
            transition: background 0.3s;
        }

        .sidebar ul li:hover {
            background: #555;
        }

        .toggle-btn {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            background: #333;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Main Content */
        .container {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            max-width: 1000px;
        }

        /* Section Layout */
        .section {
            display: flex;
            align-items: stretch; /* Ensure child elements take full height */
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            min-height: 250px; /* Ensures a reasonable height */
        }

        /* Image Styling */
        .image-container {
            flex: 1;
            padding: 10px;
        }

        .image-container img {
            max-width: 100%;
            border-radius: 10px;
        }

        /* Divider */
        .divider {
            width: 3px;
            background: #ddd;
            height: 80%;
            margin: 0 20px;
            border-radius: 5px;
        }

        /* Text Container */
        .text-container {
            display: flex;
            flex-direction: column;
            flex: 1; /* Take up remaining space */
        }

        .text-container h2 {
            margin-bottom: 10px;
        }

        /* Textarea Styling */
        .text-container textarea {
            flex: 1; /* Makes textarea take full height */
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            resize: none; /* Prevent resizing */
            background: #f9f9f9;
            overflow-y: auto; /* Enable scrolling */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 200px;
            }

            .toggle-btn {
                display: block;
            }

            .container {
                margin-left: 0;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .section {
                flex-direction: column;
                text-align: center;
                min-height: auto; /* Allow smaller sections */
            }

            .divider {
                width: 80%;
                height: 3px;
                margin: 20px 0;
            }

            .text-container textarea {
                height: 150px; /* Set fixed height for mobile */
            }
        }

    </style>
</head>
<body>
    <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
    <div class="sidebar">
        <ul>
            <?php foreach ($pairs as $name => $file): ?>
                <li onclick="scrollToSection(<?php echo array_search($name, array_keys($pairs)); ?>)">
                    <?= htmlspecialchars($name) ?>
                    
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="container">
        <?php foreach ($pairs as $name => $file): ?>
            <div class="section">
                <div class="image-container">
                    <img src="res/<?= htmlspecialchars($images[$name]) ?>" alt="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="divider"></div>
                <div class="text-container">
                    <h2><?= htmlspecialchars($name) ?></h2>
                    <textarea readonly wrap="soft"><?= htmlspecialchars(file_get_contents("$dir/" . $texts[$name])) ?></textarea>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function scrollToSection(index) {
            const sections = document.querySelectorAll('.section');
            if (sections[index]) {
                sections[index].scrollIntoView({ behavior: 'smooth' });
            }
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
        }
    </script>
</body>
</html>