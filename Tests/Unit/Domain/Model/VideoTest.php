<?php
/**
 * VideoTest
 */

namespace HVP\Html5videoplayer\Tests\Unit\Domain\Model;

use HVP\Html5videoplayer\Domain\Model\Video;

/**
 * VideoTest
 */
class VideoTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var Video
     */
    protected $fileDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fileDomainModelInstance = new Video();
    }

    /**
     * @test
     */
    public function titleCanBeSet()
    {
        $title = 'This is the title';
        $this->fileDomainModelInstance->setTitle($title);
        $this->assertEquals($title, $this->fileDomainModelInstance->getTitle());
    }
}
