<?php
// copy-storage-files.php
// Copies all storage files to public/storage folder

set_time_limit(300); // 5 minutes timeout
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Copy Storage Files</title></head><body>";
echo "<h2>📁 Copying Storage Files to Public</h2>";

$source = __DIR__ . '/../storage/app/public';
$destination = __DIR__ . '/storage';

echo "<p><strong>Source:</strong> $source</p>";
echo "<p><strong>Destination:</strong> $destination</p>";

// Check if source exists
if (!is_dir($source)) {
    echo "<p style='color: red;'>❌ Source directory not found!</p>";
    echo "<p>Checking alternative paths...</p>";
    
    // Debug: show what exists
    $parent = dirname(__DIR__);
    echo "<p>Parent directory: $parent</p>";
    if (is_dir("$parent/storage")) {
        echo "<p style='color: green;'>✅ Found storage directory</p>";
        if (is_dir("$parent/storage/app")) {
            echo "<p style='color: green;'>✅ Found storage/app directory</p>";
            if (is_dir("$parent/storage/app/public")) {
                echo "<p style='color: green;'>✅ Found storage/app/public directory</p>";
                $source = "$parent/storage/app/public";
            }
        }
    }
    
    if (!is_dir($source)) {
        echo "<p style='color: red;'>❌ Cannot locate storage files!</p>";
        exit;
    }
}

echo "<p style='color: green;'>✅ Source directory found</p>";

// Create destination if doesn't exist
if (!is_dir($destination)) {
    if (mkdir($destination, 0755, true)) {
        echo "<p style='color: green;'>✅ Created destination directory</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create destination directory</p>";
        exit;
    }
} else {
    echo "<p style='color: orange;'>⚠️ Destination already exists, will overwrite files</p>";
}

// Copy files recursively
function copyDirectory($src, $dst) {
    $count = 0;
    
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    
    $dir = opendir($src);
    if (!$dir) {
        echo "<p style='color: red;'>❌ Cannot open source directory: $src</p>";
        return 0;
    }
    
    while (($file = readdir($dir)) !== false) {
        if ($file == '.' || $file == '..') continue;
        
        $srcPath = $src . '/' . $file;
        $dstPath = $dst . '/' . $file;
        
        if (is_dir($srcPath)) {
            $count += copyDirectory($srcPath, $dstPath);
        } else {
            if (copy($srcPath, $dstPath)) {
                $count++;
                // Show progress every 10 files
                if ($count % 10 == 0) {
                    echo "✅ Copied $count files...<br>";
                    flush();
                    if (ob_get_level() > 0) ob_flush();
                }
            } else {
                echo "<p style='color: orange;'>⚠️ Failed to copy: $file</p>";
            }
        }
    }
    closedir($dir);
    
    return $count;
}

echo "<p>Starting copy process...</p>";
echo "<hr>";
flush();
if (ob_get_level() > 0) ob_flush();

$totalCopied = copyDirectory($source, $destination);

echo "<hr>";
echo "<h3 style='color: green;'>✅ SUCCESSFULLY COPIED $totalCopied FILES!</h3>";

// Verify some files
if ($totalCopied > 0) {
    echo "<p style='color: blue;'>🎉 Your images should now be accessible!</p>";
    
    // List some copied files
    $files = array_diff(scandir($destination), array('.', '..'));
    $sampleFiles = array_slice($files, 0, 10);
    
    echo "<p><strong>Sample files copied:</strong></p><ul>";
    foreach ($sampleFiles as $file) {
        if (is_file("$destination/$file")) {
            $url = "/safaris/public/storage/$file";
            echo "<li>$file - <a href='$url' target='_blank'>Test link</a></li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color: orange;'>⚠️ No files were copied. Check if storage/app/public has any files.</p>";
}

echo "<br><hr>";
echo "<h3 style='color: red;'>⚠️ IMPORTANT: DELETE THIS FILE NOW FOR SECURITY!</h3>";
echo "<p style='color: orange;'>📝 NOTE: Every time you upload NEW images, you need to run this script again!</p>";
echo "<p><a href='/safaris/public/' style='color: blue; font-weight: bold;'>← Go back to website</a></p>";

echo "</body></html>";
?>