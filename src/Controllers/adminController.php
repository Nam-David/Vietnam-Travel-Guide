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
        $sql = "INSERT INTO postdetails (postID, sectionTitle, sectionContent, category, imgURL) 
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
    public function addProvince(){

        $ProvinceName = $_POST['provinceName'];
        $provinceRegion = $_POST['provinceRegion'];

        $sql =  "INSERT INTO provinces (provinceName, provinceRegion) 
                VALUES ('$provinceName', '$provinceRegion')";

        $this->conn->query($sql);
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

    public function getAllUsers(){
        $data = [];

        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        if($result){
            while($user = $result->fetch_assoc()){
                $data[] = $user;
            }
        }

        return $data;
    }

    public function getAllBlogs() {
        $data = [];
    
        $sql = "SELECT * FROM blogs";
        $result = $this->conn->query($sql);
    
        if ($result) {
            while ($blog = $result->fetch_assoc()) {
                $data[] = $blog;
            }
        }
    
        return $data;
    }

    public function getAllPost() {
        $data = [];
    
        $sql = "SELECT * FROM posts";
        $result = $this->conn->query($sql);
    
        if ($result) {
            while ($post = $result->fetch_assoc()) {
                $data[] = $post;
            }
        }
    
        return $data;
    }
}
?>