var vueDataAnimal, vueDataPlants, vueDataElements;

vueDataAnimal = {
    index: 0,
    ani_data: [{
        name: '麻雀',
        className: 'bird',
        popUpClassName: 'bird',
        info: '愛吃的：蟲蟲、果實<br>常出沒：有食物的地方<br>稀有度：★',
        info2: '如果票選最適合麻雀的居住地，我阿康伯的農場肯定拿冠軍！這裡沒有食安問題，也沒有鳥網陷阱。有時牠們還把我的頭頂，當作休息區！',
        tagName: '#友善地球栽種規範 #維護生物多樣性 #嚴禁結網捕鳥',
    }, {
        name: '螢火蟲',
        className: 'lightBug',
        popUpClassName: 'lightBug',
        info: '愛吃的：蝸牛、蚯蚓、貝螺等<br>常出沒：乾淨無汙染的地方<br>稀有度：★★★★★',
        info2: '這些火金姑比我阿康伯的女兒還傲嬌，住的地方有任何汙染，就搬家走人！還好我平常就有認真打理農場環境，才讓牠們一住就愛上這裡！',
        tagName: '#友善地球栽種規範 #不用多餘農藥 #維護生態環境',
        tagName: '#友善地球栽種規範 #不用多餘農藥 #維護生態環境'
    }, {
        name: '樹蛙',
        className: 'frog',
        popUpClassName: 'frog',
        info: '愛吃的：各類昆蟲<br>常出沒：農耕地、竹林、果園<br>稀有度：★★',
        info2: '有人以為我農場晚上有音響放音樂，毋系啦！是樹蛙在大合唱！還好我沒有為了種玉米，把樹砍光光，讓牠們有舞台現場Live唱下去！',
        tagName: '#友善地球栽種規範 #維護生物多樣性 #不破壞景觀',
    }, {
        name: '野兔',
        className: 'rabbit',
        popUpClassName: 'rabbit',
        info: '愛吃的：各式嫩葉、蔬果<br>常出沒： 農墾地、草生地…等<br>稀有度：★★★★★',
        info2: '野兔其實是動物界的傻大姊，傻傻地把有除草劑的草葉吃下去，搞得兔命不保！還好我田裡種植前後都沒用除草劑，讓牠們吃飽睡好沒煩惱！',
        tagName: '#友善地球栽種規範 #種植前後不用除草劑',
    }, {
        name: '白鷺鷥',
        className: 'bigBird',
        popUpClassName: 'bigBird',
        info: '愛吃的：蚯蚓、蟲蟲、魚蝦等<br>常出沒：農田、溪河、湖泊<br>稀有度：★★',
        info2: '這裡的白鷺鷥是不折不扣的跟屁蟲！天天跟在我後面，吃翻土而出的蚯蚓蟲蟲！因為牠們知道我有定期拿土去健檢，好的土裡自然有一堆肥蟲！',
        tagName: '#友善地球栽種規範 #做好土壤管理',
    }, {
        name: '老鷹',
        className: 'eagle',
        popUpClassName: 'eagle3',
        info: '愛吃的：小鳥、老鼠、腐肉…等<br>常出沒：農田、魚塭、河口…等<br>稀有度：★★★★',
        info2: '住在附近的老鷹，來我阿康伯的農場就像在走灶咖！哪裡有老鼠、小鳥，牠們比我阿康伯還清楚！我不敢傷害的小動物，老鷹倒是追得很開心！',
        tagName: '#友善地球栽種規範 #維護生物多樣性',
    }, {
        name: '老鷹',
        className: 'eagle2',
        popUpClassName: 'eagle3',
        info: '愛吃的：小鳥、老鼠、腐肉…等<br>常出沒：農田、魚塭、河口…等<br>稀有度：★★★★',
        info2: '住在附近的老鷹，來我阿康伯的農場就像在走灶咖！哪裡有老鼠、小鳥，牠們比我阿康伯還清楚！我不敢傷害的小動物，老鷹倒是追得很開心！',
        tagName: '#友善地球栽種規範 #維護生物多樣性',
    }, {
        name: '草蜥',
        className: 'lizard',
        popUpClassName: 'lizard',
        info: '愛吃的：各種小型蟲蟲<br>常出沒：草生地<br>稀有度：★★',
        info2: '我農場的草蜥，可是樂活代表！田裡沒有亂灑農藥，什麼雜草蟲蟲都有，牠們每天賴在草上曬日光浴，餓了張嘴就能吃飽！希望我退休後，也過得這麼愜意！',
        tagName: '#友善地球栽種規範 #不用多餘農藥 #採物理性防治害蟲',
    }, {
        name: '田鼠',
        className: 'mouse',
        popUpClassName: 'mouse',
        info: '愛吃的：農作物都愛<br>常出沒： 農墾地、荒廢草生地<br>稀有度：★',
        info2: '阿康伯農場的田鼠，每隻都有中二病！每天活在自己的世界，把我的田當零食櫃！後來發現只要用警示燈，就能讓牠們從幻想回到現實！收斂一點！',
        tagName: '#友善地球栽種規範 #維護生物多樣性  #採物理性防治 #嚴禁捕殺田間動物',
    }, ]
}

