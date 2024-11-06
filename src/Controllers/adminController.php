<?php

include_once "CloudinaryUploader.php";
include_once __DIR__ . "/../config/config.php";
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
        $insert_query = mysqli_query($this->conn->connect(),$sql);
        return $insert_query['userID'];
    }

    private function addPostDetail($postId, $sectionTitle, $sectionContent, $category, $imageDetailUrl) {
        $sql = "INSERT INTO postdetails (postID, sectionTitle, sectionContent, category, imgURL) 
                VALUES ($postId, '$sectionTitle', '$sectionContent', '$category', '$imageDetailUrl')";
        $insert_query = mysqli_query($this->conn->connect(),$sql);
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
        $delete_query = mysqli_query($this->conn->connect(),$sqlDetailDelete);
    
        // Xóa bài viết
        $sqlPostDelete = "DELETE FROM posts WHERE postID = $postId";
        $delete_post_query = mysqli_query($this->conn->connect(),$sqlPostDelete);
    
        if ($this->conn->getAffectedRows() > 0) {
            echo "Bài viết đã được xóa thành công!";
        } else {
            echo "Không tìm thấy bài viết để xóa!";
        }
    }

    //các hàm province của admin
    public function addProvince(){

        $provinceName = $_POST['provinceName'];
        $provinceRegion = $_POST['provinceRegion'];

        $sql =  "INSERT INTO provinces (provinceName, provinceRegion) 
                VALUES ('$provinceName', '$provinceRegion')";

        $insert_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function updateProvince($provinceID) {
        $provinceName = $_POST['provinceName'];
        $provinceRegion = $_POST['provinceRegion'];
    
        $sql = "UPDATE provinces 
                SET provinceName = '$provinceName', provinceRegion = '$provinceRegion' 
                WHERE provinceID = $provinceID";
    
        $update_query = mysqli_query($this->conn->connect(),$sql);
        echo "Cập nhật tỉnh thành công!";
    }

    public function getAllUsers(){

        $sql = "SELECT * FROM users";
        $get_query = mysqli_query($this->conn->connect(),$sql);

        return $get_query;
    }

    public function getAllBlogs() {
        
        $sql = "SELECT * FROM blogs";
        $get_query = mysqli_query($this->conn->connect(),$sql);

        return $get_query;
    }

    public function getAllPost() {

        $sql = "SELECT * FROM posts";
        $get_query = mysqli_query($this->conn->connect(),$sql);
    
        return $get_query;
    }
}
?>