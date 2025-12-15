<?php

$errors = [];
$pdfUrl = "";

// Make sure uploads folder exists
$uploadDir = __DIR__ . "/uploads";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// If the user submitted the form
if (isset($_POST["upload"])) {

    // 1) Check that a file was selected and uploaded successfully
    if (!isset($_FILES["pdf"]) || $_FILES["pdf"]["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Please select a PDF file.";
    } else {
        $tmpPath = $_FILES["pdf"]["tmp_name"];
        $originalName = $_FILES["pdf"]["name"];

        // 2) Check file extension (must be .pdf)
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext !== "pdf") {
            $errors[] = "Only PDF files are allowed.";
        }

        // 3) (Optional but recommended) Check MIME type really is a PDF
        if (empty($errors)) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpPath);

            if ($mime !== "application/pdf") {
                $errors[] = "Invalid file type. Please upload a real PDF.";
            }
        }

        // 4) If everything is okay, move the file into uploads/
        if (empty($errors)) {
            // Use a safe random file name to avoid weird characters
            $newName = bin2hex(random_bytes(16)) . ".pdf";
            $destPath = $uploadDir . "/" . $newName;

            if (move_uploaded_file($tmpPath, $destPath)) {
                // Build a URL path so the browser can open it
                $pdfUrl = "uploads/" . $newName;
            } else {
                $errors[] = "Upload failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Simple PDF Viewer</title>
  <link rel="stylesheet" href="css/style.css">

</head>
<body>

  <div class="card">


    <h2>Upload a PDF to view it</h2>

    <!-- Show errors if any -->
    <?php if (!empty($errors)): ?>
      <div>
        <strong>Errors:</strong><br>
        <?php foreach ($errors as $e): ?>
          <?php echo htmlspecialchars($e); ?><br>
        <?php endforeach; ?>
      </div>
      <hr>
    <?php endif; ?>

    <!-- Upload form -->
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="pdf" accept="application/pdf" required>
      <button type="submit" name="upload" value="1">Upload</button>
    </form>

    <hr>

    <!-- If upload succeeded, show a link and also embed it -->
    <?php if ($pdfUrl !== ""): ?>
      <p>
        Uploaded successfully:
        <a href="<?php echo htmlspecialchars($pdfUrl); ?>" target="_blank">Open PDF</a>
      </p>

      <!-- Embed PDF in the page -->
      <iframe
        src="<?php echo htmlspecialchars($pdfUrl); ?>"
        width="100%"
        height="600"
        style="border: 1px solid #ccc;"
      ></iframe>
    <?php endif; ?>
  </div>

</body>
</html>
