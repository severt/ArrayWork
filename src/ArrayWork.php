<?php

/*
 * This file is the array-work package.
 *
 * (c) Simon Micheneau <contact@simon-micheneau.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fukotaku\ArrayWork;

/**
 * Class ArrayWork
 * Sort data for pagination, filtre sort and more.
 */
class ArrayWork {

  /**
   * @var int Number Data by page limit
   */
  private $byPage;

  /**
   * @var int Current number page
   */
  private $page;

  /**
   * @var string Url pagination
   */
  private $url = "#";

  /**
   * @var array Text link for previous and next page
   */
  private $pages = array(
    "ru" => array("Пред.", "След.")
  );

  /**
   * @var array Data
   */
  private $data;

  /**
   * @param array $data Data to sort (optionnal)
   * @param int $byPage Number limit for pagination (optionnal)
   * @param int $page Current number page for pagination (optionnal)
   */
  public function __construct($data = array(), $byPage = 20, $page = 1) {
    if (is_array($data)) {
      $this->data = $data;
    } else {
      $this->data = array();
    }

    if ($byPage <= 0) {
      $this->byPage = null;
    } else {
      $this->byPage = $byPage;
    }

    if (is_int(intval($page)) && intval($page) > 0) {
      $this->page = $page;
    } else {
      $this->page = 1;
    }
  }

  /**
   * @param string $on Column to order
   * @param string $order Meaning order by 'ASC' or 'DESC' (optionnal)
   * @return bool Return true if data sorted | Return false if data array empty or not a array
   */
  public function dataSort($on, $order = 'ASC') {
    if (!empty($this->data)) {
      $new_array = array();
      $sortable_array = array();

      if (count($this->data) > 0) {
        foreach ($this->data as $k => $v) {
          if (is_array($v)) {
            foreach ($v as $k2 => $v2) {
              if ($k2 == $on) {
                $sortable_array[$k] = $v2;
              }
            }
          } else {
            $sortable_array[$k] = $v;
          }
        }

        switch ($order) {
            case 'ASC':
                asort($sortable_array);
            break;
            case 'DESC':
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
          $new_array[$k] = $this->data[$k];
        }
      }
      $this->data = array_merge(array(), $new_array);
      return true;

    } else {
      return false;
    }
  }

  /**
   * @param array $column
   * @return bool Return true if data filtered | Return false if not filtered
   */
  public function dataFilter($column = array()) {
    if (!empty($this->data) && !empty($column) && array_key_exists("ActionFilter", $column)) {
      if (count($this->data) > 0) {

        foreach ($this->data as $k => $v) {
          if (is_array($v)) {

            foreach ($v as $k2 => $v2) {

              if (in_array($k2, $column)) {
                if ($column["ActionFilter"] == "skip") {
                  unset($this->data[$k][$k2]);
                }
              } else {
                if ($column["ActionFilter"] == "keep") {
                  unset($this->data[$k][$k2]);
                }
              }

            }

          }
        }

      }
      $this->data = array_merge(array(), $this->data);
      return true;

    } else {
      return false;
    }
  }

