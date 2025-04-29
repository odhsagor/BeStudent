<?php
session_start();
if (!isset($_GET['id'])) {
    echo "Invalid Book ID!";
    exit();
}

$book_id = $_GET['id'];

$host = 'localhost';     
$dbname = 'bestudent';
$username = 'root';      
$password = '';  

$error = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
$stmt = $conn->prepare("SELECT name, pdf_data FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$book) {
    echo "Book not found with ID: " . $book_id;
    exit();
}
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF - <?php echo htmlspecialchars($book['name']); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
</head>
<body>
    <h3>Viewing PDF: <?php echo htmlspecialchars($book['name']); ?></h3>
    <div id="pdf-viewer"></div>

    <script>
        var pdfData = "<?php echo base64_encode($book['pdf_data']); ?>";
        var byteCharacters = atob(pdfData);
        var byteArrays = [];
        for (var offset = 0; offset < byteCharacters.length; offset++) {
            byteArrays.push(byteCharacters.charCodeAt(offset));
        }

        var pdfBytes = new Uint8Array(byteArrays);
        pdfjsLib.getDocument(pdfBytes).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                var scale = 1.5;
                var viewport = page.getViewport({ scale: scale });

                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                document.getElementById('pdf-viewer').appendChild(canvas);

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }).catch(function(error) {
            console.error('Error loading PDF: ', error);
        });
    </script>

</body>
</html>
