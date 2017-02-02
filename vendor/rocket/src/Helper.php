<?php

namespace Rocket;

class Helper
{
    public function css($file) 
    {
        if(is_array($file)):
            $value = '';
            foreach ($file as $css):
                 $value .= '<link href="' . URL . 'public/css/'.$css.'.css" rel="stylesheet" media="screen"/>'."\n";
            endforeach;
        else:
            $value = '<link href="' . URL . 'public/css/'.$file.'.css" rel="stylesheet" media="screen"/>';
        endif;
        return $value;
    }

    public function js($file) 
    {
        if(is_array($file)):
            $value = '';
            foreach ($file as $js):
                $value .= '<script src="' . URL . 'public/js/' . $js . '.js"></script>'."\n";
            endforeach;
        else:
            $value .= '<script src="' . URL . 'public/js/' . $file . '.js"></script>';
        endif;
        return $value;
    }
    
     public function slug($string) {
        if (is_string($string)):
            $string = strtolower(trim(utf8_decode($string)));

            $before = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
            $after = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $string = strtr($string, utf8_decode($before), $after);

            $replace = array(
                '/[^a-z0-9.-]/' => '-',
                '/-+/' => '-',
                '/\-{2,}/' => ''
            );
            $string = preg_replace(array_keys($replace), array_values($replace), $string);
        endif;
        return $string;
    }
    
    public function textLimit($text,$limit) {
        $txt = strip_tags($text);
        $lmt_char = substr($txt, 0, $limit);
        $pos = strrpos($lmt_char, ' ');
        return substr($lmt_char, 0, $pos) . ' ...';
    }

    public function alert($text,$type){
        if($type == 'gren'){$type = 'success';}
        elseif($type == 'red'){$type = 'danger';}
        elseif($type == 'blue'){$type = 'info';}
        elseif($type == 'orange'){$type = 'warning';}
        else{$type = '';}
        echo '<div class="alert alert-'.$type.' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.$text.'</div>';
    }
    
    public function flash($text,$type)
    {
        if($type == 'gren'){$type = 'success';}
        elseif($type == 'red'){$type = 'danger';}
        elseif($type == 'blue'){$type = 'info';}
        elseif($type == 'orange'){$type = 'warning';}
        else{$type = '';}
        return '<div class="alert alert-'.$type.' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.$text.'</div>';
    } 
    
    public function pagination($qtd_pages, $page,$qtd_links,$url){
        if($qtd_pages > 1):
            echo '<nav aria-label="Page navigation"><ul class="pagination">';
            for($i = 1; $i <= $qtd_pages; $i++):
                if($i >= ($page - $qtd_links) && $i <= ($page + $qtd_links)):
                    if($i == $page):
                        echo '<li class="active"><a href="'.URL.$url.$i.'">'.$i.'</li>';
                    else:
                        echo '<li><a href="'.URL.$url.$i.'">'.$i.'</a></li>';
                    endif;
                endif;
            endfor;
            echo '</ul></nav>';
        endif;
    }
    
    public function getUrlBase() {
        $dominio = $_SERVER['HTTP_HOST'];
        $url = "http://" . $dominio . "/";
        return $url;
    }

    public function getUrlCurrent() {
        $dominio = $_SERVER['HTTP_HOST'];
        $url = "http://" . $dominio . $_SERVER['REQUEST_URI'];
        return $url;
    }
    
    public function getIpClient() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    public function getBrowser() {
        $user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_DEFAULT);
        $browsers = array(
            'OPR' => 'Opera',
            'Flock' => 'Flock',
            'Edge' => 'Edge',
            'Chrome' => 'Chrome',
            'Opera.*?Version' => 'Opera',
            'Opera' => 'Opera',
            'MSIE' => 'Internet Explorer',
            'Internet Explorer' => 'Internet Explorer',
            'Trident.* rv' => 'Internet Explorer',
            'Shiira' => 'Shiira',
            'Firefox' => 'Firefox',
            'Chimera' => 'Chimera',
            'Phoenix' => 'Phoenix',
            'Firebird' => 'Firebird',
            'Camino' => 'Camino',
            'Netscape' => 'Netscape',
            'OmniWeb' => 'OmniWeb',
            'Safari' => 'Safari',
            'Mozilla' => 'Mozilla',
            'Konqueror' => 'Konqueror',
            'icab' => 'iCab',
            'Lynx' => 'Lynx',
            'Links' => 'Links',
            'hotjava' => 'HotJava',
            'amaya' => 'Amaya',
            'IBrowse' => 'IBrowse',
            'Maxthon' => 'Maxthon',
            'Ubuntu' => 'Ubuntu Web Browser'
        );

        foreach ($browsers as $key => $value):
            if (preg_match('|' . $key . '.*?([0-9\.]+)|i', $user_agent)):
                return $value;
            endif;
        endforeach;
    }

    public function getPlatform() {
        $user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_DEFAULT);
        $platforms = array(
            'windows nt 10.0' => 'Windows 10',
            'windows nt 6.3' => 'Windows 8.1',
            'windows nt 6.2' => 'Windows 8',
            'windows nt 6.1' => 'Windows 7',
            'windows nt 6.0' => 'Windows Vista',
            'windows nt 5.2' => 'Windows 2003',
            'windows nt 5.1' => 'Windows XP',
            'windows nt 5.0' => 'Windows 2000',
            'windows nt 4.0' => 'Windows NT 4.0',
            'winnt4.0' => 'Windows NT 4.0',
            'winnt 4.0' => 'Windows NT',
            'winnt' => 'Windows NT',
            'windows 98' => 'Windows 98',
            'win98' => 'Windows 98',
            'windows 95' => 'Windows 95',
            'win95' => 'Windows 95',
            'windows phone' => 'Windows Phone',
            'windows' => 'Unknown Windows OS',
            'android' => 'Android',
            'blackberry' => 'BlackBerry',
            'iphone' => 'iOS',
            'ipad' => 'iOS',
            'ipod' => 'iOS',
            'os x' => 'Mac OS X',
            'ppc mac' => 'Power PC Mac',
            'freebsd' => 'FreeBSD',
            'ppc' => 'Macintosh',
            'linux' => 'Linux',
            'debian' => 'Debian',
            'sunos' => 'Sun Solaris',
            'beos' => 'BeOS',
            'apachebench' => 'ApacheBench',
            'aix' => 'AIX',
            'irix' => 'Irix',
            'osf' => 'DEC OSF',
            'hp-ux' => 'HP-UX',
            'netbsd' => 'NetBSD',
            'bsdi' => 'BSDi',
            'openbsd' => 'OpenBSD',
            'gnu' => 'GNU/Linux',
            'unix' => 'Unknown Unix OS',
            'symbian' => 'Symbian OS'
        );

        foreach ($platforms as $key => $value):
            if (preg_match('|' . preg_quote($key) . '|i', $user_agent)):
                return $value;
            endif;
        endforeach;
    }
    
    public function findCep($cep)
    {
        if(preg_match("/^[0-9]{8}$/",$cep)):
            $curl = curl_init("http://viacep.com.br/ws/{$cep}/json/");

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

            $return = curl_exec($curl);
            curl_close($curl);

            return $return;
        endif;
    }
    
}