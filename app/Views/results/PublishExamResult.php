<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publish Exam Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Publish Exam Results</h1>
        
        <div class="form-group">
            <label for="session">Academic Session:</label>
            <select id="session" class="form-control" required>
                <option value="">Select Session</option>
                <?php foreach ($sessions as $session): ?>
                    <option value="<?= $session['id'] ?>"><?= $session['session'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="class">Class:</label>
            <select id="class" class="form-control" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"><?= $class['class'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="section">Section:</label>
            <select id="section" class="form-control">
                <option value="">All Sections</option>
            </select>
        </div>

        <div class="form-group">
            <label for="exam">Exam:</label>
            <select id="exam" class="form-control" required>
                <option value="">Select Exam</option>
            </select>
        </div>

        <button onclick="calculateResults()" class="btn btn-primary">
            Calculate and Publish Results
        </button>

        <div id="results" class="mt-4"></div>
    </div>

    <script>
        // Add your JavaScript code for handling form submission and displaying results
        async function calculateResults() {
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const sectionId = document.getElementById('section').value;
            const sessionId = document.getElementById('session').value;

            if (!examId || !classId || !sessionId) {
                alert('Please select all required fields');
                return;
            }

            try {
                const response = await fetch('<?= base_url('results/calculate') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `exam_id=${examId}&class_id=${classId}&section_id=${sectionId}&session_id=${sessionId}`
                });

                const data = await response.json();
                if (data.status === 'success') {
                    // Display results
                    displayResults(data.data);
                } else {
                    alert('Failed to calculate results: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while calculating results');
            }
        }

        function displayResults(results) {
            // Implement your results display logic here
        }

        // Add event listeners for dependent dropdowns
    </script>
</body>
</html>