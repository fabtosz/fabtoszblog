<?php

namespace FabtoszBlog\Utils;

class Paginator {

	private $page;
	private $recordsPerPage;
	private $pagesCount;
	private $records;
	
	public function __construct($page, $recordsPerPage) {
		$this->page = $page;
		$this->recordsPerPage = $recordsPerPage
		$this->pagesCount = ceil($recordsCount/$records_per_page);
	}
}