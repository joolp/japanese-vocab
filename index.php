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
    <title>Image and Text Display</title>
    <style>
        body { font-family: Consolas, monospace; margin: 0; overflow: hidden; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; height: 100vh; }
        .container { width: 100vw; height: 100vh; overflow-y: hidden; display: flex; flex-direction: column; align-items: center; scroll-snap-type: y mandatory; position: relative; }
        .section { width: 60vw; height: 80vh; display: flex; flex-direction: column; justify-content: center; align-items: center; scroll-snap-align: center; margin: 10vh 0; transition: transform 0.5s ease, opacity 0.5s ease; filter: blur(5px); opacity: 0.5; }
        .active { transform: scale(1.1); filter: blur(0); opacity: 1; }
        .card { width: 100%; padding: 20px; background: white; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: space-between; transition: transform 0.3s ease; }
        .card img { max-width: 40%; height: auto; border-radius: 5px; }
        .card .text { flex: 1; padding: 20px; font-size: 1.2em; height: 40vh; overflow-y: auto; border: 1px solid #ccc; background: #f9f9f9; border-radius: 5px; }
        .divider { width: 2px; background: black; height: 80%; margin: 0 20px; }
        .sidebar { position: fixed; left: -250px; top: 0; width: 250px; height: 100%; background: #333; color: white; padding: 20px; transition: left 0.3s ease; overflow-y: auto; z-index: 1000; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .sidebar ul { list-style: none; padding: 0; width: 100%; }
        .sidebar ul li { margin: 10px 0; cursor: pointer; padding: 10px; background: #444; border-radius: 5px; }
        .sidebar ul li:hover { background: #555; }
        .toggle-btn { position: fixed; left: 10px; top: 10px; background: #444; color: white; padding: 10px; cursor: pointer; border-radius: 5px; z-index: 1100; }
        .sidebar.active { left: 0; }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let sections = document.querySelectorAll(".section");
            let index = 0;
            let scrollThreshold = 40;
            let scrollAmount = 0;
            let sidebar = document.querySelector(".sidebar");
            let toggleBtn = document.querySelector(".toggle-btn");

            function updateActiveSection() {
                sections.forEach((section, idx) => {
                    section.classList.toggle("active", idx === index);
                });
            }

            window.scrollToSection = function(idx) {
                if (idx >= 0 && idx < sections.length) {
                    sections[idx].scrollIntoView({ behavior: "smooth", block: "center" });
                    index = idx;
                    scrollAmount = 0;
                    updateActiveSection();
                }
            }

            document.addEventListener("wheel", function (event) {
                let activeText = document.querySelector(".section.active .text");
                if (activeText && (activeText.matches(":hover") || document.activeElement === activeText)) {
                    return;
                }

                scrollAmount += event.deltaY;
                if (scrollAmount > scrollThreshold) {
                    scrollToSection(index + 1);
                } else if (scrollAmount < -scrollThreshold) {
                    scrollToSection(index - 1);
                }
                event.preventDefault();
            }, { passive: false });

            toggleBtn.addEventListener("click", function () {
                sidebar.classList.toggle("active");
            });

            updateActiveSection();
        });
    </script>
</head>
<body>
    <div class="toggle-btn">☰</div>
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
                <div class="card">
                    <img src="res/<?= htmlspecialchars($images[$name]) ?>" alt="<?= htmlspecialchars($name) ?>">
                    <div class="divider"></div>
                    <div class="text" tabindex="0">
                        <?php 
                        $textContent = file_get_contents("$dir/" . $texts[$name]);
                        echo nl2br(htmlspecialchars($textContent));
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
