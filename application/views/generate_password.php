<?php
// Fungsi enkripsi sesuai dengan kode Anda
function encrypt_password($plain_text) {
    $key = 'c67b9e0f2a4a4b7eac5f8e7d12345678'; // Encryption key dari config Anda
    
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($plain_text, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . '::' . $encrypted);
}

// Fungsi dekripsi sesuai dengan kode Anda  
function decrypt_password($encrypted_text) {
    $key = 'c67b9e0f2a4a4b7eac5f8e7d12345678'; // Encryption key yang sama
    
    list($iv, $encrypted) = explode('::', base64_decode($encrypted_text), 2);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}

// Generator password untuk insert ke database
function generate_password_for_database($plain_password) {
    $encrypted = encrypt_password($plain_password);
    
    echo "Password asli: " . $plain_password . "\n";
    echo "Password terenkripsi: " . $encrypted . "\n";
    echo "Dekripsi test: " . decrypt_password($encrypted) . "\n\n";
    
    echo "SQL INSERT:\n";
    echo "INSERT INTO users (username, password_hash) VALUES ('username_anda', '" . $encrypted . "');\n\n";
    
    return $encrypted;
}

// Contoh penggunaan
echo "=== GENERATOR PASSWORD UNTUK DATABASE ===\n\n";

// Generate beberapa password
$passwords = ['admin123', 'user456', 'password789'];

foreach ($passwords as $password) {
    generate_password_for_database($password);
    echo str_repeat("-", 50) . "\n";
}

// Jika ingin generate password random
function generate_random_password($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

echo "\n=== PASSWORD RANDOM ===\n";
for ($i = 1; $i <= 3; $i++) {
    $random_pass = generate_random_password();
    echo "Password random #$i:\n";
    generate_password_for_database($random_pass);
    echo str_repeat("-", 50) . "\n";
}
?>