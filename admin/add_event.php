<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Event</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 500;
        }

        .error-message {
            background-color: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 0;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 30px;
            font-size: 16px;
            color: #333;
            background: transparent;
            outline: none;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input::placeholder {
            color: #aaa;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        button[type="submit"], .cancel-button {
            padding: 15px 40px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            min-width: 150px;
        }

        button[type="submit"] {
            background-color: #7E57C2;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #6A48B0;
        }

        .cancel-button {
            background-color: #e0e0e0;
            color: #333;
        }

        .cancel-button:hover {
            background-color: #d0d0d0;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Event</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" action="add_event.php">
            <div class="form-grid">
                <div class="left-column">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Event Name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <input type="date" name="date" required 
                               value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <input type="time" name="time" required 
                               value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <input type="text" name="location" placeholder="Location" required 
                               value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <textarea name="description" placeholder="Description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <input type="number" name="max_participants" placeholder="Max Participants" required 
                               value="<?php echo isset($_POST['max_participants']) ? htmlspecialchars($_POST['max_participants']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <input type="file" name="image">
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit">Add Event</button>
                <a href="../index.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
