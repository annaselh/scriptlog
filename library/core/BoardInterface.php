<?php
/**
 * BoardInterface Interface
 * 
 * @category  Interface for dashboard based on user previlege
 * @package   SCRIPTLOG
 * @author    M.Noermoehammad
 * @license   MIT
 * 
 */
interface BoardInterface
{

/**
 * set View
 * 
 * @param string $view
 */
 public function setView($view);

/**
 * set page title
 * 
 * @param string $pageTitle
 * 
 */
 public function setPageTitle($pageTitle);

/**
 * get page title
 * 
 */
 public function getPageTitle();

}