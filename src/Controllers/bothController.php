<?php
class bothController{

    private Config $conn;

    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $this->conn = new Config();
    }

    public function getPostbyProvinceID($provinceId) {
        $data = [];
    
        // tìm post theo province
        $sql = "SELECT * FROM post WHERE provinceID = $provinceId LIMIT 1"; // Giới hạn 1 bài viết
        $postResult = $this->conn->query($sql);
        
        if (!$postResult || $postResult->num_rows === 0) {
            return null; 
        }
    
        $post = $postResult->fetch_assoc(); // Lấy bài viết đầu tiên
    
        // tìm postdetail bằng postID
        $sqlDetail = "SELECT * FROM postDetail WHERE postID = " . $post['postID'];
        $detailsResult = $this->conn->query($sqlDetail);
        $details = [];
    
        // Lưu trữ thông tin chi tiết của post vào mảng
        while ($detail = $detailsResult->fetch_assoc()) {
            $details[] = $detail;
        }
    
        // tìm destination theo province
        $sqlDestination = "SELECT * FROM destination WHERE provinceID = " . $post['provinceID'];
        $destinationResult = $this->conn->query($sqlDestination);
        $destinations = [];
    
        // Lưu trữ thông tin destination vào mảng
        while ($destination = $destinationResult->fetch_assoc()) {
            $destinations[] = $destination;
        }
    
        // Thêm post, details và destinations vào mảng data
        $data = [
            'post' => $post,
            'details' => $details,
            'destinations' => $destinations,
        ];
    
        return $data;
    }

    public function getProvincesByRegion($provinceRegion) {
        $data = [];
    
        $sql = "SELECT * FROM provinces WHERE provinceRegion = '$provinceRegion'";
        $result = $this->conn->query($sql);
    
        if ($result) {
            while ($province = $result->fetch_assoc()) {
                $data[] = $province;
            }
        }
        
        return $data;
    }

    public function getAllProvinces() {
        $data = [];
    
        $sql = "SELECT * FROM provinces";
        $result = $this->conn->query($sql);
    
        if ($result) {
            while ($province = $result->fetch_assoc()) {
                $data[] = $province;
            }
        }
    
        return $data;
    }

    public function getAllDestinationByProvinceID($provinceID) {
        $data = [];
    
        $sql = "SELECT * FROM destination WHERE provinceID = $provinceID";
        $result = $this->conn->query($sql);
    
        if ($result) {
            while ($destination = $result->fetch_assoc()) {
                $data[] = $destination;
            }
        }
    
        return $data;
    }
}
?>