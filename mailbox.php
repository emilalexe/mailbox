<?php
header('Content-Type: application/json');
include_once("send_mail.php");

// --- 1. CONFIGURARE BAZĂ DE DATE ---
$db_host = "localhost";
$db_user = "wvgrhpew_api";
$db_pass = "A)]X_f0Q7^6Q";
$db_name = "wvgrhpew_api";



// --- 2. CONFIGURARE SECURITATE ---
$expected_api_key = "(p1I-&jpq_M1x55[]CTfHKE0";	// Trebuie să fie identică cu cea din Pico
$log_file         = "access_log.txt";      		// Fișierul unde se salvează istoricul


// Conectare la DB
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// --- 3. COLECTARE DATE CERERE ---
$headers    = apache_request_headers();
$client_key = $headers['Authorization'] ?? '';
$client_mac = strtoupper($headers['X-Device-MAC'] ?? '');
$client_ip  = $_SERVER['REMOTE_ADDR'];
$timestamp  = date("Y-m-d H:i:s");

// Funcție pentru logare (ajută la depanare)
function write_to_log($conn, $msg) {
    // 1. Verificăm dacă datele necesare există în array
    $type = $msg["status"] ?? 'unknown';
    $device_id = $msg["device_id"] ?? null;
    $data = $msg["message"] ?? 'No message provided';
    $ip = $msg["ip"] ?? null;
    
    // 2. Pregătim statement-ul
    $log = $conn->prepare("INSERT INTO event_log (type, device_id, ip, data) VALUES (?, ?, ?, ?)");
    
    if ($log) {
        // 3. Legăm parametrii și executăm
        $log->bind_param("ssss", $type, $device_id, $ip, $data);
        
        if (!$log->execute()) {
            // Opțional: scriem într-un fișier local dacă baza de date eșuează
            
            $log_file = "access.log";
            
            $client_mac = $headers['X-Device-MAC'] ?? 'N/A';
            $client_ip = $_SERVER['REMOTE_ADDR'];
            $timestamp = date("Y-m-d H:i:s");
            $message = "SQL Error in write_to_log: " . $log->error;
            
            $entry = "[$timestamp] IP: $client_ip | MAC: $client_mac | $message\n";
            file_put_contents($log_file, $entry, FILE_APPEND);
            
            error_log($message);
        }
        
        // 4. Închidem statement-ul
        $log->close();
    } else {
        error_log("Failed to prepare log statement: " . $conn->error);
    }
}

// Validare Cheie API (primul filtru de securitate)
if ($client_key !== $expected_api_key) {
	write_to_log($conn,["status" => "error", "message" => "API Key invalida : " . $client_key, "ip" => $client_ip]);
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "API Key invalida"]);
    exit;
}

// --- 4. INTEROGARE BAZĂ DE DATE ---
// Folosim Prepared Statements pentru a preveni SQL Injection
$stmt = $conn->prepare("SELECT id, email_destinatie, nume_dispozitiv FROM devices WHERE mac_address = ? AND activ = 1");
$stmt->bind_param("s", $client_mac);
$stmt->execute();
$result = $stmt->get_result();

// Validare MAC dispozitiv (al doilea filtru de securitate)
if ($row = $result->fetch_assoc()) {
    $device_id = $row['id'];
    $email_to = $row['email_destinatie'];
    $nume_dispozitiv = $row['nume_dispozitiv'];
    $email_from = $row['nume_dispozitiv'];
    
// --- 5. TRIMITERE E-MAIL ---
    $subject = "ALERTA: $nume_dispozitiv";
    $timestamp = date("Y-m-d H:i:s");
    $message = "Alerta declansata de: $nume_dispozitiv\nMAC: $client_mac\nOra: $timestamp";

    if (smtp_mail($email_to, $subject, $message, $email_from)) {
		write_to_log($conn,["status" => "success", "message" => "Alerta trimisa catre $email_to", "ip" => $client_ip, "device_id" => $device_id]);
        echo json_encode(["status" => "success", "message" => "Alerta trimisa catre $email_to"]);
    } else {
		write_to_log($conn, ["status" => "error", "message" => "Eroare trimitere email", "ip" => $client_ip, "device_id" => $device_id]);
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Eroare trimitere email"]);
    }
} else {
    // MAC-ul nu există în baza de date sau este dezactivat
	write_to_log($conn, ["status" => "error", "message" => "Dispozitiv neautorizat", "ip" => $client_ip]);
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Dispozitiv neautorizat"]);
}

$stmt->close();
$conn->close();
?>