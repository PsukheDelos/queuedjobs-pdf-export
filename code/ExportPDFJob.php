<?php
/**
 * This queued job is to export data into a CSV for downloading.
 *
 */
class ExportPDFJob extends AbstractQueuedJob implements QueuedJob {

    //Export PDF Variables
    protected $pages;

    /**
     * Constructor
     */
    public function __construct() {
        //$this->pages =  Page::get();
        $this->pages = Versioned::get_by_stage('Page', 'Live');
    }

    /**
     * @return string
     */
    public function getTitle() {
        return 'Export all pages to PDF';
    }

    /**
     * Process export to PDF
     */
    public function process()
    {
        $this->pages->each(function ($page) {
            Director::test($page->AbsoluteLink() . 'downloadpdf');
        });

        $this->isComplete = true;
        return;
    }

}