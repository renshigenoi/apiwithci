<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createJWT($payload, $secretKey, $expire = 3600) {
    $issuedAt   = time();
    $expireAt   = $issuedAt + $expire;
    $token      = [
        'iat'   => $issuedAt,
        'exp'   => $expireAt,
        'data'  => $payload
    ];
    return JWT::encode($token, $secretKey, 'HS256');
}

function verifyJWT($jwt, $secretKey) {
    try {
        return JWT::decode($jwt, new Key($secretKey, 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}

/**
 * 🔹 Fungsi tambahan: decode langsung dari request Authorization header
 */
function decodeJWTFromRequest() {
    $request    = \Config\Services::request();
    $authHeader = $request->getHeaderLine('Authorization');
    if (!$authHeader) return null;

    $token      = str_replace('Bearer ', '', $authHeader);
    $secretKey  = getenv('jwt.secret');
    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        // ubah ke array agar mudah diakses
        return (array) $decoded;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * ====================================
 * Helper Format Tanggal & Waktu
 * ====================================
 */
$_MONTH2    = array ( 
                "01" => "Jan",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Apr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Aug",
                "09" => "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dec"
            );

function format_date($term="", $format="d-M-Y") 
{ 
    if (!(strpos($term, "-") === false)) {
        $arr_tgl	= explode("-",$term);
        $arr_day	= explode(" ",$arr_tgl[2]);
        if ($arr_tgl[0] <= "1970" && $format=="d-M-Y") 
        {
            return $arr_day[0]."-".$_MONTH2[$arr_tgl[1]]."-".$arr_tgl[0];
        }
        elseif ($arr_tgl[0] <= "1970" && $format=="d M") 
        {
            return $arr_day[0]." ".$_MONTH2[$arr_tgl[1]];
        }
        else
        {
            return date($format,strtotime($term));
        }
    } else if (!(strpos($term, "/") === false)) {
        $arr_tgl	= explode("/",$term);
        $arr_day	= explode(" ",$arr_tgl[2]);
        if ($arr_tgl[0] <= "1970" && $format=="d-M-Y") 
        {
            return $arr_day[0]."-".$_MONTH2[$arr_tgl[1]]."-".$arr_tgl[0];
        }
        elseif ($arr_tgl[0] <= "1970" && $format=="d M") 
        {
            return $arr_day[0]." ".$_MONTH2[$arr_tgl[1]];
        }
        else
        {
            return date($format,strtotime($term));
        }
    } else {
        return "";
    }
}

function format_datetime($term="", $format="d-M-Y H:i:s") 
{ 
    global $_MONTH2;
    if (!(strpos($term, "-") === false))
    {
        $arr_tgl	= explode("-",$term);
        $arr_day	= explode(" ",$arr_tgl[2]);
        if ($arr_tgl[0] <= "1970" && $format=="d-M-Y H:i:s") 
        {
            return $arr_day[0]."-".$_MONTH2[$arr_tgl[1]]."-".$arr_tgl[0];
        }
        elseif ($arr_tgl[0] <= "1970" && $format=="d M H:i:s") 
        {
            return $arr_day[0]." ".$_MONTH2[$arr_tgl[1]];
        }
        else
        {
            return date($format,strtotime($term));
        }
    }
    else
    {
        return "&nbsp;";
    }
}
