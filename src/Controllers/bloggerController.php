<?php

include_once "CloudinaryUploader.php";
include_once __DIR__ . "/../config/config.php";

class bloggerController{
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

    public function saveBlog($provinceID, $userID, $blogContent, $blogCreateDate){
        $sql = "INSERT INTO blogs(provinceID, userID, blogContent, blogCreateDate) 
                VALUES ($provinceID, '$userID', '$blogContent', '$blogCreateDate')";
        $insert_query = mysqli_query($this->conn->connect(),$sql);
        return $insert_query['blogID'];
    }

    private function saveBlogImage($blogId, $imgBlogURL) {
        $sql = "INSERT INTO blog_images (blogID, imgBlogURL) VALUES ($blogId, '$imgBlogURL')";
        $insert_query = mysqli_query($this->conn->connect(),$sql);
    }

    //các hàm của blogger
    public function addblog(){

        $provinceID = $_POST['provinceID'];
        $userID = $_SESSION['blogger_id'];
        $blogContent = $_POST['blogContent'];
        $blogCreateDate = date('Y-m-d H:i:s');

        $blogid = saveBlog($provinceID, $userID, $blogContent, $blogCreateDate);
        echo "Blog thêm thành công";

        // Xử lý hình ảnh
        $files = $_FILES['blogImages'];

        // Lặp qua từng tệp hình ảnh
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileTmpPath = $files['tmp_name'][$i];

            // Tải lên hình ảnh và nhận URL
            $imageUrl = $this->uploadImage($fileTmpPath);

            if ($imageUrl !== false) {
                $this->saveBlogImage($blogId, $imageUrl);
            } else {
                echo "Lỗi khi tải lên hình ảnh: " . $files['name'][$i];
            }
        }
    }

    public function deleteBlog($blogId){

        $userID = $_SESSION['blogger_id'];
        $sql = "DELETE FROM blogID = $blogid and userID = $userID";

        $delete_query = mysqli_query($this->conn->connect(),$sql);

        if ($this->conn->getAffectedRows() > 0) {
            echo "blog đã được xóa thành công!";
        } else {
            echo "Không tìm thấy blog để xóa!";
        }
    }

    public function updateBlog($blogId) {
        $provinceID = $_POST['provinceID'];
        $userID = $_SESSION['blogger_id'];
        $blogContent = $_POST['blogContent'];
    
        // Tạo câu lệnh SQL để cập nhật blog
        $sql = "UPDATE blogs
                SET provinceID = $provinceID, 
                    blogContent = '$blogContent', 
                WHERE blogID = $blogId and userID = $userID";

        $update_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function addComment($blogID)
    {
        $cmtContent = $_POST['cmtContent'];
        $userID = $_SESSION['blogger_id'];
        $createDate = date('Y-m-d H:i:s');

        $sql = "INSERT INTO userComment (blogID, userID, cmtContent, createDate) 
                VALUES ($blogID, $userID, '$cmtContent', '$createDate')";

        $insert_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function updateComment($commentID)
    {
        $cmtContent = $_POST['cmtContent'];
        $userID = $_SESSION['blogger_id'];
        $sql = "UPDATE userComment 
                SET cmtContent = '$cmtContent' 
                WHERE commentID = $commentID AND userID = $userID";

        $update_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function deleteComment($commentID)
    {
        $userID = $_SESSION['blogger_id'];
        $sql = "DELETE FROM userComment 
                WHERE commentID = $commentID AND userID = $userID";

        $delete_query = mysqli_query($this->conn->connect(),$sql);

        if ($this->conn->getAffectedRows() > 0) {
            echo "Comment đã được xóa thành công!";
        } else {
            echo "Không tìm thấy Comment để xóa!";
        }
    }

    public function addRepComment($commentID)
    {
        $userID = $_SESSION['blogger_id'];
        $repContent = $_POST['repContent'];
        $createDateRep = date('Y-m-d H:i:s');

        $sql = "INSERT INTO repComment (userID,commentID, repContent, createDateRep) 
                VALUES ($userID, $commentID, '$repContent', '$createDateRep')";

        $insert_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function updateRepComment($repCommentID)
    {
        $userID = $_SESSION['blogger_id'];
        $repContent = $_POST['repContent'];
        $createDateRep = date('Y-m-d H:i:s');

        $sql = "UPDATE repComment 
                SET repContent = '$repContent'
                WHERE repCommentID = $repCommentID AND userID = $userID";
        $update_query = mysqli_query($this->conn->connect(),$sql);
    }

    public function deleteRepComment($repCommentID)
    {
        $userID = $_SESSION['blogger_id'];
        $sql = "DELETE FROM repComment 
                WHERE repCommentID = $repCommentID AND userID = $userID";

        $delete_query = mysqli_query($this->conn->connect(),$sql);

        if ($this->conn->getAffectedRows() > 0) {
            echo "RepComment đã được xóa thành công!";
        } else {
            echo "Không tìm thấy RepComment để xóa!";
        }
    }

}
?>