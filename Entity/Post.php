<?php

namespace Prh\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post entity.
 *
 * @ORM\Entity(repositoryClass="Prh\BlogBundle\Entity\PostRepository")
 * @ORM\Table
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    use TimestampableTrait;

    const STATE_DRAFT = 0;
    const STATE_PUBLISHED = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured", type="smallint")
     */
    private $featured;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var ArrayCollection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="post__category",
     *          joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="post_id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="category_id")})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $categories;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $this->getContent());

        $paragraphs = $doc->getElementsByTagName('p');
        if (!is_null($paragraphs->item(0))) {
            $p = htmlentities($paragraphs->item(0)->nodeValue);

            if (empty($p)) {
                $p = htmlentities($paragraphs->item(1)->nodeValue);
            }
        }

        if (substr($p, -1) != '.') {
            $p .= '.';
        }

        return '<p>' . $p . '</p>';
    }

    /**
     * @return string|null
     */
    public function getFeaturedImageUrl()
    {
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $this->getContent());

        $images = $doc->getElementsByTagName('img');

        if (!is_null($images->item(0))) {
            foreach ($images->item(0)->attributes as $attribute) {
                if ($attribute->nodeName == 'src') {
                    return $attribute->nodeValue;
                }
            }
        }
    }

    /**
     * Get excerpt without html tags.
     *
     * @return string
     */
    public function getPlainExcerpt()
    {
        return strip_tags(html_entity_decode($this->getExcerpt()));
    }

    /**
     * @param integer $state
     * @return Post
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $url
     * @return Post
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return int
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @param int $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $category->addPost($this); // synchronously updating inverse side
        $this->categories[] = $category;
    }

    /**
     * @param Category $category
     */
    public function addCategoryCollection(Category $category)
    {
        $category->addPost($this); // synchronously updating inverse side
        $this->categories[] = $category;
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Check if the post is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        if ($this->getState() === self::STATE_PUBLISHED) {
            return true;
        }

        return false;
    }
}
