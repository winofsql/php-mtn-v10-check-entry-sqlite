<?php
// **************************
// 入力チェック
// **************************
function check_entry( $sqlite ) {

    if( $_POST["kanri"] == "" ) {
        return true;
    }

    $query =<<<QUERY
select
    社員コード,
    氏名
from
    社員マスタ
where
    社員コード = :scode
QUERY;

    try {
        $stmt = $sqlite->prepare($query);
        $stmt->bindValue( ':scode', $_POST["kanri"], PDO::PARAM_STR );
        $stmt->execute();
    }
    catch ( PDOException $e ) {
        $GLOBALS["error"]["db"] .= $GLOBALS["dbname"];
        $GLOBALS["error"]["db"] .= " " . $e->getMessage();
    }

    if ( $GLOBALS["error"]["db"] == " " ) {
        $row = $stmt->fetch();
    }
    else {
        $row = null;
    }

    return $row;
}

// **************************
// 社員コードで存在チェック
// **************************
function check( $sqlite ) {

    $query =<<<QUERY
select
    A.社員コード,
    A.氏名,
    A.フリガナ,
    A.所属,
    A.性別,
    A.給与,
    A.手当,
    A.管理者,
    B.氏名 as 管理者名,
    strftime('%Y-%m-%d', A.生年月日) as 生年月日
from
    社員マスタ A
    left outer join
        社員マスタ B
    on A.管理者 = B.社員コード
where
    A.社員コード = :scode
QUERY;

    try {
        $stmt = $sqlite->prepare($query);
        $stmt->bindValue( ':scode', $_POST["scode"], PDO::PARAM_STR );
        $stmt->execute();
    }
    catch ( PDOException $e ) {
        $GLOBALS["error"]["db"] .= $GLOBALS["dbname"];
        $GLOBALS["error"]["db"] .= " " . $e->getMessage();
    }

    if ( $GLOBALS["error"]["db"] == " " ) {
        $row = $stmt->fetch();
    }
    else {
        $row = null;
    }
    return $row;
}

// **************************
// 更新処理
// **************************
function insert( $mysqli ) {


    $query = <<<QUERY

insert into 社員マスタ
    (社員コード
    ,氏名
    ,フリガナ
    ,所属
    ,性別
    ,給与
    ,手当
    ,管理者
    ,生年月日
    )
    values( :scode
    ,:sname
    ,:fname
    ,:syozoku
    ,:seibetsu
    ,:kyuyo
    ,:teate
    ,:kanri
    ,:birth
    )

QUERY;

    try {
        $stmt = $sqlite->prepare($query);
        $stmt->bindValue( ':sname', $_POST["sname"], PDO::PARAM_STR );
        $stmt->bindValue( ':fname', $_POST["fname"], PDO::PARAM_STR );
        $stmt->bindValue( ':syozoku', $_POST["syozoku"], PDO::PARAM_STR );
        $stmt->bindValue( ':seibetsu', $_POST["seibetsu"]+0, PDO::PARAM_INT );
        $stmt->bindValue( ':kyuyo', intval($_POST["kyuyo"])+0, PDO::PARAM_INT );
        if ( $_POST["teate"] == "" ) {
            $stmt->bindValue( ':teate', null, PDO::PARAM_NULL );
        }
        else {
            $stmt->bindValue( ':teate', $_POST["teate"]+0, PDO::PARAM_INT );
        }
        if ( $_POST["kanri"] == "" ) {
            $stmt->bindValue( ':kanri', null, PDO::PARAM_NULL );
        }
        else {
            $stmt->bindValue( ':kanri', $_POST["kanri"], PDO::PARAM_STR );
        }
        $stmt->bindValue( ':birth', $_POST["birth"], PDO::PARAM_STR );
        $stmt->bindValue( ':scode', $_POST["scode"], PDO::PARAM_STR );
        $stmt->execute();
    }
    catch ( PDOException $e ) {
        $GLOBALS["error"]["db"] .= $GLOBALS["dbname"];
        $GLOBALS["error"]["db"] .= " " . $e->getMessage();
    }

}

function update( $sqlite ) {

// ヒアドキュメント
    $query = <<<QUERY
update 社員マスタ set
    氏名 = :sname,
    フリガナ = :fname,
    所属 = :syozoku,
    性別 = :seibetsu,
    給与 = :kyuyo,
    手当 = :teate,
    管理者 = :kanri,
    生年月日 = :birth
where 社員コード = :scode
QUERY;

    try {
        $stmt = $sqlite->prepare($query);
        $stmt->bindValue( ':sname', $_POST["sname"], PDO::PARAM_STR );
        $stmt->bindValue( ':fname', $_POST["fname"], PDO::PARAM_STR );
        $stmt->bindValue( ':syozoku', $_POST["syozoku"], PDO::PARAM_STR );
        $stmt->bindValue( ':seibetsu', $_POST["seibetsu"]+0, PDO::PARAM_INT );
        $stmt->bindValue( ':kyuyo', intval($_POST["kyuyo"])+0, PDO::PARAM_INT );
        if ( $_POST["teate"] == "" ) {
            $stmt->bindValue( ':teate', null, PDO::PARAM_NULL );
        }
        else {
            $stmt->bindValue( ':teate', $_POST["teate"]+0, PDO::PARAM_INT );
        }
        if ( $_POST["kanri"] == "" ) {
            $stmt->bindValue( ':kanri', null, PDO::PARAM_NULL );
        }
        else {
            $stmt->bindValue( ':kanri', $_POST["kanri"], PDO::PARAM_STR );
        }
        $stmt->bindValue( ':birth', $_POST["birth"], PDO::PARAM_STR );
        $stmt->bindValue( ':scode', $_POST["scode"], PDO::PARAM_STR );
        $stmt->execute();
    }
    catch ( PDOException $e ) {
        $GLOBALS["error"]["db"] .= $GLOBALS["dbname"];
        $GLOBALS["error"]["db"] .= " " . $e->getMessage();
    }

}


// **************************
// デバッグ表示
// **************************
function debug_print() {

    print "<pre class=\"m-5\">";
    print_r( $_GET );
    print_r( $_POST );
    print_r( $_SESSION );
    print_r( $_FILES );
    print "</pre>";

}

