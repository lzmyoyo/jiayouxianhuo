<?php

class Page {

    public $url;
    public $maxpage;
    public $lipg, $thpg, $ngcm;

    public function __construct() {
        $this->url = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    public function __toString() {
        return '分页类';
    }

    public function showpage($count, $page, $pagesize = 20) {
        $num = ceil($count / $pagesize);    //一共有多少页
        $this->maxpage = $num;
        $lastlipage = ceil($num / 5);    //总共可以分几排
        $this->lipg = $lastlipage;
        $lastli = $num % 5;       //最后一排剩余的页数
        if ($page % 5 > 0) {
            $pagenum = ceil($page / 5);     //当前页在第几排
            $this->ngcm = ceil($page / 4);
        } else {
            $pagenum = ceil($page / 5) + 1;     //当前页在第几排
            $this->ngcm = ceil($page / 4);
        }
        $this->thpg = $pagenum;
        $n = 1;
        while ($page - 4 * $n >= 1) {
            $n++;
        }
        $stai = $n * 4 - 3;
        $starstr = strpos($this->url, 'page');
        if (!$starstr) {

            $urlArr = parse_url($this->url);
            if(isset($urlArr['query']) && $urlArr['query']) {
                $urlarr = $this->createpage($n, $this->url, '&page');
            } else {
                $urlarr = $this->createpage($n, $this->url, '?page');
            }

        } else {
            $string = '';
            for ($i = $stai; $i < $stai + 5; $i++) {
                if ($i <= $this->maxpage) {
                    $string = preg_replace("/page=(0|[1-9][0-9]*)/", "page=$i", $this->url);
                    if ($i == $page) {
                        $showindex = 'class="showindex"';
                    } else {
                        $showindex = 'class="shownoindex"';
                    }
                    $urlarr[] = '<a href="' . $string . '" ' . $showindex . '>' . $i . '</a>';
                }
            }
            $topnext = $this->cratetopnext($page, $string, 1);
            $urlarr[-1] = $topnext['top'];
            $urlarr[] = $topnext['next'];
        }
        if ($urlarr[0]) {     //这里表示有分页，没有分页就什么都不返回
            $urlarr[-3] = '<span>共<label>' . $count . '</label>条</span>';
            //$urlarr[-2] = '<span>当前第<label>'.$page.'</label>页</span>';
            ksort($urlarr);
            $divpage = '<div id="page">';
            foreach ($urlarr as $val) {
                $divpage.=$val;
            }
            $divpage .= '</div>';
            return $divpage;
        }
    }

    public function createpage($page, $url, $str) {
        $stai = $page * 4 - 3;
        for ($i = $stai; $i < $stai + 5; $i++) {
            if ($i <= $this->maxpage) {
                if ($i == 1) {
                    $showindex = 'class="showindex"';
                } else {
                    $showindex = 'class="shownoindex"';
                }
                $strc[] = '<a href="' . $url . $str . '=' . $i . '" ' . $showindex . '>' . $i . '</a>';
            }
        }
        $aurl = $url . $str . '=';
        $topnext = $this->cratetopnext($page, $aurl);
        $strc[-1] = $topnext['top'];
        $strc[] = $topnext['next'];
        return $strc;
    }

    public function cratetopnext($page, $url, $type = '') {
        $strpoint = $strpoin = '';
        if ($type) {
            if ($page + 1 <= $this->maxpage) {
                $next = $page + 1;
                $string = preg_replace("/page=(0|[1-9][0-9]*)/", "page=$next", $url);
                if ($this->lipg != $this->thpg) {
                    $laspage = preg_replace("/page=(0|[1-9][0-9]*)/", "page=" . $this->maxpage, $url);
                    $nump = ($this->ngcm * 4) + 1;
                    $nedpg = preg_replace("/page=(0|[1-9][0-9]*)/", "page=" . $nump, $url);
                    $strpoint = '<a href="' . $nedpg . '" class="noborder">...</a><a href="' . $laspage . '" class="shownoindex">' . $this->maxpage . '</a>';
                }
                $alpage['next'] = $strpoint . '<a href="' . $string . '" id="next">下一页</a>';
            }
            if ($page - 1 >= 1) {
                if ($this->thpg > 1) {
                    $firstr = preg_replace("/page=(0|[1-9][0-9]*)/", "page=1", $url);
                    $nump = ($this->ngcm * 4) - 4;
                    $nedpg = preg_replace("/page=(0|[1-9][0-9]*)/", "page=" . $nump, $url);
                    $strpoin = '<a href="' . $firstr . '" class="shownoindex">1</a><a href="' . $nedpg . '" class="noborder">...</a>';
                }
                $top = $page - 1;
                $string = preg_replace("/page=(0|[1-9][0-9]*)/", "page=$top", $url);
                $alpage['top'] = '<a href="' . $string . '" id="top">上一页</a>' . $strpoin;
            }
        } else {
            if ($page + 1 <= $this->maxpage) {
                if ($this->lipg != $this->thpg) {
                    $nedpg = $this->ngcm * 4;
                    $nedpg+=1;
                    $strpoint = '<a href="' . ($url . $nedpg) . '" class="noborder">...</a><a href="' . $url . $this->maxpage . '">' . $this->maxpage . '</a>';
                }
                $alpage['next'] = $strpoint . '<a href="' . $url . ($page + 1) . '" id="next">下一页</a>';
            }
            if ($page - 1 >= 1) {
                if ($this->thpg > 1) {
                    $strpoin = '<a href="' . $url . '1' . '" class="shownoindex">1</a><a href="" class="noborder">...</a>';
                }
                $alpage['top'] = '<a href="' . $url . ($page - 1) . '" id="top">上一页</a>' . $strpoin;
            }
        }
        if (!isset($alpage['top'])) {
            $alpage['top'] = '';
        }
        if (!isset($alpage['next'])) {
            $alpage['next'] = '';
        }
        return $alpage;
    }

}

?>