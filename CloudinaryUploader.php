
<?php
require_once 'path/to/cloudinary_php/src/Cloudinary.php';
require_once 'path/to/cloudinary_php/src/Api.php';
require_once 'path/to/cloudinary_php/src/Uploader.php';
require_once 'path/to/cloudinary_php/src/Api/ApiResponse.php';





?>





<!-- 
class CloudinaryUploader
{
    private $cloudName = 'dt5xizv10';
    private $apiKey = '113462295938149';
    private $apiSecret = 'QiX64lhj43Ll-6Uo6DLZRpHEC70';

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
        $signatureString = "timestamp={$timestamp}{$this->apiSecret}";
        $signature = sha1($signatureString);

        // Prepare file for upload
        $fileData = curl_file_create($fileTmpPath, mime_content_type($fileTmpPath), basename($fileTmpPath));

        // Data for the request
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

        // Handle the response
        if ($response) {
            $responseData = json_decode($response, true);
            if (isset($responseData['secure_url'])) {
                return $responseData['secure_url'];
            }
        }
        
        return false;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $fileTmpPath = $_FILES['image']['tmp_name'];

    $uploader = new CloudinaryUploader();
    $uploadedUrl = $uploader->upload($fileTmpPath);

    if ($uploadedUrl) {
        echo "Upload successful! Image URL: <a href='$uploadedUrl'>$uploadedUrl</a>";
    } else {
        echo "Upload failed.";
    }
} -->