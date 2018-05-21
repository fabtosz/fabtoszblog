<?php

namespace FabtoszBlog\Repository;

use FabtoszBlog\Utils\DependencyInjector;
//use PDO;

abstract class AbstractModel {
    protected $db;

    public function __construct(DependencyInjector $di) {
        $this->db = $di->get('PDO');
    }
}