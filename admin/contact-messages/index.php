<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$db = getDBConnection();

$message = '';
$error = '';

/*
 * Delete message
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);

    if ($deleteId && $deleteId > 0) {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param('i', $deleteId);

            if ($stmt->execute()) {
                $message = 'Message deleted successfully.';
            } else {
                $error = 'Unable to delete the message.';
            }

            $stmt->close();
        } else {
            $error = 'Unable to prepare delete request.';
        }
    } else {
        $error = 'Invalid message ID.';
    }
}

/*
 * Load contact messages
 */
$messages = [];

$result = $db->query(
    "SELECT id, name, email, message, created_at
     FROM contact_messages
     ORDER BY created_at DESC, id DESC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $result->free();
} else {
    $error = 'Unable to load contact messages.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | Admin</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .container {
            width: min(1200px, 94%);
            margin: 40px auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 30px;
            color: #111827;
        }

        .topbar p {
            margin: 7px 0 0;
            color: #6b7280;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 16px;
            background: #111827;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }

        .back-btn:hover {
            background: #374151;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .messages-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .count {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            color: #374151;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        .sender-name {
            font-weight: bold;
            color: #111827;
        }

        .email {
            color: #2563eb;
            text-decoration: none;
        }

        .email:hover {
            text-decoration: underline;
        }

        .message-text {
            max-width: 430px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .date {
            white-space: nowrap;
            color: #6b7280;
        }

        .delete-btn {
            border: 0;
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 13px;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #6b7280;
        }

        @media (max-width: 700px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar h1 {
                font-size: 25px;
            }

            th,
            td {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="topbar">
        <div>
            <h1>Contact Messages</h1>
            <p>Messages submitted through your portfolio contact form.</p>
        </div>

        <a href="../dashboard.php" class="back-btn">← Dashboard</a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="alert success">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="messages-card">

        <div class="card-header">
            <h2>Inbox</h2>
            <span class="count">
                <?= count($messages) ?> message<?= count($messages) === 1 ? '' : 's' ?>
            </span>
        </div>

        <?php if (empty($messages)): ?>

            <div class="empty">
                <h3>No messages yet</h3>
                <p>Contact form submissions will appear here.</p>
            </div>

        <?php else: ?>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($messages as $item): ?>

                        <tr>
                            <td>
                                <div class="sender-name">
                                    <?= htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>

                            <td>
                                <a
                                    class="email"
                                    href="mailto:<?= htmlspecialchars((string) $item['email'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <?= htmlspecialchars((string) $item['email'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </td>

                            <td>
                                <div class="message-text">
                                    <?= htmlspecialchars((string) $item['message'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>

                            <td class="date">
                                <?= htmlspecialchars((string) $item['created_at'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    <input
                                        type="hidden"
                                        name="delete_id"
                                        value="<?= (int) $item['id'] ?>"
                                    >
                                    <button type="submit" class="delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
