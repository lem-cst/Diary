<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: love2.php");
    exit();
}

$dataFile = 'gallery_data.json';
$uploadDir = 'uploads/';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $loveQuotes = [
            "Love is composed of a single soul inhabiting two bodies.",
            "The best thing to hold onto in life is each other.",
            "I love you not only for what you are, but for what I am when I am with you.",
            "We loved with a love that was more than love.",
            "Love recognizes no barriers."
        ];
        $quote = $loveQuotes[array_rand($loveQuotes)];
        
        $images = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
        $images[] = ['filename' => $filename, 'quote' => $quote];
        file_put_contents($dataFile, json_encode($images));
    }
    exit();
}

// Handle delete/edit actions
if (isset($_GET['action'])) {
    $images = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    $filename = $_POST['filename'] ?? '';
    
    foreach ($images as $key => $image) {
        if ($image['filename'] === $filename) {
            switch ($_GET['action']) {
                case 'delete':
                    unlink($uploadDir . $filename);
                    unset($images[$key]);
                    break;
                case 'edit':
                    $images[$key]['quote'] = $_POST['quote'] ?? $image['quote'];
                    break;
            }
        }
    }
    file_put_contents($dataFile, json_encode(array_values($images)));
    exit();
}

$images = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
    <title>love
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ffe6f0, #ffb3d9);
            margin: 0;
            padding: 20px;
        }

        .dashboard-header {
            text-align: center;
            color: #ff1a66;
            margin-bottom: 30px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .love-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(255, 0, 102, 0.2);
            transition: transform 0.3s ease;
        }

        .love-card:hover {
            transform: translateY(-5px);
        }

        .love-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .quote-overlay {
            position: absolute;
            bottom: 0;
            background: rgba(255, 26, 102, 0.8);
            color: white;
            width: 100%;
            padding: 15px;
            text-align: center;
            font-style: italic;
        }

        .add-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #ff1a66;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(255, 0, 102, 0.3);
            transition: transform 0.3s ease;
        }

        .add-button:hover {
            transform: scale(1.1);
        }

        #file-input {
            display: none;
        }

        .card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #ff1a66;
            color: white;
        }
        .delete-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #ff1a66;
        }
        
        .delete-btn:hover {
            background: #ff4444 !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <a href="logout.php" style="position: absolute; top: 20px; right: 20px; color: #ff1a66; font-weight: bold;">Logout</a>

    <div class="dashboard-header">
        <h1>❤️ Love & Inspiration Gallery ❤️</h1>
    </div>

    <div class="gallery" id="gallery">
        <?php foreach ($images as $image): ?>
        <div class="love-card">
            <img src="uploads/<?= htmlspecialchars($image['filename']) ?>" class="love-image" alt="Love Image">
            <div class="quote-overlay">"<?= htmlspecialchars($image['quote']) ?>"</div>
            <div class="card-actions">
                <button class="action-btn" onclick="editQuote(this)"><i class="fas fa-edit"></i></button>
                <button class="action-btn delete-btn" onclick="deleteCard(this, '<?= htmlspecialchars($image['filename']) ?>')"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="add-button" onclick="document.getElementById('file-input').click()">
        <i class="fas fa-plus fa-2x"></i>
    </div>

    <input type="file" id="file-input" accept="image/*">

    <script>
        async function handleImageUpload(event) {
            const formData = new FormData();
            formData.append('image', event.target.files[0]);
            
            await fetch('love1.php', {
                method: 'POST',
                body: formData
            });
            location.reload();
        }

        function editQuote(button) {
            const card = button.closest('.love-card');
            const quoteDiv = card.querySelector('.quote-overlay');
            const filename = card.querySelector('img').src.split('/').pop();
            const newQuote = prompt('Edit quote:', quoteDiv.textContent.replace(/"/g, ''));
            
            if (newQuote) {
                const formData = new FormData();
                formData.append('filename', filename);
                formData.append('quote', newQuote);
                
                fetch('love1.php?action=edit', { method: 'POST', body: formData })
                    .then(() => quoteDiv.textContent = `"${newQuote}"`);
            }
        }

        function deleteCard(button, filename) {
            if (confirm('Delete this memory?')) {
                const formData = new FormData();
                formData.append('filename', filename);
                
                fetch('love1.php?action=delete', { method: 'POST', body: formData })
                    .then(() => button.closest('.love-card').remove());
            }
        }

        document.getElementById('file-input').onchange = handleImageUpload;
    </script>
</body>
</html>