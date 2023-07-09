<?php
$targetDir = "uploads/";

function getMimeTypeFromURL($url) {
    $headers = get_headers($url, 1);

    if (isset($headers['Content-Type'])) {
        return $headers['Content-Type'];
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadOk = 1;
    $fileType = "";

    // Handle file upload
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the uploaded file type is allowed
        $allowedMimeTypes = array(
            'image/png',
            'image/jpeg',
            'image/gif',
            'video/mp4'
        );
        $allowedExtensions = array(
            'png',
            'jpg',
            'jpeg',
            'gif',
            'mp4'
        );
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $_FILES["fileToUpload"]["tmp_name"]);
        finfo_close($finfo);

        // Check if the file type is allowed
        if (!in_array($fileMimeType, $allowedMimeTypes) || !in_array($fileType, $allowedExtensions)) {
            echo "Sorry, only PNG, JPG, JPEG, GIF, and MP4 files are allowed.";
            $uploadOk = 0;
        }

        // If there are no errors, move the uploaded file to the target directory
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                echo "File " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

                // Run the deletion script after 30 seconds using AJAX
                echo "<script>
                        setTimeout(function() {
                            var file = '" . basename($targetFile) . "';
                            var xhttp = new XMLHttpRequest();
                            xhttp.open('GET', 'delete.php?file=' + encodeURIComponent(file), true);
                            xhttp.send();
                        }, 3 * 24 * 60 * 60 * 1000); // 3 days delay
                    </script>";
            } else {
                echo "Sorry, there was an error uploading the file: " . $_FILES["fileToUpload"]["error"];
            }
        }
    }

    // Handle URL upload
    if (!empty($_POST["urlToUpload"])) {
        $url = $_POST["urlToUpload"];
        $fileMimeType = getMimeTypeFromURL($url); // Implement getMimeTypeFromURL() function
        if ($fileMimeType !== false && in_array($fileMimeType, $allowedMimeTypes)) {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extension;
            $targetFile = $targetDir . $filename;

            // Retrieve the file from the URL and save it
            if (file_put_contents($targetFile, file_get_contents($url))) {
                echo "File from URL has been uploaded with the name: " . $filename;

                // Run the deletion script after 30 seconds using AJAX
                echo "<script>
                        setTimeout(function() {
                            var file = '" . basename($targetFile) . "';
                            var xhttp = new XMLHttpRequest();
                            xhttp.open('GET', 'delete.php?file=' + encodeURIComponent(file), true);
                            xhttp.send();
                        }, 3 * 24 * 60 * 60 * 1000); // 3 days delay
                    </script>";
            } else {
                echo "Sorry, there was an error uploading the file from URL.";
            }
        } else {
            echo "Sorry, the file from the URL is not allowed.";
        }
    }
}
?> 
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Upload and delete files on the server.">
    <meta name="keywords" content="upload, delete, files, server">
    <meta name="author" content="Xnuvers007">
    <meta property="og:title" content="Upload File">
    <meta property="og:description" content="Upload and delete files on the server.">
    <meta property="og:image" content="https://up.xnuvers007.repl.co/icon.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://https://up.xnuvers007.repl.co">
    <meta property="og:site_name" content="Xnuvers007|Upload Files">
    <link rel="icon" type="image/x-icon" href="https://up.xnuvers007.repl.co/icon.ico">
    <link rel="icon" type="image/jpeg" href="https://up.xnuvers007.repl.co/icon.ico">
    <title>Upload File</title>
    <style>
        :root {
            --text-color: #333;
            --background-color: #f0f0f0;
            --container-background-color: #fff;
            --button-background-color: #222;
            --button-text-color: #fff;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: var(--container-background-color);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .paragraf {
          text-align: center;
          font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input[type="file"],
        .form-group input[type="text"] {
            margin-top: 5px;
        }

        button {
            background-color: var(--button-background-color);
            color: var(--button-text-color);
        }

        .dark-mode {
            --text-color: #fff;
            --background-color: #333;
            --container-background-color: #222;
            --button-background-color: #fff;
            --button-text-color: #333;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center; font-weight: bold;">Files will be deleted in 3 days </h3>
    <!--<img class="responsive-image" style="display: block; margin: 0 auto;" height="auto" width="100%" src="icon.webp" loading="lazy" alt="Image" /> -->
    <div class="container">
      <p class="paragraf">File yang terupload ada pada folder uploads => https://up.xnuvers007.repl.co/<br />uploads/NamaFileKamu</p>
        <h2>Upload File</h2>
        <form method="post" action="index.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Choose file:</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
              <br />
              <br />
                <label for="urlToUpload">or enter URL (https://example.com/gambar.png):</label>
                <input type="text" name="urlToUpload" id="urlToUpload">
            </div>
            <button type="submit" name="submit">Upload</button>
        </form>
    </div>

    <button onclick="toggleDarkMode()">Toggle Dark Mode</button>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
