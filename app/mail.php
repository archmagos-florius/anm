<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

function send_app_email(string $to, string $subject, string $html, string $text = ''): bool
{
    $placeholder = str_starts_with((string) config('SMTP_HOST', ''), 'TODO_') || str_starts_with((string) config('SMTP_FROM_EMAIL', ''), 'TODO_');
    if ($placeholder || !class_exists(PHPMailer::class)) {
        log_email($to, $subject, $html);
        return false;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = (string) config('SMTP_HOST');
        $mail->Port = (int) config('SMTP_PORT', 587);
        $mail->SMTPAuth = true;
        $mail->Username = (string) config('SMTP_USERNAME');
        $mail->Password = (string) config('SMTP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom((string) config('SMTP_FROM_EMAIL'), (string) config('SMTP_FROM_NAME'));
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $text !== '' ? $text : strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html));
        return $mail->send();
    } catch (MailException $exception) {
        error_log('Email failed: ' . $exception->getMessage());
        log_email($to, $subject, $html);
        return false;
    }
}

function log_email(string $to, string $subject, string $html): void
{
    $line = sprintf("[%s] To: %s | Subject: %s\n%s\n\n", now_text(), $to, $subject, strip_tags($html));
    file_put_contents(dirname(__DIR__) . '/storage/mail.log', $line, FILE_APPEND);
}

function order_email_html(array $order, array $items): string
{
    $lines = [];
    $lines[] = '<h1>Order #' . e($order['id']) . '</h1>';
    if (!empty($order['created_at'])) {
        $lines[] = '<p><strong>Placed:</strong> ' . e(format_datetime($order['created_at'])) . '</p>';
    }
    $lines[] = '<p><strong>Status:</strong> ' . e(status_label($order['status'])) . '</p>';
    $lines[] = '<p><strong>Name:</strong> ' . e($order['customer_name']) . '<br><strong>Email:</strong> ' . e($order['customer_email']) . '<br><strong>Phone:</strong> ' . e($order['customer_phone']) . '</p>';
    $lines[] = '<p><strong>Fulfillment:</strong> ' . e(status_label($order['fulfillment_type'])) . '</p>';
    if ($order['fulfillment_type'] === 'delivery') {
        $lines[] = '<p><strong>Delivery address:</strong><br>' . nl2br(e($order['delivery_address'])) . '</p>';
    }
    if (!empty($order['customer_notes'])) {
        $lines[] = '<p><strong>Notes:</strong><br>' . nl2br(e($order['customer_notes'])) . '</p>';
    }
    $lines[] = '<ul>';
    foreach ($items as $item) {
        $lines[] = '<li>' . e($item['quantity']) . ' x ' . e($item['item_name_snapshot']) . ' at ' . e(money((int) $item['unit_price_cents'])) . ' = ' . e(money((int) $item['line_total_cents'])) . '</li>';
    }
    $lines[] = '</ul>';
    $lines[] = '<p><strong>Subtotal:</strong> ' . e(money((int) $order['subtotal_cents'])) . '<br><strong>Delivery fee:</strong> ' . e(money((int) $order['delivery_fee_cents'])) . '<br><strong>Total:</strong> ' . e(money((int) $order['total_cents'])) . '</p>';
    return implode("\n", $lines);
}

function send_order_confirmation(array $order): void
{
    $items = db_fetch_all('SELECT * FROM order_items WHERE order_id = ? ORDER BY id', [(int) $order['id']]);
    send_app_email((string) $order['customer_email'], 'Order confirmation #' . $order['id'], order_email_html($order, $items));
}

function send_admin_new_order(array $order): void
{
    $adminEmail = (string) config('ADMIN_EMAIL');
    if ($adminEmail === '' || str_starts_with($adminEmail, 'TODO_')) {
        return;
    }

    $html = '<p>New order #' . e($order['id']) . ' from ' . e($order['customer_name']) . ' for ' . e(money((int) $order['total_cents'])) . '.</p>';
    $html .= '<p><a href="' . e(app_url('/admin/order-detail.php?id=' . $order['id'])) . '">View order</a></p>';
    send_app_email($adminEmail, 'New order #' . $order['id'], $html);
}
