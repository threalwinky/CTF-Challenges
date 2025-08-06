<?php

require __DIR__.'/vendor/autoload.php';
require './logging.php';
require './optimizer.php';
use Symfony\Component\Filesystem\Path;

$upload_success = false;
$original_file_path = '';
$optimized_file_path = '';
$original_file_size = 0;
$optimized_file_size = 0;
$filename = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $counter = count(glob('uploads/*')) + 1;
    $target_dir = $_POST['target_dir'];
    $filename = $_FILES['file']['name'];
    $image_extension = pathinfo($filename, PATHINFO_EXTENSION);
    $image_type = $_FILES['file']['type'];
    $real_file_name = $counter.'.'.$image_extension;
    $file_path = Path::join($target_dir, $real_file_name);
    $optimized_file_path = Path::join('optimized', $real_file_name);
    $original_file_path = Path::join('uploads', $real_file_name);
    
    if (!$target_dir){
        echo "No target directory found.";
        exit(-1);
    }

    if (preg_match('#^/|(\.\.)#', $original_file_path)) {
        echo "Filtered.";
        exit(-1);
    }

    $allowed_extension = ['png', 'jpg', 'jpeg', 'jfif', 'svg', 'webp', 'gif'];
    if (!in_array(strtolower($image_extension), $allowed_extension)) {
        echo "Invalid image type.";
        exit(-1);
    }

    $allowed_type = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    if (!in_array(strtolower($image_type), $allowed_type)) {
        echo "Invalid image type.";
        exit(-1);
    }

    
    move_uploaded_file($_FILES['file']['tmp_name'], $original_file_path);
    copy($original_file_path, $optimized_file_path);

    $optimizerChain->optimize($file_path);

    $upload_success = true;
    $original_file_size = filesize($original_file_path);
    $optimized_file_size = filesize( $optimized_file_path);

    $logger->info('The user has uploaded file '.$filename);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimizer</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖼️ Optimizer</h1>
            <p>Upload your images and see the magic of optimization</p>
        </div>
        
        <div class="upload-section">
            <form class="upload-form" action="" method="post" enctype="multipart/form-data">
                <h2>Choose an image to optimize</h2>
                <div class="button-group">
                    <div class="file-input-wrapper">
                        <input type="file" name="file" id="file" class="file-input" accept="image/*" required>
                        <label for="file" class="file-input-label">
                            📁 Choose File
                            <div style="font-size: 0.9em; margin-top: 5px; opacity: 0.8;">
                                Supported: PNG, JPG, JPEG, GIF, WebP, SVG
                            </div>
                        </label>
                    </div>
                    <input type="hidden" name="target_dir" value="optimized">
                    <input type="submit" value="🚀 Optimize Image" class="submit-btn" id="submit-btn" disabled>
                </div>
            </form>
        </div>
        
        <?php if ($upload_success): ?>
        <div class="comparison-section">
            <h2 class="comparison-title">✨ Optimization Results</h2>
            
            <?php 
            $compression_ratio = round((($original_file_size - $optimized_file_size) / $original_file_size) * 100, 1);
            $savings_kb = round(($original_file_size - $optimized_file_size) / 1024, 1);
            ?>
            
            <div class="compression-ratio">
                <h3>📊 Compression Results</h3>
                <div class="ratio-value"><?php echo $compression_ratio; ?>%</div>
                <div class="savings">Reduced by <?php echo $savings_kb; ?> KB</div>
                <div class="savings">Original: <?php echo htmlspecialchars($filename); ?></div>
            </div>
            
            <div class="comparison-container">
                <div class="image-card before">
                    <h3>📤 Before Optimization</h3>
                    <img src="<?php echo htmlspecialchars($original_file_path); ?>" alt="Original Image" class="image-display">
                    <div class="file-info">
                        <div class="file-size"><?php echo number_format($original_file_size); ?> bytes</div>
                        <div><?php echo round($original_file_size / 1024, 1); ?> KB</div>
                    </div>
                    <a href="<?php echo htmlspecialchars($original_file_path); ?>" download="original_<?php echo htmlspecialchars($filename); ?>" class="download-btn">
                        💾 Download Original
                    </a>
                </div>
                
                <div class="image-card after">
                    <h3>📥 After Optimization</h3>
                    <img src="<?php echo htmlspecialchars($optimized_file_path); ?>" alt="Optimized Image" class="image-display">
                    <div class="file-info">
                        <div class="file-size"><?php echo number_format($optimized_file_size); ?> bytes</div>
                        <div><?php echo round($optimized_file_size / 1024, 1); ?> KB</div>
                    </div>
                    <a href="<?php echo htmlspecialchars($optimized_file_path); ?>" download="optimized_<?php echo htmlspecialchars($filename); ?>" class="download-btn">
                        💾 Download Optimized
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const label = document.querySelector('.file-input-label');
            const submitBtn = document.getElementById('submit-btn');
            
            if (fileName) {
                label.innerHTML = `
                    📁 ${fileName}
                    <div style="font-size: 0.9em; margin-top: 5px; opacity: 0.8;">
                        Ready to optimize!
                    </div>
                `;
                label.classList.add('file-selected');
                submitBtn.disabled = false;
                submitBtn.style.display = 'inline-block';
            } else {
                label.innerHTML = `
                    📁 Choose File
                    <div style="font-size: 0.9em; margin-top: 5px; opacity: 0.8;">
                        Supported: PNG, JPG, JPEG, GIF, WebP, SVG
                    </div>
                `;
                label.classList.remove('file-selected');
                submitBtn.disabled = true;
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerHTML = '⏳ Optimizing...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>