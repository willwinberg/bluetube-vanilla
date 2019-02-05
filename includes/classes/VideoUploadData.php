<?php
class VideoUploadData {

    private $videoDataArray, $title, $description, $privacy, $category, $uploadedBy;

    public function __construct($videoDataArray, $title, $description, $privacy, $category, $uploadedBy) {
        $this->videoDataArray = $videoDataArray;
        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploadedBy = $uploadedBy;
    }

    public function getTitle() {
       return $this->title;
    }
    
    public function getDescription() {
       return $this->description;
    }

    public function getPrivacy() {
       return $this->privacy;
    }

    public function getCategory() {
       return $this->category;
    }
    
    public function getUploadedBy() {
       return $this->uploadedBy;
    }
}
?>