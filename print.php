<?php
require_once __DIR__ . '/admin.php';

if (empty($_GET['post'])) {
    echo 'Error：POST_IDが空です。';
    die;
}

$post = get_post($_GET['post']);
if (empty($post)) {
    echo 'Error：POSTが取得できませんでした。';
    die;
}

// 封筒に出力する項目
$name = get_post_meta($post->ID, '名前', true);
$postal_code = mb_str_split(get_post_meta($post->ID, '郵便番号', true));
$prefecture = get_post_meta($post->ID, '都道府県', true);
$city = get_post_meta($post->ID, '市区町村名', true);
$building_address = get_post_meta($post->ID, '番地・建物名・部屋番号', true);
$select = get_post_meta($post->ID, '選択マスク', true);
$terminal_type = get_post_meta($post->ID, '端末', true);

$normal_count = (int) get_post_meta($post->ID, '標準サイズ', true);
$fit_count    = (int) get_post_meta($post->ID, 'ピタッとサイズ', true);
$small_count  = (int) get_post_meta($post->ID, '小さめサイズ', true);
$child_count  = (int) get_post_meta($post->ID, 'こども用サイズ', true);

// 選択マスクの文をプラスする
if (!empty($select)) {
    switch ($select) {
        case '標準サイズ':
            $normal_count++;
            break;
        case 'ピタッとサイズ':
            $fit_count++;
            break;
        case '小さめサイズ':
            $small_count++;
            break;
        case 'こども用サイズ':
            $child_count++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>封筒印刷</title>
</head>

<body>
    <div id="header">
        <img src="./images/postal.png" alt="postal" id="postal-img">
        <div>
            <?php foreach ($postal_code as $p) : ?>
                <span class="postal-code"><?php echo $p; ?></span>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="contents">
        <div id="address">
            <?php echo $prefecture; ?><br>
            <?php echo '　' . $city . ' ' . $building_address; ?>
        </div>
        <div id="name"><?php echo $name; ?> 様</div>
    </div>

    <div id="footer">
        <div id="company">
            <div>
                <img src="./images/yamato-logo.png" alt="company-logo" id="company-logo">
                〇〇工業株式会社
            </div>
            <div>〒639-0000 奈良県...</div>
            <div>TEL：0000-00-0000</div>
        </div>
        <div id="detail">
            <?php echo $terminal_type; ?>-
            標<?php echo $normal_count; ?>-
            ピ<?php echo $fit_count; ?>-
            小<?php echo $small_count; ?>-
            子<?php echo $child_count; ?>-
            <?php echo $post->ID; ?>
        </div>
    </div>
</body>

</html>

<style>
    body {
        margin: 0;
        padding: 30px;
        box-sizing: border-box;
        width: 120mm;
        height: 235mm;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-family: serif;
    }

    #header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #postal-img {
        width: 2.5cm;
        height: 2.5cm;
        display: block;
    }

    .postal-code {
        padding: 3px;
    }

    .postal-code:nth-child(1),
    .postal-code:nth-child(2),
    .postal-code:nth-child(3) {
        border: solid 1.5px red;
    }

    .postal-code:nth-child(4),
    .postal-code:nth-child(5),
    .postal-code:nth-child(6),
    .postal-code:nth-child(7) {
        border: solid 1px red;
    }

    #contents {
        writing-mode: vertical-rl;
        padding: 20px 0;
        height: 100%;
    }

    #address {
        width: 40%;
        font-size: 25px;
        text-align: start;
    }

    #name {
        text-align: center;
        font-size: 38px;
    }

    #company {
        text-align: center;
        border: 2px solid #000;
        padding: 20px;
    }

    #company-logo {
        width: 20px;
    }

    #detail {
        font-size: 10px;
        color: gray;
        text-align: end;
    }
</style>

<script>
    window.onload = function() {
        window.print();
        window.onafterprint = function() {
            window.close();
        };
    };
</script>