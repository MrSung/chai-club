<?php

require_once 'class/item.php';

$items = [];
$itemIds = [];
$itemNames = [];

$items[] = new Item(1, 'Ginger chai', 300, 'images/img_chai_p_1.jpg',
  'チャイと聞いて、「必ずスパイスが入っているお茶」とイメージする人が意外と多いですが、定番のスパイスチャイとして、インドで一般的に使われているのが土ショウガを使ったジンジャーチャイです。');
$items[] = new Item(2,'Cardamon chai', 320, 'images/img_chai_p_2.jpg',
  'カルダモンとは、ショウガ科の多年草のことで、最も古いスパイスの一つであり、「キング・オブ・スパイス」とも言われています。チャイの香りづけの他にコーヒーに取り入れられることもあります。');
$items[] = new Item(3,'Ice chai', 340, 'images/img_chai_p_3.jpg',
  'プレーンチャイを作れるようになったら、冷たいチャイも飲んでみたくなるものです。作り方はさほど大差なく、水の量をやや少なめにした濃いめのチャイを作ります。');
$items[] = new Item(4,'Masala chai', 300, 'images/img_chai_p_4.jpg',
  'マサラとは「混ざったスパイス」の意味です。カレーにも数十種類の混合スパイスを使いますが、カレーで使うものとは異なり、ミルクティーの味を損なわず、風味をプラスできるようなスパイスを選んで使います。');
$items[] = new Item(5,'Chai latte', 280, 'images/img_chai_p_5.jpg',
  'チャイラテとはいわゆるインド式ミルクティーのことで、鍋などで煮出した少量の紅茶に大量のミルクを加えてさらに煮出し、大量の砂糖を入れて味付けしたものです。これにシナモンなどの香辛料を加える場合もあります。');
$items[] = new Item(6,'Plain chai', 260, 'images/img_chai_p_6.jpg',
  'チャイは、ティーポットがなくても鍋があれば作れるミルクティーです。まずはチャイ用の茶葉を探すことになりますが、基本的にミルクティー用のものであればどんな茶葉でも使えます。');

$itemsCount = count($items);

foreach ($items as $item) {
  $itemIds[] = $item->getItemId();
  $itemNames[] = $item->getName();
}