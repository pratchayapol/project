<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate PDF</title>
</head>
<body>
    <h1>Generate PDF Report</h1>
    <form method="POST" action="generatePDF.php">
        <label for="month">Month:</label>
        <input type="text" id="month" name="month" required>
        <br><br>
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" required>
        <br><br>
        <button type="submit">Generate PDF</button>
    </form>
</body>
</html>
