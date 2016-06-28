<?php
namespace Admin\Entity;

class Category
{
    /**
     * @var string
     \*/
    public $content;

    /**
     * @param string $name
     * @return Category
     \*/
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     \*/
    public function getContent()
    {
        return $this->content;
    }
}
?>
