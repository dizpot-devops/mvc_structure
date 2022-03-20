<?php


class DIZtilities
{
    public static function stripAllNonDigits($string) {
        return preg_replace("/[^0-9]/","",$string);
    }

    public static function media_directory() {
        return MEDIA_DIR;
    }

    public static function media_path($file,$size = false) {
        $path =  MEDIA_DIR;
        if($size !== false) {
            $npath = $path . $size . "-";
            if(file_exists($path . $file)) {
                $path = $npath;
            }
        }
        return $path . $file;

    }

    public static function get_media_info($file) {
        $media_info = explode(" ",shell_exec('identify ' . $file));
        $size = explode('x',$media_info[2]);
        return array(
            'path'=>$media_info[0],
            'type'=>$media_info[1],
            'width'=>$size[0],
            'height'=>$size[1],
            'ratio'=>$size[0]/$size[1]
        );
    }

    public static function reroute($path) {
        header("Location: " . ROOT_URL . $path);
    }

    public static function getDatabasePostEntries($post, $identifier = 'DIZB_') {
        $arr = array();
        foreach($post as $key=>$value) {
            if(preg_match('/^' . $identifier . '/',$key)) {
                $key = preg_replace('/^' . $identifier . '/','',$key);
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public static function random($length,$numbers = true, $lc_chars = true, $uc_chars = true) {
        $nums = '0123456789';
        $lc = 'abcdefghijklmnopqrstuvwxyz';
        $uc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input = '';

        if(! ($numbers || $lc_chars || $uc_chars) ) { return ''; }

        if($numbers) { $input .= $nums; }
        if($lc_chars) { $input .= $lc; }
        if($uc_chars) { $input .= $uc; }

        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $length; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }


}