vueDataPlants = {
    index: 0,
    data: [{
            name: '玉米',
            className: 'plat1',
            enName: 'corn',
            picUrl: 'images/r3.png',
            info: '人家都說農夫是靠天吃飯，我阿康伯還會靠腦袋！像這些玉米，我都特別挑超甜品種給它種下去！收成後還會拿玉米桿去做肥料，一物多用是不是蓋聰明！',
            productArr: ['products/pp_5.png', 'products/pp_2.png', 'products/pp_3.png'],
            tagName: '#友善地球栽種規範 #提升農業知識 #落實廢棄物管理',
            urlLink: [],
            seeMoreProductId: 0,
        },
        {
            name: '綠花椰',
            className: 'plat5',
            enName: 'broccoli',
            picUrl: 'images/r4.png',
            info: '種綠花椰菜，就像養小孩，給它最好的環境，它就能長得頭好壯壯！我常常用有機肥料幫土壤進補，讓綠花椰菜們安心長大！',
            productArr: ['products/pp_5.png'],
            tagName: '#友善地球栽種規範 #做好土壤管理 #推廣有機肥',
            urlLink: [],
            seeMoreProductId: 10,
            
        },
        {
            name: '南瓜',
            className: 'plat2',
            enName: 'pumpkin',
            picUrl: 'images/r0.png',
            info: '這些南瓜，我阿康伯都把它當成金孫惜命命！特別裝了滴灌設施，均勻灑水，不會浪費水資源。這樣養出來的南瓜，才會又大又好！',
            productArr: ['products/pp_9.png'],
            tagName: '#友善地球栽種規範 #善用水資源',
            urlLink: [],
            seeMoreProductId: 6,
            
        },
        {
            name: '雪裡紅',
            className: 'plat4',
            enName: 'juncea',
            picUrl: 'images/r2.png',
            info: '農場裡最勇健的不是我阿康伯，是這些雪裡紅！只要給它們一塊好土，就算在冷冷的冬天種下去，開春也能長好長滿！讓我的田，一年四季都能有好收成！',
            productArr: ['products/pp_12.png'],
            tagName: '#友善地球栽種規範 #提升農業知識 #增加收益',
            urlLink: [],
            seeMoreProductId: 8,
            
        },
        {
            name: '香菜',
            className: 'plat3',
            enName: 'parsley',
            picUrl: 'images/r1.png',
            info: '說香菜有公主病一點也不為過，因為它們喜歡曬太陽，但又怕熱，很難伺候！我都趁11月開始種，還幫它們架高有利排水，讓它們長得亭亭玉立！',
            productArr: ['products/pp_1.png', 'products/pp_8.png', 'products/pp_7.png'],
            tagName: '#友善地球栽種規範 #持續改善農業耕種方式',
            urlLink: [],
            seeMoreProductId: 0,
            
        }
    ]

}

vueDataElements = {
    index: 0,
    data: [{
            name: '減用農用化學品',
            title: '減用農用化學品',
            info: '減少化學肥料、農藥及燃料的使用量，避免傷害田間生物及環境。',
            className: 'p1',
        },
        {
            name: '提升農業知識',
            title: '提升農業知識',
            info: '康寶與專家合作，協助契作農夫提升農業知識與技能，使農作物更有效率地生長。',
            className: 'p6',
        },
        {
            name: '落實廢棄物管理',
            title: '落實廢棄物管理',
            info: '減少對環境的影響，由專人管理農場廢棄物、分類，並以更環保的方式處理廢棄物。',
            className: 'p7',
        },
        {
            name: '維護動物福利',
            title: '維護動物福利',
            info: '制定動物福利相關的標準，協助供應商與農夫管理/飼養農場動物。',
            className: 'p2',
        },
        {
            name: '節能減碳',
            title: '節能減碳',
            info: '透過優化機械設備，致力減少農場的能源使用和溫室氣體排放。',
            className: 'p8',
        },
        {
            name: '土壤管理',
            title: '土壤管理',
            info: '定期收集土壤送檢，使用有機肥來增進土壤肥力，避免錯誤施肥侵蝕或損害土壤。',
            className: 'p3',
        },
        {
            name: '善用水資源',
            title: '善用水資源',
            info: '因應不同的作物種類、農場位置及灌溉設施等，規劃最適合的灌溉方式，妥善運用水資源不浪費。',
            className: 'p9',
        },
        {
            name: '維護生物多樣性',
            title: '維護生物多樣性',
            info: '種植農作物而不破壞景觀，並保護重要生物棲息地，嚴禁危害生物，確保生物多樣性。',
            className: 'p4',
        },
        {
            name: '持續改善耕種',
            title: '持續改善耕種',
            info: '持續改善農業耕種的方式，致力平衡人類、地球及農夫收益的需求。',
            className: 'p5',
        },
        {
            name: '提升地方經濟',
            title: '提升地方經濟',
            info: '協助農夫積極管理所屬農場，幫助其降低經營成本、增加收益、提高競爭力並提升在地經濟。',
            className: 'p10',
        },
        {
            name: '重視勞工福利',
            title: '重視勞工福利',
            info: '確保農夫遵循現行人權或勞工法規制度，並有維持生計的能力。',
            className: 'p11',
        },
    ]
}