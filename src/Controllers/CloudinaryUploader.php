<?php

class CloudinaryUploader
{
    private $cloudName = 'your_cloud_name';  // Thay 'your_cloud_name' bằng tên cloud của bạn
    private $apiKey = 'your_api_key';        // Thay 'your_api_key' bằng API key của bạn
    private $apiSecret = 'your_api_secret';  // Thay 'your_api_secret' bằng API secret của bạn

    /**
     * Upload file to Cloudinary
     *
     * @param string $fileTmpPath Temporary file path from the form
     * @return bool|string Returns URL if successful, or false if failed
     */
    public function upload($fileTmpPath)
    {
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

        
        $timestamp = time();
        $signature = sha1("timestamp={$timestamp}{$this->apiSecret}");

        // File data for upload
        $fileData = curl_file_create($fileTmpPath, mime_content_type($fileTmpPath), basename($fileTmpPath));

        // Data to send to Cloudinary
        $data = [
            'file' => $fileData,
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // Process the response
        if ($response) {
            $responseData = json_decode($response, true);
            if (isset($responseData['secure_url'])) {
                // Return URL if upload successful
                return $responseData['secure_url'];
            }
        }
        
        // Return false if upload failed
        return false;
    }
}
