<?php

include_once "CloudinaryUploader.php";

class AdminController{
    private Config $conn;

    // hàm tạo
    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $this->conn = new Config();
    }

    private function uploadImage($fileTmpPath) {
        $uploader = new CloudinaryUploader();
        return $uploader->upload($fileTmpPath);
    }

    private function savePost($provinceID, $postCreateDate, $imageUrl) {
        $sql = "INSERT INTO posts (provinceID, postCreateDate, imageUrl) 
                VALUES ('$provinceID', '$postCreateDate', '$imageUrl')";
        $this->conn->query($sql);
        return $this->conn->insert_id;
    }

    private function addPostDetail($postId, $sectionTitle, $sectionContent, $category, $imageDetailUrl) {
        $sql = "INSERT INTO post_details (postID, sectionTitle, sectionContent, category, imgURL) 
                VALUES ($postId, '$sectionTitle', '$sectionContent', '$category', '$imageDetailUrl')";
        $this->conn->query($sql);
    }

    // các hàm post của admin
    public function addPost() {
        // lấy form
        $provinceID = $_POST['provinceID'];
        $postCreateDate = date('Y-m-d H:i:s');
        $fileTmpPath = $_FILES['postIMG']['tmp_name'];

        // ảnh
        $imageUrl = $this->uploadImage($fileTmpPath);

        if ($imageUrl !== false) {
            
            $postId = $this->savePost($provinceID, $postCreateDate, $imageUrl);
            echo "Bài viết đã được thêm thành công!";

            // lưu postDetail
            if (isset($_POST['postDetails']) && is_array($_POST['postDetails'])) {
                foreach ($_POST['postDetails'] as $postDetail) {
                    $postSectionTitle = $postDetail['sectionTitle'];
                    $postSectionContent = $postDetail['sectionContent'];
                    $postCategory = $postDetail['category'];
                    $fileTmpPathDetail = $_FILES['postDetailIMG']['tmp_name'];

                    $imageDetailUrl = $this->uploadImage($fileTmpPathDetail);

                    $this->addPostDetail($postId, $postSectionTitle, $postSectionContent, $postCategory, $imageDetailUrl);
                }
            }
        } else {
            echo "Lỗi khi upload ảnh.";
        }
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

    public function deletePost($postId) {
        // Xóa các chi tiết của bài viết trước
        $sqlDetailDelete = "DELETE FROM post_details WHERE postID = $postId";
        $this->conn->query($sqlDetailDelete);
    
        // Xóa bài viết
        $sqlPostDelete = "DELETE FROM posts WHERE postID = $postId";
        $this->conn->query($sqlPostDelete);
    
        if ($this->conn->affected_rows > 0) {
            echo "Bài viết đã được xóa thành công!";
        } else {
            echo "Không tìm thấy bài viết để xóa!";
        }
    }

    //các hàm province của admin
    public function adddProvince(){

        $ProvinceName = $_POST['provinceName'];
        $provinceRegion = $_POST['provinceRegion'];

        $sql =  "INSERT INTO provinces (provinceName, provinceRegion) 
                VALUES ('$provinceName', '$provinceRegion')";

        $this->conn->query($sql);
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

    public function updateProvince($provinceID) {
        $provinceName = $_POST['provinceName'];
        $provinceRegion = $_POST['provinceRegion'];
    
        $sql = "UPDATE provinces 
                SET provinceName = '$provinceName', provinceRegion = '$provinceRegion' 
                WHERE provinceID = $provinceID";
    
        $this->conn->query($sql);
        echo "Cập nhật tỉnh thành công!";
    }
    
}
?>