  /**
   * @param array $cssClass Array css class for table balise (optionnal)
   * @return string Return table html code with css class or not and data by page limit
   */
  public function generateTable($cssClass = array(), $pagination = array("lang" => "en"), $idClass = array()) {
    if (!empty($cssClass)) {
      $class_table = implode(" ", $cssClass);
    } else {
      $class_table = "";
    }

    if (!empty($idClass)) {
        $id_table = implode(" ", $idClass);
    } else {
        $id_table = "";
    }

    if (isset($pagination["url"])) {
      $this->url = $pagination["url"];
    }

    $column = array();
    $line = array();

    foreach ($this->data as $array) {
      $column = array_keys($array);
      break;
    }

    if ($this->byPage == null) {
      $count = count($this->data);
      $page = 0;
    } else {
      $count = $this->byPage;
      $page = $this->page-1;
    }

    $nb = 0;
    for ($i = (0+($page*$count)); $i < ($count+($page*$count)); $i++) {
      if (isset($this->data[$i])) {
        foreach ($this->data[$i] as $k => $v) {
          $line[$nb][] = $v;
        }
        $nb++;
      }
    }
    $html = "";

    if (isset($pagination["position"]) && ($pagination["position"] == "top" || $pagination["position"] == "full")) {
      if (isset($pagination["cssClass"]) && is_array($pagination["cssClass"])) {
        $class = implode(" ", $pagination["cssClass"]);
      } else {
        $class = "";
      }
      if ($this->byPage != null && $this->byPage < count($this->data)) {
        $html .= "<ul class=\"".$class."\">";

        if ($this->url != "#" && preg_match("#{}#", $this->url) == 1) {
          $urlPrevious = str_replace("{}", $this->page-1, $this->url);
          $urlNext = str_replace("{}", $this->page+1, $this->url);
        } else {
          $urlPrevious = "#";
          $urlNext = "#";
        }

        if ($this->byPage > 1) {
          if (isset($pagination["lang"]) && isset($this->pages[$pagination["lang"]])) {
            $previous = $this->pages[$pagination["lang"]][0];
            $next = $this->pages[$pagination["lang"]][1];
          } else {
            $previous = $this->pages["en"][0];
            $next = $this->pages["en"][1];
          }
          if ($this->page > 1) {
            $html .= "<li><a href=\"".$urlPrevious."\">".$previous."</a></li>";
          }
        }
        if ($this->page < (count($this->data)/$this->byPage)) {
          $html .= "<li><a href=\"".$urlNext."\">".$next."</a></li>";
        }
        $html .= "</ul>";
      }
    }

    $html .= "<table class=\"".$class_table."\" id=\"".$id_table."\" width=100% >
              <thead>";

    $html .= "<tr>";
    for ($i = 0; $i < count($column); $i++) {
      $html .= "<th>".$column[$i]."</th>";
    }
    $html .= "</tr>
            </thead>";

    $html .= "<tbody>";

    foreach ($line as $array) {
      $html .= "<tr>";
      foreach ($array as $k => $v) {
        $html .= "<td>".$v."</td>";
      }
      $html .= "</tr>";
    }

    $html .= "</tbody>
            </table>";

    if (isset($pagination["position"]) && ($pagination["position"] == "bottom" || $pagination["position"] == "full")) {
      if (isset($pagination["cssClass"]) && is_array($pagination["cssClass"])) {
        $class = implode(" ", $pagination["cssClass"]);
      } else {
        $class = "";
      }
      if ($this->byPage != null && $this->byPage < count($this->data)) {
        $html .= "<ul class=\"".$class."\">";

        if ($this->url != "#" && preg_match("#{}#", $this->url) == 1) {
          $urlPrevious = str_replace("{}", $this->page-1, $this->url);
          $urlNext = str_replace("{}", $this->page+1, $this->url);
        } else {
          $urlPrevious = "#";
          $urlNext = "#";
        }

        if ($this->byPage > 1) {
          if (isset($pagination["lang"]) && isset($this->pages[$pagination["lang"]])) {
            $previous = $this->pages[$pagination["lang"]][0];
            $next = $this->pages[$pagination["lang"]][1];
          } else {
            $previous = $this->pages["en"][0];
            $next = $this->pages["en"][1];
          }
          if ($this->page > 1) {
            $html .= "<li><a href=\"".$urlPrevious."\">".$previous."</a></li>";
          }
        }
        if ($this->page < (count($this->data)/$this->byPage)) {
          $html .= "<li><a href=\"".$urlNext."\">".$next."</a></li>";
        }
        $html .= "</ul>";
      }
    }

    return $html;
  }

  /**
   * @return array Data
   */
  public function getData() {
    return $this->data;
  }

  /**
   * @param array Data
   */
  public function setData($data) {
    $this->data = $data;
  }

  /**
   * @return int Number data by page
   */
  public function getByPage() {
    return $this->byPage;
  }

  /**
   * @param int Number data by page
   */
  public function setByPage($nb) {
    if ($nb <= 0) {
      $this->byPage = null;
    } else {
      $this->byPage = $nb;
    }
  }

  /**
   * @return int Current number page
   */
  public function getPage() {
    return $this->page;
  }

  /**
   * @param int Current number page
   */
  public function setPage($page) {
    $this->page = $page;
  }

  /**
   * @return string Url pagination
   */
  public function getUrl() {
    return str_replace("{}", $this->page, $this->url);
  }

}
