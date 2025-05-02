function generateInvoiceNumber() {
    global $conn;
    $prefix = date('Ymd');
    
    // Lấy số hóa đơn cao nhất trong ngày
    $sql = "SELECT MAX(CAST(SUBSTRING(invoice_number, 9) AS UNSIGNED)) as max_num 
            FROM invoices 
            WHERE invoice_number LIKE '$prefix%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    $next_num = ($row['max_num'] ?? 0) + 1;
    return $prefix . sprintf('%04d', $next_num);
}